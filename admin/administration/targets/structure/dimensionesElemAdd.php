<?php  

	include_once '../../../../lib/j/j.func.php';

	$p = $_POST;
	// print2($_POST);

	if($_POST['dimensionElemId'] != ''){
		$datM = $db 
			-> query("
				SELECT de.*, kml.id as KMLId 
				FROM DimensionesElem de 
				LEFT JOIN KML kml ON kml.elemId = de.id AND kml.type = 'de'
				WHERE de.id = $_POST[dimensionElemId]
				")
			->fetch(PDO::FETCH_ASSOC);

		// print2($datM);
	}

	$dimensionesId = $_POST['dimensionElemId'] != ''?$datM['dimensionesId']:$_POST['dimensionId'];
	// print2($dimensionesId);
	$dimension = $db->query("SELECT * FROM Dimensiones WHERE id = $dimensionesId")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($dimension);
	$numDims = $db->query("SELECT COUNT(*) FROM Dimensiones 
		WHERE elemId = $_POST[targetsId] AND type = 'structure' ")->fetchAll(PDO::FETCH_NUM)[0][0];
	// print2($numDims);
	$ultimo = $numDims == $dimension['nivel'];


?>

<script type="text/javascript">
	$(document).ready(function() {

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


		<?php if (isset($_POST['dimensionElemId'])){ ?>
			var elemId = '<?= $_POST['dimensionElemId']; ?>';
			var type = 'de';

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



		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['dimensionElemId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[dimensionElemId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.padre = $_POST[padreId];";
					echo "dat.dimensionesId = $_POST[dimensionId];";
				}
			?>

			console.log(dat);

			if(allOk){
				// console.log(dat);
				var rj = jsonF('admin/administration/targets/structure/json/json.php',{datos:dat,acc:acc,opt:5});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#dimensionesElemList_'+<?php echo $_POST['dimensionId']; ?>)
					.load(rz+'admin/administration/targets/structure/dimensionesElemList.php',{
						ajax:1,
						targetsId:<?php echo $_POST['targetsId']; ?>,
						padreId:<?php echo $_POST['padreId']; ?>,
						dimensionId:<?php echo $_POST['dimensionId']; ?>
					});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('element'); ?>
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
			<tr>
				<td><?php echo TR('ID'); ?></td>
				<td>
					<input type="text" value="<?php echo $datM['ID2']; ?>" name="ID2" id="ID2" class="form-control" >
				</td>
				<td></td>
			</tr>
			<?php if (isset($_POST['dimensionElemId'])){ ?>
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
