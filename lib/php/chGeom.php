<?php

	include_once '../j/j.func.php';
	exit();

	$sas = $db->query("SELECT * FROM Studyarea")->fetchAll(PDO::FETCH_ASSOC);

	foreach ($sas as $s) {
		$pts = $db->query("SELECT lat,lng FROM StudyareaPoints WHERE studyareaId = $s[id]")->fetchAll(PDO::FETCH_ASSOC);
		// print2($pts);
		
	
		$p['tabla'] = 'Studyarea';
		$p['where'] = "id = $s[id]";
		$p['datos']['type'] ='polygon';
		$p['geo']['latlngs'] = atj([$pts]);
		$p['geo']['type'] = 'polygon';
		$p['geo']['field'] = 'geometry';

		echo upd($p)."<br/>";

	}

	$prb = $db->query("SELECT * FROM Problems")->fetchAll(PDO::FETCH_ASSOC);

	foreach ($prb as $e) {
		$pts = $db->query("SELECT lat,lng FROM Points WHERE problemsId = $e[id]")->fetchAll(PDO::FETCH_ASSOC);
		// print2($pts);
		
	
		$pp['tabla'] = 'Problems';
		$pp['where'] = "id = $e[id]";
		$pp['datos']['type'] = $e['type'];
		$pp['geo']['type'] = $e['type'];
		$pp['geo']['field'] = 'geometry';

		switch ($e['type']) {
			case 'marker':
				$pp['geo']['latlngs'] = atj($pts[0]);	
				break;
			
			case 'polygon':
				$pp['geo']['latlngs'] = atj([$pts]);	
				break;
			case 'polyline':
				$pp['geo']['latlngs'] = atj($pts);	
				break;
			
			default:
				# code...
				break;
		}
		// print2($pp);
		// $p['geo']['latlngs'] = atj([$pts]);


		echo upd($pp)."<br/>";

	}





?>