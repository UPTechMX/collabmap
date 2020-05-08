<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAcceso(5); // checaAcceso analysis;


	include_once raiz().'lib/php/calcCache.php';
	// print2($_POST);

	$inq = $_POST['inequality'];
	if($inq != '<' && $inq != '<=' && $inq != '=' && $inq != '>=' && $inq != '>' && $_POST['type'] == 'num'){
		exit();
	}
	if((!is_numeric($_POST['value']) && $_POST['type'] == 'num') 
		|| !is_numeric($_POST['spatialQuestion']) 
		|| !is_numeric($_POST['pId']) 
		|| !is_numeric($_POST['numAnt']) 
	){
		exit();
	}

	$targetchecklsit = $db->query("SELECT te.*, f.code as fCode
		FROM TargetsChecklist te 
		LEFT JOIN Frequencies f ON f.id = te.frequency
		WHERE te.id = $_POST[trgtChk]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$numDim = $db->query("SELECT COUNT(*) FROM Dimensiones 
		WHERE elemId = $targetchecklsit[targetsId] AND type='structure' ")->fetchAll(PDO::FETCH_NUM)[0][0];


	$LJ = '';
	$nivelMax = $_POST['nivelMax'];
	for ($i=$nivelMax; $i <$numDim ; $i++) { 
		if($i == $nivelMax){
			$LJ .= " LEFT JOIN DimensionesElem de$i ON te.dimensionesElemId = de$i.id";
		}else{
			$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
		}
		if($i == $numDim - 2){
		}
		if($i == $numDim - 1){
			$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
			$wDE = " de$i.padre = $_POST[padre]";
		}
	}

	$frequency = $targetchecklsit['fCode'];
	$today = date('Y-m-d 23:59:59');
	// print2($today);

	switch ($frequency) {
		case "daily":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' day'));
			break;
		case "weekly":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' week'));
			break;
		case "2weeks":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' week'));
			break;
		case "3weeks":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' week'));
			break;
		case "monthly":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' month'));
			break;
		case "2months":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' month'));
			break;
		case "3months":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' month'));
			break;
		case "4months":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' month'));
			break;
		case "6months":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' month'));
			break;
		case "yearly":
			$prevDate = date('Y-m-d 00:00:00', strtotime($today . ' -'.$_POST['numAnt'].' year'));
			break;
		default:
			# code...
			break;
	}

	$todayRep = date('Y-m-d');
	$dates = array();
	$dates[] = $todayRep;
	for ($i=0; $i < $_POST['numAnt']; $i++) { 
		switch ($frequency) {
			case "daily":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -1 day'));
				$dates[] = $todayRep;
				break;
			case "weekly":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -1 week'));
				$dates[] = $todayRep;
				break;
			case "2weeks":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -2 week'));
				$dates[] = $todayRep;
				break;
			case "3weeks":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -3 week'));
				$dates[] = $todayRep;
				break;
			case "monthly":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -1 month'));
				$dates[] = $todayRep;
				break;
			case "2months":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -2 month'));
				$dates[] = $todayRep;
				break;
			case "3months":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -3 month'));
				$dates[] = $todayRep;
				break;
			case "4months":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -4 month'));
				$dates[] = $todayRep;
				break;
			case "6months":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -6 month'));
				$dates[] = $todayRep;
				break;
			case "yearly":
				$todayRep = date('Y-m-d', strtotime($todayRep . ' -1 year'));
				$dates[] = $todayRep;
				break;
			default:
				# code...
				break;
		}

	}
	// echo "\n\n\n(v.finishDate > '$prevDate' && v.finishDate < '$today')\n\n\n";

	// print2($_POST);
	if($_POST['type'] == 'num'){

		$sql = "
			SELECT rvDat.respuesta as rvDatValue, deName.nombre as deName, v.finishDate,
			rvDat.respuesta as respName
			FROM RespuestasVisita rvSpatial
			LEFT JOIN Visitas v ON rvSpatial.visitasId = v.id AND v.type = 'trgt'
			LEFT JOIN TargetsElems te ON te.id = v.elemId
			$LJ
			LEFT JOIN DimensionesElem deName ON deName.id = te.dimensionesElemId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
			LEFT JOIN RespuestasVisita rvDat ON rvDat.visitasId = rvSpatial.visitasId
			WHERE tc.id = $_POST[trgtChk] AND $wDE AND rvSpatial.preguntasId = $_POST[spatialQuestion] AND rvDat.preguntasId = $_POST[pId]
				AND (rvDat.respuesta $_POST[inequality] $_POST[value]) 
				AND (v.finishDate > '$prevDate' && v.finishDate < '$today')
				AND v.type ='trgt'
			ORDER BY v.finishDate DESC
		";
		$answers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
	if($_POST['type'] == 'mult'){
		$sql = "
			SELECT rvDat.respuesta as rvDatValue, deName.nombre as deName, v.finishDate,
			r.respuesta as respName
			FROM RespuestasVisita rvSpatial
			LEFT JOIN Visitas v ON rvSpatial.visitasId = v.id AND v.type = 'trgt'
			LEFT JOIN TargetsElems te ON te.id = v.elemId
			$LJ
			LEFT JOIN DimensionesElem deName ON deName.id = te.dimensionesElemId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
			LEFT JOIN RespuestasVisita rvDat ON rvDat.visitasId = rvSpatial.visitasId
			LEFT JOIN Respuestas r ON r.id = rvDat.respuesta
			WHERE tc.id = $_POST[trgtChk] AND $wDE AND rvSpatial.preguntasId = $_POST[spatialQuestion] AND rvDat.preguntasId = $_POST[pId]
				AND (rvDat.respuesta = $_POST[ans]) 
				AND (v.finishDate > '$prevDate' && v.finishDate < '$today')
				AND v.type ='trgt'
			ORDER BY v.finishDate DESC
		";
		$answers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}


	// echo "\n";
	// print2($answers);
	$resp = array();
	$resp['answers'] = $answers;
	$resp['dates'] = $dates;


	// print2($resp);
	echo atj($resp);




?>