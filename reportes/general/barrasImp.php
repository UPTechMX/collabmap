<?php
$tiempoIni = microtime(true); 

// print2($_POST);
$serieA = array();
$cats = array();


$cuenta = pregImp($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$nps['identificador'],$_POST['mId']);

// print2($serieA);
$tiempoFin = microtime(true);
$tiempo = ($tiempoFin - $tiempoIni);
// echo $tiempo;
?>

<script type="text/javascript">
	// console.log(parseFloat(<?php echo $tiempo; ?>));
	var tiempo = parseFloat(<?php echo $tiempo; ?>);
	$(document).ready(function() {
		var serieA = <?php echo atj($serieA); ?>;
		parseaObjeto(serieA);
		// console.log(serieA);
		var cats = <?php echo atj($cats); ?>;
		var npsId = <?php echo $_POST['npsId']; ?>;
		drilldown = [''];
		apiladas($('#grafNPS_'+npsId),serieA,drilldown);
	});
</script>

<div id="grafNPS_<?php echo $_POST[npsId];?>" style="height: 300px;"></div>