<?php
include_once '../../lib/j/j.func.php';
checaAcceso(50); // checaAcceso analysis;

if(!is_numeric($_POST['pId']) && !is_numeric($_POST['pcId'])){
	exit();
}
// print2($_POST);
$preg = $db->query("SELECT p.*, t.siglas as tSiglas
	FROM Preguntas p 
	LEFT JOIN Tipos t ON t.id = p.tiposId
	WHERE p.id = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC)[0];

// print2($_POST);



switch ($preg['tSiglas']) {
	case 'mult':
		
		$answers = $db->query("
			SELECT COUNT(*) as cuenta,rv.respuesta as respuestasId,r.respuesta as respuesta
			FROM RespuestasVisita rv
			LEFT JOIN Visitas v ON rv.visitasId = v.id 
			LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
			LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
			LEFT JOIN Respuestas r ON r.id = rv.respuesta
			WHERE pc.id = $_POST[pcId] AND rv.preguntasId = $_POST[pId] AND v.type = 'pubC' AND v.finalizada = 1
			GROUP BY rv.respuesta
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'mult.php';

		break;
	case 'num':
		
		$answers = $db->query("
			SELECT COUNT(*) as cuenta,rv.respuesta
			FROM RespuestasVisita rv
			LEFT JOIN Visitas v ON rv.visitasId = v.id 
			LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
			LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
			WHERE pc.id = $_POST[pcId] AND rv.preguntasId = $_POST[pId] AND v.type = 'pubC' AND v.finalizada = 1
			GROUP BY rv.respuesta
		")->fetchALL(PDO::FETCH_ASSOC);
		// print2($answers);
		// print2($_POST);
		include 'num.php';

		break;
	case 'spatial':
	case 'op':
		$answers = $db->query("
			SELECT p.id, ST_AsGeoJSON(p.geometry) as geometry
			FROM RespuestasVisita rv
			LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
			LEFT JOIN Visitas v ON rv.visitasId = v.id 
			LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
			LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
			WHERE pc.id = $_POST[pcId] AND rv.preguntasId = $_POST[pId] AND v.type = 'pubC' AND v.finalizada = 1
		")->fetchALL(PDO::FETCH_ASSOC);
		include 'spatial.php';

		break;
	case 'cm':
		$answers = $db->query("
			SELECT p.id, ST_AsGeoJSON(p.geometry) as geometry
			FROM RespuestasVisita rv
			LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
			LEFT JOIN Visitas v ON rv.visitasId = v.id 
			LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
			LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
			WHERE pc.id = $_POST[pcId] AND rv.preguntasId = $_POST[pId] AND v.type = 'pubC' AND v.finalizada = 1
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