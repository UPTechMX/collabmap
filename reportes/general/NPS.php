<?php
	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';

	
	if( !isset($_POST['reps']) ){
		$rps = $db->query("SELECT id FROM Repeticiones WHERE proyectosId = $_POST[proyectoId] AND elim IS NULL")->fetchAll(PDO::FETCH_ASSOC);
		$_POST['reps'] = array();
		foreach ($rps as $r) {
			$_POST['reps'][] = $r['id'];
		}
	}
	$nps=$db->query("SELECT nps.presentacion, p.identificador FROM PreguntasNPS nps
		LEFT JOIN Preguntas p ON p.id = nps.preguntasId
		WHERE nps.id=$_POST[npsId]")->fetch(PDO::FETCH_ASSOC);
	$cortes = $db->query("SELECT * FROM Cortes WHERE preguntasNPSId = $_POST[npsId] ORDER BY inf")->fetchAll(PDO::FETCH_ASSOC);

	// print2($_POST);
	// print2($nps);
	// print2($cortes);
	switch ($nps['presentacion']) {
		case 'pastel':
			include 'pastelNPS.php';
			break;
		case 'apilada':
			include 'apiladaNPS.php';
			break;
		case 'barras':
			include 'barrasNPS.php';
			break;
		case 'promedio':
			// include 'promedioNPS.php';
			break;
		
		default:
			# code...
			break;
	}

?>