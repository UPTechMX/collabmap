<?php
include_once '../../../lib/j/j.func.php';
checaAcceso(5); // checaAcceso analysis;

if(!is_numeric($_POST['pId']) && !is_numeric($_POST['consChk'])){
	exit();
}
// print2($_POST);
$preg = $db->query("SELECT p.*, t.siglas as tSiglas
	FROM Preguntas p 
	LEFT JOIN Tipos t ON t.id = p.tiposId
	WHERE p.id = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC)[0];

// print2($_POST);

$targetchecklsit = $db->query("SELECT te.*, f.code as fCode
	FROM TargetsChecklist te 
	LEFT JOIN Frequencies f ON f.id = te.frequency
	WHERE te.id = $_REQUEST[consChk]")->fetchAll(PDO::FETCH_ASSOC)[0];
// $numDim = $db->query("SELECT COUNT(*) FROM Dimensiones 
// 	WHERE elemId = $targetchecklsit[targetsId] AND type='structure' ")->fetchAll(PDO::FETCH_NUM)[0][0];


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

// echo "nivelMax: $nivelMax<br/>";
// echo "padre: $padre<br/>";
// echo "LJ: $LJ<br/>";
// echo "wDE: $wDE<br/>";


switch ($preg['tSiglas']) {
	case 'mult':
		
		$answers = $db->query("
			SELECT COUNT(*) as cuenta,rv.respuesta as respuestasId,r.respuesta as respuesta
			FROM RespuestasVisita rv
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND type = 'cons'
			LEFT JOIN UsersConsultationsChecklist te ON te.id = v.elemId
			LEFT JOIN ConsultationsChecklist tc ON tc.id = te.consultationsChecklistId
			LEFT JOIN Respuestas r ON r.id = rv.respuesta
			$LJ
			WHERE tc.id = $_POST[consChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'cons'  AND v.finalizada = 1
			GROUP BY rv.respuesta
		")->fetchALL(PDO::FETCH_ASSOC);

		include 'mult.php';

		break;
	case 'num':
		
		$answers = $db->query("
			SELECT COUNT(*) as cuenta,rv.respuesta
			FROM RespuestasVisita rv
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND type = 'cons'
			LEFT JOIN UsersConsultationsChecklist te ON te.id = v.elemId
			LEFT JOIN ConsultationsChecklist tc ON tc.id = te.consultationsChecklistId
			$LJ
			WHERE tc.id = $_POST[consChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'cons'  AND v.finalizada = 1
			GROUP BY rv.respuesta
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'num.php';

		break;
	case 'spatial':
	case 'op':
		$answers = $db->query("
			SELECT p.id, ST_AsGeoJSON(p.geometry) as geometry
			FROM RespuestasVisita rv
			LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'cons'
			LEFT JOIN UsersConsultationsChecklist te ON te.id = v.elemId
			LEFT JOIN ConsultationsChecklist tc ON tc.id = te.consultationsChecklistId
			$LJ
			WHERE tc.id = $_POST[consChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'cons'  AND v.finalizada = 1
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'spatial.php';

		break;
	case 'cm':
		$answers = $db->query("
			SELECT p.id, ST_AsGeoJSON(p.geometry) as geometry
			FROM RespuestasVisita rv
			LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'cons'
			LEFT JOIN UsersConsultationsChecklist te ON te.id = v.elemId
			LEFT JOIN ConsultationsChecklist tc ON tc.id = te.consultationsChecklistId
			$LJ
			WHERE tc.id = $_POST[consChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'cons'  AND v.finalizada = 1
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'spatial.php';

		break;
	
	case 'ab':
		include 'ab.php';

		break;
	
	default:
		# code...
		break;
}

?>