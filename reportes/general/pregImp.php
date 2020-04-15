<?php
	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';

	$tiempoIni = microtime(true); 
	// print2($_POST);
	// exit;
	if( !isset($_POST['reps']) ){
		$rps = $db->query("SELECT id FROM Repeticiones WHERE proyectosId = $_POST[proyectoId] AND elim IS NULL")->fetchAll(PDO::FETCH_ASSOC);
		$_POST['reps'] = array();
		foreach ($rps as $r) {
			$_POST['reps'][] = $r['id'];
		}
	}

	$pregsImp=$db->query("SELECT p.identificador FROM PreguntasImp pi
		LEFT JOIN Preguntas p ON p.id = pi.preguntasId
		WHERE pi.proyectosId=$_POST[proyectoId]
		ORDER BY pi.orden")->fetchAll(PDO::FETCH_ASSOC);
	
	// print2($_POST);
	$serieA = array();
	$cats = array();

	// print2($pregsImp);

	$results = pregImp($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$pregsImp,$_POST['mId']);

	foreach ($results as $k => $r) {
		if($r['y']<50){
			$results[$k]['color'] = '#f00';
		}else{
			$results[$k]['color'] = 'green';
		}

	}
	// print2($results);
	// print2($serieA);
	$tiempoFin = microtime(true);
	$tiempo = ($tiempoFin - $tiempoIni);
	// echo $tiempo;
?>
<script type="text/javascript">
	var tiempo = parseFloat(<?php echo $tiempo; ?>);	
</script>
<?php if (!empty($results)){ ?>
	<script type="text/javascript">
		// console.log(parseFloat(<?php echo $tiempo; ?>));
		$(document).ready(function() {
			var serie = <?php echo atj($results);?>;
			parseaObjeto(serie);
			// console.log(serie);
			// $('#grafImp').css({height: parseInt(rows)*50});

			barras('#grafImp',serie,false);

		});
	</script>
		
	<div class="nuevo">Información relevante</div>
	<div id="grafImp" style="height: 300px;"></div>
<?php } ?>