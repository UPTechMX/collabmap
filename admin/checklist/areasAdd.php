<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50);
	$p = $_POST;
	// print2($_POST);

	if($_POST['areaId'] != ''){
		$datM = $db-> query("SELECT * FROM Areas WHERE id = $_POST[areaId]")->fetch(PDO::FETCH_ASSOC);
	}

	$sql = "SELECT b.id as bId, c.id as cId 
		FROM Bloques b 
		LEFT JOIN Checklist c ON b.checklistId = c.id
		WHERE b.id = $_POST[bloqueId]";

	$info = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
	$identificador = "a_$info[cId]_$info[bId]_";


?>

<script type="text/javascript">
	$(document).ready(function() {
		// console.log(checklistId);
		$('#modal').css({width:''});
		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#nombre').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			// console.log('asas');
			if(keycode == '13'){
				event.preventDefault();
			}
		});

		<?php if(isset($_POST['areaId'])): ?>
			$('#chkIdentificador').click(function(event) {
				if($('#identificador').is(':visible')){
					$('#identificador').val("<?php echo $datM['identificador'];?>")
					$('#identificador').toggle();
				}else{
					conf('Cambiar el identificador modifica las asociaciones entre bloques de distintos checklist',{},function(){
						$('#identificador').toggle();
					});
				}
			});
		<?php endif; ?>



		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['areaId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[areaId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.bloquesId = $_POST[bloqueId];";
					echo 'dat.orden = $("#areasSort li").length+1;';
					echo 'dat.identificador = "'.$identificador.'";';
				}
			?>

			if(allOk){
				var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:acc,opt:6,chkId:checklistId});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#areasList').load(rz+'admin/checklist/areasList.php',
						{ajax:1,bloqueId:<?php echo $_POST['bloqueId']; ?>});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['areaId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			área
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
				<td>Nombre</td>
				<td>
					<input type="text" value="<?php echo $datM['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Ponderador del área</td>
				<td>
					<input type="text" value="<?php echo $datM['valMax'] != ''?$datM['valMax']:100; ?>" 
						name="valMax" id="valMax" class="form-control" >
				</td>
				<td></td>
			</tr>
			<?php if(isset($_POST['areaId'])): ?>
				<tr>
					<td><span class="btn btn-sm btn-default" id="chkIdentificador">Cambiar identificador</span></td>
					<td>
						<input type="text" value="<?php echo $datM['identificador']; ?>" 
						name="identificador" id="identificador" class="form-control oblig" style="display: none;" />
					</td>
					<td></td>
				</tr>
			<?php endif; ?>

		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
