<?php  

	include_once '../../lib/j/j.func.php';

	// print2($_POST);

	if($_POST['checklistId'] != ''){
		$datM = $db-> query("SELECT * FROM ChecklistExt WHERE id = $_POST[checklistId]")->fetch(PDO::FETCH_ASSOC);
	}
?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		// $('#tipoReembolso').val('<?php echo $datM['tipoReembolso']; ?>');
		$('#tipoReembolso').change(function(event) {
			var tipoReembolso = $(this).val();
			switch(tipoReembolso){
				case "0":
					$('#TRreembolso').hide();
					$('#TRmaxReembolso').hide();
					$('#reembolso').val('');
					$('#maxReembolso').val('');
					break;
				case "1":
					$('#TRreembolso').show();
					$('#TRmaxReembolso').show();
					// $('#reembolso').val('');
					// $('#maxReembolso').val('');
					break;
				case "2":
					$('#TRreembolso').hide();
					$('#TRmaxReembolso').show();
					// $('#reembolso').val('');
					// $('#maxReembolso').val('');
					break;

			}
		});
		$('#tipoReembolso').trigger('change');

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.localizacion = $('#localizacion').is(':checked')?1:0;
			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['checklistId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[checklistId];";
				}else{
					echo 'var acc = 1;';
				}
			?>

			if(allOk){

				var rj = jsonF('admin/checklistExt/json/json.php',{datos:dat,acc:acc,opt:11});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#checklistList').load(rz+'admin/checklistExt/checklistList.php',{ajax:1});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>
			<?php if(isset($_POST['checklistId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo
			<?php endif; ?>
			checklist
		</h4>
	</div>
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
				<td>Siglas</td>
				<td><input type="text" value="<?php echo $datM['siglas']; ?>" name="siglas" id="siglas" class="form-control oblig"></td>
				<td></td>
			</tr>

			<tr>
				<td>Contraseña</td>
				<td><input type="text" value="<?php echo $datM['pwd']; ?>" name="pwd" id="pwd" class="form-control oblig"></td>
				<td></td>
			</tr>
			<tr>
				<td>Requerir localización</td>
				<td>
					<input type="checkbox"  name="localizacion" id="localizacion" 
					class="form-control oblig" <?php echo $datM['localizacion'] == 1?'checked':'' ; ?>>
				</td>
				<td></td>
			</tr>


		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
