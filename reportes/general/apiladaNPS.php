<?php
$tiempoIni = microtime(true); 

// print2($_POST);
$serieA = array();
$cats = array();

// echo "$nps[identificador] ///  $_POST[npsId]";
$cuenta = cuentaNPS($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$cortes,$nps['identificador'],$_POST['mId']);
// print2($cuenta);
foreach ($cortes as $c) {
	// print2($c);
	// print2($cuenta);
	$tmpS['data'] = array();
	$tmpS['color'] = $c['color'];
	$tmpS['name'] = $c['nombre'];
	$tmpS['data'][] = $cuenta[$c['nombre']][0]['cuenta'];
	$serieA[] = $tmpS;
	$cats[] = $c['nombre'];
}
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
		var identificador = '<?php echo $nps['identificador']; ?>';
		var cats = <?php echo atj($cats); ?>;
		var npsId = <?php echo $_POST['npsId']; ?>;
		// console.log(npsId,identificador,serieA);
		drilldown = [''];
		apiladas($('#grafNPS_'+npsId),serieA,drilldown);
	});
</script>

<div id="grafNPS_<?php echo $_POST[npsId];?>" style="height: 300px"></div>