<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);
	$chkInfo = $db->query("SELECT * FROM ChecklistExt WHERE id = $_POST[checklistId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$cuantos = $db->query("SELECT COUNT(*) FROM VisitasExt WHERE checklistId = $_POST[checklistId]")->fetchAll(PDO::FETCH_NUM)[0];

	// print2($cuantos);
	
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#descargaInfo').click(function(event) {
			console.log('asas');
			$('#descForma').submit();
		});
	});
</script>

<div class="nuevo">Información de la encuesta</div>

<table class="table">
	<tr>
		<th>Nombre de la encuesta:</th>
		<td style="text-align: right;"><?php echo $chkInfo['nombre']; ?></td>
	</tr>
	<tr>
		<th>Contraseña:</th>
		<td style="text-align: right;"><?php echo $chkInfo['pwd']; ?></td>
	</tr>
	<tr>
		<th>Localización:</th>
		<td style="text-align: right;"><?php echo $chkInfo['localizacion'] == 1?'Sí':'No'; ?></td>
	</tr>
	<tr>
		<th>Encuestas recibidas:</th>
		<td style="text-align: right;"><?php echo $cuantos[0]; ?></td>
	</tr>
	<tr>
		<th>Descargar:</th>
		<td style="text-align: right;">
			<i class="glyphicon glyphicon-download manita" id="descargaInfo" ></i>
			<form action="descargas/externasSinValor.php" method="POST" target="_blank" id="descForma">
				<input type="hidden" name="chkId" value="<?php echo $_POST['checklistId']; ?>">
			</form>
		</td>
	</tr>
</table>