<?php
include_once '../../lib/j/j.func.php';
checaAcceso(50); // checaAcceso analysis;

$pregInfo = $db->query("SELECT * FROM Preguntas WHERE id = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($pregInfo);

$targetchecklsit = $db->query("SELECT te.*, f.code as fCode
	FROM TargetsChecklist te 
	LEFT JOIN Frequencies f ON f.id = te.frequency
	WHERE te.id = $_REQUEST[trgtChk]")->fetchAll(PDO::FETCH_ASSOC)[0];
$numDim = $db->query("SELECT COUNT(*) FROM Dimensiones 
	WHERE elemId = $targetchecklsit[targetsId] AND type='structure' ")->fetchAll(PDO::FETCH_NUM)[0][0];

$LJ = '';
$nivelMax = isset($_POST['nivelMax'])?$_POST['nivelMax']:0;
$padre = isset($_POST['padre'])?$_POST['padre']:0;
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
		$wDE = " de$i.padre = $padre";
	}
}

$sql = "
	SELECT rv.respuesta $fields
	FROM RespuestasVisita rv
	LEFT JOIN Visitas v ON rv.visitasId = v.id AND type = 'trgt'
	LEFT JOIN TargetsElems te ON te.id = v.elemId
	LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
	$LJ
	WHERE tc.id = $_POST[trgtChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'trgt' AND $wDE
	
";


// echo $sql."<br/>";
$answers = $db->query($sql)->fetchALL(PDO::FETCH_ASSOC);

header('Content-Type: text/html; charset=utf-8'); 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$pregInfo[identificador].csv");

$csv = "";
foreach ($answers as $a) {
	$csv .= '"'.$a['respuesta'].'"';
	$csv .= "\n";
	# code...
}



echo $csv;
// print2($answers);


?>