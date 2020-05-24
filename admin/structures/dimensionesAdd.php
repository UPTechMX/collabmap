<?php  

	include_once '../../lib/j/j.func.php';

	// $p = $_POST;
	// print2($_POST);

	if($_POST['dimensionId'] != ''){
		$datM = $db-> query("SELECT * FROM Dimensiones WHERE id = $_POST[dimensionId]")->fetch(PDO::FETCH_ASSOC);
	}
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
					echo "dat.elemId = $_POST[elemId];";
					echo "dat.type = '$_POST[type]'";
				}
			?>
			// console.log(dat);
			if(allOk){

				var rj = jsonF('admin/structures/json/json.php',{datos:dat,acc:acc,opt:4});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					var elemId = <?php echo $_POST['elemId']; ?>;
					var type = "<?php echo $_POST['type']; ?>";
					$('#dimensionesList').load(rz+'admin/structures/dimensionesList.php',{ajax:1,elemId:elemId,type:type});
					<?php if(isset($_POST['dimensionId'])): ?>
						$("#dimCol_<?php echo $_POST['dimensionId'];?>").text(dat.nombre);
					<?php else:?>
						var elemId = <?php echo $_POST['elemId']; ?>;
						var type = "<?php echo $_POST['type']; ?>";
						$('#dimensionesArbol').load(rz+'admin/structures/dimensionesArbol.php',{ajax:1,elemId:elemId,type:type});
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
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
