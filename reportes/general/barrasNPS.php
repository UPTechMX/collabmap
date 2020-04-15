<?php
$tiempoIni = microtime(true); 


$serieB = array();
$cats = array();

$cuenta = cuentaNPS($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$cortes,$nps['identificador'],$_POST['mId']);
// print2($cuenta);

foreach ($cortes as $c) {
	$tmpS['color'] = $c['color'];
	$tmpS['name'] = $c['nombre'];
	$tmpS['y'] = $cuenta[$c['nombre']][0]['cuenta'];
	$serieB[] = $tmpS;
	$cats[] = $c['nombre'];
}
// print2($serieB);
$tiempoFin = microtime(true);
$tiempo = ($tiempoFin - $tiempoIni);

?>

<script type="text/javascript">
	var tiempo = parseFloat(<?php echo $tiempo; ?>);
	$(document).ready(function() {
		var serieB = <?php echo atj($serieB); ?>;
		parseaObjeto(serieB);
		// console.log('serie Barras');
		// console.log(serieB);
		var cats = <?php echo atj($cats); ?>;
		var npsId = <?php echo $_POST['npsId']; ?>;
		barras($('#grafNPS_'+npsId),serieB,true);

	});
</script>

<div id="grafNPS_<?php echo $_POST[npsId];?>" style="height: 300px;"></div>