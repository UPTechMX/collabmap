<?php  

	include_once '../../../../lib/j/j.func.php';

	// $p = $_POST;
	// print2($_POST);

	if($_POST['dimensionId'] != ''){
		$datM = $db
		-> query("
			SELECT d.*, kml.id as KMLId
			FROM Dimensiones d 
			LEFT JOIN KML kml ON kml.elemId = d.id AND kml.type = 'dim'
			WHERE d.id = $_POST[dimensionId]")
		->fetch(PDO::FETCH_ASSOC);
	}

	
?>

<script type="text/javascript">
	$(document).ready(function() {

		<?php if (isset($_POST['dimensionId'])){ ?>
			var elemId = '<?= $_POST['dimensionId']; ?>';
			var type = 'dim';

			$('#btnKML').click(function (e) {
				e.preventDefault();
				popUpImg('admin/administration/targets/structure/importKML.php',{elemId:elemId,type:type});
			});

			$('#delKML').click(function (e) {
				e.preventDefault();
				var dat = {};

				dat.kmlId = $('#kmlId').val();
				var rj = jsonF('admin/administration/targets/structure/json/json.php',{datos:dat,acc:9,opt:5});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
				}
			});

		<?php } ?>


		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:'white'});
		});


		$('#nombre').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				event.preventDefault();
			}
		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var numDims = $('#tabDims tr').length;
			dat.nivel = numDims+1;

			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['dimensionId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[dimensionId];";
					echo "delete dat.nivel;";
				}else{
					echo 'var acc = 1;';
					echo "dat.elemId = $_POST[targetsId];";
					echo "dat.type = 'structure'";
				}
			?>
			// console.log(dat);
			if(allOk){

				var rj = jsonF('admin/administration/targets/structure/json/json.php',{datos:dat,acc:acc,opt:4});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#dimensionesList').load(rz+'admin/administration/targets/structure/dimensionesList.php',{ajax:1,targetsId:<?php echo $_POST['targetsId']; ?>});
					<?php if(isset($_POST['dimensionId'])): ?>
						$("#dimCol_<?php echo $_POST['dimensionId'];?>").text(dat.nombre);
					<?php else:?>
						$('#dimensionesArbol').load(rz+'admin/administration/targets/structure/dimensionesArbol.php',{ajax:1,targetsId:<?php echo $_POST['targetsId']; ?>});
					<?php endif;?>
				}
			}

		});

	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('level'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR('name'); ?></td>
				<td>
					<input type="text" value="<?php echo $datM['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<?php if (isset($_POST['dimensionId'])){ ?>
				<?php if (!empty($datM['KMLId'])){ ?>
					<tr>
						<td>KML:</td>
						<td><span id="delKML" class="btn btn-sm btn-cancel"><?= TR('delete'); ?></span></td>
						<td><input type="hidden" id="kmlId" value="<?= $datM['KMLId']; ?>" /></td>
					</tr>
					<tr>
						<?php $_POST['KMLId'] = $datM['KMLId']; ?>
						<td colspan="2">
							<?php
								include_once 'KMLview.php';

							?>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td>KML:</td>
						<td>
							<div>
								<span id="btnKML" class="btn btn-sm btn-primary"><?= TR('upload'); ?></span>
							</div>
						</td>
						<td>
							<input type="hidden" id="kmlFile" name="kmlFile" />
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
