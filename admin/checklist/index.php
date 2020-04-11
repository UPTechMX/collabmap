<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist



?>

<script type="text/javascript">
	var checklistId = '';
	$(document).ready(function() {

		$('#checklistsSel').change(function(event) {
			var checklistId = $(this).val();
			if(checklistId != ""){
				$('#edtChklist').show();
			}else{
				$('#edtChklist').hide();
			}
		});

		$('#checklists').on('click', '.edtInfoChk', function(event) {
			event.preventDefault();
			checklistId = $(this).closest('li').attr('id').split('_')[1];
			// console.log(checklistId);
			popUp('admin/checklist/checklistAdd.php',{checklistId:checklistId},function(e){},{});

		});

		$('#checklists').on('click', '.edtChk', function(event) {
			event.preventDefault();
			checklistId = $(this).closest('li').attr('id').split('_')[1];
			$('#bloques').load(rz+'admin/checklist/bloques.php',{checklistId:checklistId});
			$('#general').load(rz+'admin/checklist/general.php',{checklistId:checklistId});
			$('#areas').empty();
			$('#preguntas').empty();
		});

		$('#checklists').on('click', '.condChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			popUp('admin/checklist/condiciones.php',{eleId:checklistId,aplicacion:'chk'},function(e){},{});
		});

		$('#checklists').on('click', '.tPromChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			popUp('admin/checklist/chkTipoProm.php',{chkId:checklistId},function(e){},{});
		});

		var coord = <?php echo $coord; ?>;
		// console.log(coord);

		$('#addChk').click(function(event) {
			popUp('admin/checklist/checklistAdd.php',{},function(e){},{});
		});

		$('#dupChk').click(function(event) {
			popUp('admin/checklist/checklistCopy.php',{},function(e){},{});
		});

	});
</script>
<div class="row">
	<div class="col-3">
		<div class="nuevo">Etapa</div>
		<select class="form-control" id="etapasSel">
			<option value="">- - - - - - - -</option>
			<?php foreach ($etapas as $e){ ?>
				<option value="<?php echo $e['nomInt']; ?>"><?php echo $e['nombre']; ?></option>
			<?php } ?>
		</select>
		<span id="addChk" class="btn btn-sm btn-shop" style="margin: 10px 0px;">Agregar Cuestionario</span>
		<span id="dupChk" class="btn btn-sm btn-shop">Duplicar Cuestionario</span>
	</div>
	<div class="col-3" >
		<div class="nuevo">Checklist</div>
		<div style="height: 150px;overflow-y: auto;">			
			<ul class="list-group" id="checklists">			
			</ul>
		</div>
	</div>
</div>
<hr/>
<div class="row" id="general" style="margin-top:20px;margin-bottom: 20px;"></div>
<div class="row">
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="bloques"></div>
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="areas"></div>
</div>
<div id="preguntas"></div>
