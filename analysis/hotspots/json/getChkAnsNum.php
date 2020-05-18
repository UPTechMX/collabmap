<?php
ini_set('memory_limit', '2000M');
include_once '../../../lib/j/j.func.php';

checaAcceso(5); // checaAcceso analysis;

// print2($_POST);

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
	if($q['questionId'] != 'ansNum'){
		continue;
	}

	$LJQuestions .= " LEFT JOIN Visitas v$k ON v$k.type = 'trgt' AND v$k.elemId = v.elemId AND v$k.checklistId = :chkId$k ";

	$arr["chkId$k"] = $q['chkId'];
	$arr["vchkId$k"] = $q['chkId'];

	if($_POST['kmlId'] == -1){
		$fields = "te.id as idGroup, p.id, COUNT(*) as ansNum,
		te.id as teId, v.id as vId";
	}else{
		$fields = "kg.identifier as idGroup, p.id, kg.identifier, COUNT(*) as ansNum,
		te.id as teId, v.id as vId";
		$whereGeom = "AND ST_Contains(kg.geometry,p.geometry)";
	}

	$sql = "
		SELECT $fields
		FROM RespuestasVisita rv
		LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
		LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'trgt'
		LEFT JOIN TargetsElems te ON te.id = v.elemId
		LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
		LEFT JOIN KMLGeometries kg ON ST_Contains(kg.geometry,p.geometry) AND kg.KMLId = :kmlId
		$LJStructure $LJQuestions
		WHERE (tc.id = :tcId AND rv.preguntasId = :spatialQ AND v.type = 'trgt' $wDE AND v.finalizada = 1)
		$whereGeom AND v$k.checklistId = :vchkId$k
		GROUP BY te.id
	";



	// echo "\n$sql\n";
	$arr['tcId'] = $_POST['tcIdspatial'];
	$arr['spatialQ'] = $_POST['spatialQ'];
	$arr['kmlId'] = $_POST['kmlId'];

	$prep = $db->prepare($sql);

	$prep->execute($arr);

	$answers = $prep->fetchALL(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$numPregs[$q['chkId']] = $answers;

}


echo atj($numPregs);
