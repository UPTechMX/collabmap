<?php
include_once '../../lib/j/j.func.php';
checaAcceso(50); // checaAcceso analysis;

if(!is_numeric($_POST['pId']) && !is_numeric($_POST['trgtChk'])){
	exit();
}
// print2($_POST);
$preg = $db->query("SELECT p.*, t.siglas as tSiglas
	FROM Preguntas p 
	LEFT JOIN Tipos t ON t.id = p.tiposId
	WHERE p.id = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC)[0];

// print2($preg);
switch ($preg['tSiglas']) {
	case 'mult':
		
		$answers = $db->query("
			SELECT COUNT(*) as cuenta,rv.respuesta as respuestasId,r.respuesta as respuesta
			FROM RespuestasVisita rv
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND type = 'trgt'
			LEFT JOIN TargetsElems te ON te.id = v.elemId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
			LEFT JOIN Respuestas r ON r.id = rv.respuesta
			WHERE tc.id = $_POST[trgtChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'trgt'
			GROUP BY rv.respuesta
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'mult.php';

		break;
	case 'num':
		
		$answers = $db->query("
			SELECT COUNT(*) as cuenta,rv.respuesta
			FROM RespuestasVisita rv
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND type = 'trgt'
			LEFT JOIN TargetsElems te ON te.id = v.elemId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
			WHERE tc.id = $_POST[trgtChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'trgt'
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
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'trgt'
			LEFT JOIN TargetsElems te ON te.id = v.elemId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
			WHERE tc.id = $_POST[trgtChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'trgt'
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'spatial.php';

		break;
	case 'cm':
		$answers = $db->query("
			SELECT p.id, ST_AsGeoJSON(p.geometry) as geometry
			FROM RespuestasVisita rv
			LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
			LEFT JOIN Visitas v ON rv.visitasId = v.id AND v.type = 'trgt'
			LEFT JOIN TargetsElems te ON te.id = v.elemId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId
			WHERE tc.id = $_POST[trgtChk] AND rv.preguntasId = $_POST[pId] AND v.type = 'trgt'
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'spatial.php';

		break;
	
	default:
		# code...
		break;
}

?>