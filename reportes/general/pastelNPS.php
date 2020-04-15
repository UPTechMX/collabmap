<?php
$tiempoIni = microtime(true); 


$serieP = array();

$cuenta = cuentaNPS($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$cortes,$nps['identificador'],$_POST['mId']);
// print2($cuenta);

foreach ($cortes as $c) {
	// print2($cuenta);
	$tmpS['color'] = $c['color'];
	$tmpS['name'] = $c['nombre'];
	$tmpS['y'] = $cuenta[$c['nombre']][0]['cuenta'];
	$serieP[] = $tmpS;
}
// print2($serieP);



$tiempoFin = microtime(true);
$tiempo = ($tiempoFin - $tiempoIni);

?>

<script type="text/javascript">
	var tiempo = parseFloat(<?php echo $tiempo; ?>);

	$(document).ready(function() {
		var serieP = <?php echo atj($serieP); ?>;
		parseaObjeto(serieP);
		// console.log('serie Pastel');
		// console.log(serieP);
		var npsId = <?php echo $_POST['npsId']; ?>;
		pay($('#grafNPS_'+npsId),serieP,'nom1');

	});
</script>

<div id="grafNPS_<?php echo $_POST[npsId];?>" style="height: 300px;"></div>