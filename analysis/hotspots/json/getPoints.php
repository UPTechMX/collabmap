<?php
ini_set('memory_limit', '2000M');
include_once '../../../lib/j/j.func.php';

checaAcceso(5); // checaAcceso analysis;

$spatialQ = $db->query("SELECT p.id, t.siglas 
	FROM Preguntas p 
	LEFT JOIN Tipos t ON t.id = p.tiposId
	WHERE p.id = $_POST[spatialQ]")->fetchAll(PDO::FETCH_ASSOC)[0];


switch ($spatialQ['siglas']) {
	case 'op':
		$spatialFnc = 'ST_Contains';
		$geometry = "ST_AsGeoJSON(p.geometry) as geometry";
		break;
	case 'spatial':
		$spatialFnc = 'ST_Intersects';
		$geometry = "ST_AsGeoJSON(p.geometry) as geometry";
		break;	
	default:
		break;
}
// print2($_POST);

$numDim = $db->query("SELECT COUNT(*) FROM Dimensiones 
	WHERE elemId = $_POST[trgtId] AND type='structure' ")->fetchAll(PDO::FETCH_NUM)[0][0];


$LJStructure = '';
$nivelMax = isset($_POST['nivelMax'])?$_POST['nivelMax']:0;
$padre = isset($_POST['padre'])?$_POST['padre']:0;
for ($i=$nivelMax; $i <$numDim ; $i++) { 
	if($i == $nivelMax){
		$LJStructure .= " LEFT JOIN DimensionesElem de$i ON te.dimensionesElemId = de$i.id";
	}else{
		$LJStructure .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
	}
	if($i == $numDim - 2){
	}
	if($i == $numDim - 1){
		$fieldsDims = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
		$wDE = "AND de$i.padre = $padre";
	}
}

// echo "numDim: $numDim\n";
// echo "nivelMax: $nivelMax\n";

$LJQuestions = '';
$whereQuestions = '';
$fieldsQ = '';
$whereQ = '';
foreach ($_POST['questionsChk'] as $k => $q) {
	if(empty($q['questionId'])  || $q['questionId'] == 'ansNum' ){
		continue;
	}

	// echo "$q[questionId]\n\n";

	switch ($q['qType']) {
		case 'mult':
			// $LJQuestions .= " LEFT JOIN TargetsChecklist tc$k ON tc$k.targetsId = te.targetsId ";
			$LJQuestions .= " LEFT JOIN Visitas v$k ON v$k.type = 'trgt' AND v$k.elemId = v.elemId AND v$k.checklistId = :chkId$k ";
			$LJQuestions .= " LEFT JOIN RespuestasVisita rv$k ON rv$k.visitasId = v$k.id AND rv$k.preguntasId = :pregId$k ";
			$LJQuestions .= " LEFT JOIN Respuestas r$k ON r$k.id = rv$k.respuesta "; 
			$fieldsQ .= ", v$k.id as vId$k ";
			$fieldsQ .= ", rv$k.respuesta as respV$k, r$k.respuesta as respN$k ";
			$arr["chkId$k"] = $q['chkId'];
			$arr["pregId$k"] = $q['questionId'];
			break;

		case 'num':
			$LJQuestions .= " LEFT JOIN Visitas v$k ON v$k.type = 'trgt' AND v$k.elemId = v.elemId AND v$k.checklistId = :chkId$k ";
			$LJQuestions .= " LEFT JOIN RespuestasVisita rv$k ON rv$k.visitasId = v$k.id AND rv$k.preguntasId = :pregId$k ";

			$fieldsQ .= ", v$k.id as vId$k ";
			$fieldsQ .= ", rv$k.respuesta as respV$k";
			$arr["chkId$k"] = $q['chkId'];
			$arr["pregId$k"] = $q['questionId'];
			// $LJQuestions .= " LEFT JOIN TargetsChecklist tc$k ON tc$k.targetsId = te.targetsId ";
			break;
		
		
		default:
			# code...
			break;
	}
	// print2($q);
}

if($_POST['kmlId'] == -1){
	$fields = "te.id as idGroup";
}else{
	$fields = "	kg.id as idGroup, kg.id";
	$whereGeom = "AND $spatialFnc(kg.geometry,p.geometry)";
}


$sql = "
	SELECT $fields, $geometry, p.id, te.id as teId, v.id as vId $fieldsQ
	FROM RespuestasVisita rv
	LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
	LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'trgt'
	LEFT JOIN TargetsElems te ON te.id = v.elemId
	LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
	LEFT JOIN KMLGeometries kg ON $spatialFnc(kg.geometry,p.geometry) AND kg.KMLId = :kmlId
	$LJStructure $LJQuestions
	WHERE (tc.id = :tcId AND rv.preguntasId = :spatialQ AND v.type = 'trgt' $wDE AND v.finalizada = 1)
	-- AND $spatialFnc(kg.geometry,p.geometry)

	-- GROUP BY te.id

";

$arr['tcId'] = $_POST['tcIdspatial'];
$arr['spatialQ'] = $_POST['spatialQ'];
$arr['kmlId'] = $_POST['kmlId'];

// echo "\nSQL: $sql \n";

$prep = $db->prepare($sql);

$prep->execute($arr);

$answers = $prep->fetchALL(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);



// print2($answers);


echo atj($answers);


