<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso analysis;


	include_once raiz().'lib/php/calcCache.php';
	// print2($_POST);

	$inq = $_POST['inequality'];
	if($inq != '<' && $inq != '<=' && $inq != '=' && $inq != '>=' && $inq != '>' && $_POST['type'] == 'num'){
		exit();
	}
	if((!is_numeric($_POST['value']) && $_POST['type'] == 'num') || !is_numeric($_POST['spatialQuestion']) || !is_numeric($_POST['pId']) ){
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
	$today = date('Y-m-d H:m:s');
	// print2($today);

	switch ($frequency) {
		case "daily":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -1 day'));
			break;
		case "weekly":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -1 week'));
			break;
		case "2weeks":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -2 week'));
			break;
		case "3weeks":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -3 week'));
			break;
		case "monthly":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -1 month'));
			break;
		case "2months":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -2 month'));
			break;
		case "3months":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -3 month'));
			break;
		case "4months":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -4 month'));
			break;
		case "6months":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -6 month'));
			break;
		case "yearly":
			$prevDate = date('Y-m-d H:m:s', strtotime($today . ' -1 year'));
			break;
		default:
			# code...
			break;
	}

	// echo "\n\n\n(v.finishDate > '$prevDate' && v.finishDate < '$today')\n\n\n";

	// print2($_POST);
	if($_POST['type'] == 'num'){
		$sql = "
			SELECT p.id as pId,p.id as prId, p.type, pts.*, rvDat.respuesta as rvDatValue, deName.nombre as deName, v.finishDate,
			rvDat.respuesta as respName
			FROM RespuestasVisita rvSpatial
			LEFT JOIN Problems p ON p.respuestasVisitaId = rvSpatial.id
			LEFT JOIN Points pts ON pts.problemsId = p.id
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
			GROUP BY te.id
			ORDER BY v.finishDate DESC
		";
		$answers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	}
	if($_POST['type'] == 'mult'){
		$sql = "
			SELECT p.id as pId,p.id as prId, p.type, pts.*, rvDat.respuesta as rvDatValue, deName.nombre as deName, v.finishDate,
			r.respuesta as respName
			FROM RespuestasVisita rvSpatial
			LEFT JOIN Problems p ON p.respuestasVisitaId = rvSpatial.id
			LEFT JOIN Points pts ON pts.problemsId = p.id
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
			GROUP BY te.id
			ORDER BY v.finishDate DESC
		";
		$answers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	}


	// echo "\n";
	// print2($answers);
	echo atj($answers);




?>