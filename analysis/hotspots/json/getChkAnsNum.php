<?php
ini_set('memory_limit', '2000M');
include_once '../../../lib/j/j.func.php';

checaAcceso(5); // checaAcceso analysis;

// print2($_POST);

$spatialQ = $db->query("SELECT p.id, t.siglas 
	FROM Preguntas p 
	LEFT JOIN Tipos t ON t.id = p.tiposId
	WHERE p.id = $_POST[spatialQ]")->fetchAll(PDO::FETCH_ASSOC)[0];


switch ($spatialQ['siglas']) {
	case 'op':
		$spatialFnc = 'ST_Contains';
		break;
	case 'spatial':
		$spatialFnc = 'ST_Intersects';
		break;	
	default:
		break;
}


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
		$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
		$wDE = "AND de$i.padre = $padre";
	}
}

$LJQuestions = '';
$whereQuestions = '';
$fieldsQ = '';
$whereQ = '';

$numPregs = array();
foreach ($_POST['questionsChk'] as $k => $q) {

	$inequality = '=';
	switch ($q['inequality']) {
		case '<':
			$inequality = "<";
			break;
		case '<=':
			$inequality = "<=";
			break;
		case '=':
			$inequality = "=";
			break;
		case '>=':
			$inequality = ">=";
			break;
		case '>':
			$inequality = ">";
			break;
		default:
			$inequality = "=";
			break;
	}

	// $whereQ .= " AND s$v.ansNum $inequality $ "



	if($q['questionId'] != 'ansNum'){
		continue;
	}

	// $LJQuestions .= " LEFT JOIN Visitas v$k ON v$k.type = 'trgt' AND v$k.elemId = v.elemId AND v$k.checklistId = :chkId$k ";
	$LJQuestions .= " LEFT JOIN (SELECT v$k.elemId, COUNT(*) as ansNum
		FROM Visitas v$k WHERE v$k.type = 'trgt' AND v$k.checklistId = :chkId$k GROUP BY v$k.elemId ) s$v ON s$v.elemId = v.elemId";

	$arr["chkId$k"] = $q['chkId'];
	// $arr["vchkId$k"] = $q['chkId'];

	if($_POST['kmlId'] == -1){
		$fields = "te.id as idGroup";
	}else{
		$fields = " kg.id idGroup, kg.id";
		$whereGeom = "AND $spatialFnc(kg.geometry,p.geometry)";
	}

	$sql = "
		SELECT $fields, p.id, s$v.ansNum as ansNum, te.id as teId, v.id as vId, kg.identifier
		FROM RespuestasVisita rv
		LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
		LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'trgt'
		LEFT JOIN TargetsElems te ON te.id = v.elemId
		LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId AND tc.checklistId = v.checklistId
		LEFT JOIN KMLGeometries kg ON $spatialFnc(kg.geometry,p.geometry) AND kg.KMLId = :kmlId
		$LJStructure $LJQuestions
		WHERE (tc.id = :tcId AND rv.preguntasId = :spatialQ AND v.type = 'trgt' $wDE AND v.finalizada = 1)
		$whereGeom 
		GROUP BY te.id
	";



	// echo "\nSQL: $sql\n";
	$arr['tcId'] = $_POST['tcIdspatial'];
	$arr['spatialQ'] = $_POST['spatialQ'];
	$arr['kmlId'] = $_POST['kmlId'];

	$prep = $db->prepare($sql);

	$prep->execute($arr);

	$answers = $prep->fetchALL(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$numPregs[$q['chkId']] = $answers;

}


echo atj($numPregs);
