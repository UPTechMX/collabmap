<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

	$proyectos = $db->query("SELECT * FROM Proyectos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
	$etapas = $db->query("SELECT * FROM Etapas ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#prySel").change(function(event) {
			var pryId = $(this).val();
			$('#pryId').val(pryId);
		});
		$("#etapaSel").change(function(event) {
			var etapa = $(this).val();
			$('#etapa').val(etapa);
		});

		$('#download').click(function(event) {
			if($('#pryId').val() != '' && $('#etapaSel').val()){
				$("#formaCte").submit();
			}
		});
	});
</script>
<div style="text-align: center;margin-bottom: 15px;">
	<h2>Descargas de cuestionarios</h2>
</div>

<div style="margin: 15px;">
	<div class="row justify-content-md-center">
		<div class="col-4">
			<select class="form-control" id="prySel">
				<option value="">Selecciona un proyecto</option>
				<?php foreach ($proyectos as $p){ ?>
					<option value="<?php echo $p['id'] ?>"><?php echo $p['nombre']; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-4">
			<select class="form-control" id="etapaSel">
				<option value="">Selecciona una etapa</option>
				<?php foreach ($etapas as $p){ ?>
					<option value="<?php echo $p['nomInt'] ?>"><?php echo $p['nombre']; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-2">
			<span class="btn btn-sm btn-shop" id="download">Descargar</span>
		</div>
	</div>
	<form id="formaCte" action="descargas/archivos/checklist.php" target="_blank" method="post">
		<input type="hidden" name="pryId" id="pryId" />
		<input type="hidden" name="etapa" id="etapa" />
	</form>
</div>

