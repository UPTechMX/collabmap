<?php  

	include_once '../lib/j/j.func.php';
	include_once raiz().'/lib/php/usrInt.php';

	checaAcceso(50);
	$usrId = $_SESSION['IU']['admin']['usrId'];
	$usr = new Usuario($usrId);
	// print2($_POST);
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>

<script src="<?php echo aRaizHtml();?>lib/js/graficas.js"></script>

<script type="text/javascript">
	var filtros = {};
	$(document).ready(function() {
		$("#masivo").click(function(event) {
			popUp('admin/inicio/masivo.php',{},function(){},{});
		});
	});
</script>

<div style="margin:15px 0px;">
	<span class="btn btn-sm btn-shop" id="masivo">
		<i class="glyphicon glyphicon-envelope">&nbsp;</i>Enviar correo a los shoppers
	</span>
</div>
<div id="datosImportantes"><?php include_once 'datosImportantes.php'; ?></div>
<div id="proyectosReps"><?php include_once 'proyectosReps.php'; ?></div>