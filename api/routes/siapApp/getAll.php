<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;

include_once '../../lib/php/checklist.php';

$app->group('/getAll', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->get('/user/{usrId}', function (Request $request, Response $response, array $args) {
		global $db;
	$usrId = $args['usrId'];
	
	$token = getToken($request);
	$verif = tokenVerif($token,$usrId);
	
	if(!$verif){
		$response->getBody()->write('{"err":"Invalid token"}');
		return $response;
	}



	$UsersTargets = $db->query("SELECT * FROM UsersTargets WHERE usersId = $usrId")->fetchAll(PDO::FETCH_ASSOC);

	$Targets = array();
	$Dimensiones = array();
	$DimensionesElem = array();
	$TargetsChecklist = array();
	foreach ($UsersTargets as $ut) {
		$targetsUt = $db->query("SELECT * FROM Targets WHERE id = $ut[targetsId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		$Targets[] = $targetsUt;

		$dims = $db->query("SELECT * FROM Dimensiones WHERE type = 'structure' AND elemId = $ut[targetsId] ")->fetchAll(PDO::FETCH_ASSOC);

		foreach ($dims as $d) {
			$Dimensiones[] = $d;
			$dimsElem = $db->query("SELECT * FROM DimensionesElem WHERE dimensionesId = $d[id]")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($dimsElem as $de) {
				$DimensionesElem[] = $de;
			}
		}

		$trgtChk = $db->query("SELECT * FROM TargetsChecklist WHERE targetsId = $ut[targetsId]")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($trgtChk as $tc) {
			$TargetsChecklist[] = $tc;
		}

	}

	$TargetsElems = $db->query("SELECT * FROM TargetsElems WHERE usersId = $usrId")->fetchAll(PDO::FETCH_ASSOC);

	$Visitas = $db->query("SELECT v.* 
		FROM Visitas v
		LEFT JOIN TargetsElems te ON te.id = v.elemId
		WHERE te.usersId = $usrId AND (v.type = 'trgt')
	")->fetchAll(PDO::FETCH_ASSOC);

	$VisitasCons = $db->query("SELECT v.* 
		FROM Visitas v
		LEFT JOIN UsersConsultationsChecklist te ON te.id = v.elemId
		WHERE te.usersId = $usrId AND (v.type = 'cons')
	")->fetchAll(PDO::FETCH_ASSOC);

	foreach ($VisitasCons as $v) {
		$Visitas[] = $v;
	}

	// print2($Visitas);
	$RespuestasVisita = array();
	foreach ($Visitas as $v) {
		$respVis = $db->query("SELECT * FROM RespuestasVisita WHERE visitasId = $v[id]")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($respVis as $rv) {
			$RespuestasVisita[] = $rv;
		}
	}

	$Problems = array();
	foreach ($Visitas as $v) {
		$prbs = $db->query("SELECT p.id, p.type, p.name, p.description, 
			p.categoriesId, p.respuestasVisitaId, p.photo, ST_AsGeoJSON(p.geometry) as geometry
			FROM RespuestasVisita rv
			LEFT JOIN Problems p ON p.respuestasVisitaId = rv.id
			LEFT JOIN Preguntas pr ON pr.id = rv.preguntasId
			WHERE visitasId = $v[id] AND p.geometry IS NOT NULL
		")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($prbs as $p) {
			$Problems[] = $p;
		}
	}

	$Categories = $db->query("SELECT * FROM Categories")->fetchAll(PDO::FETCH_ASSOC);
	

	$Checklist = array();
	foreach ($Targets as $t) {
		// $db->query("SELECT * 
		// 	FROM TargetsChecklist tc
		// 	LEFT JOIN ChecklistEst ce ON ce.checklistId = tc.checklistId		
		// ")->fetchAll(PDO::FETCH_ASSOC)	;

		$chks = $db-> query("SELECT c.*, ce.estructura as est
			FROM Checklist c
			LEFT JOIN  TargetsChecklist tc ON tc.checklistId = c.id
			LEFT JOIN ChecklistEst ce ON ce.checklistId = c.id
			WHERE tc.targetsId = $t[id]
		")->fetchAll(PDO::FETCH_ASSOC);

		foreach ($chks as $chk) {
			if(empty($chk['est'])){
				$est = estructuraEXT($chk['id']);
				// print2($est);
				// $prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $c[id], estructura = ?");
				$prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $chk[id], estructura = ?");
				$estj = atj($est);
				$prep -> execute(array($estj));
				$chk['est'] = $estj;
			}

			$Checklist[$chk['id']] = $chk;	
		}
	}


	$Frequencies = $db->query("SELECT * FROM Frequencies")->fetchAll(PDO::FETCH_ASSOC);

	$now = date('Y-m-d');
	$Projects = $db->query("SELECT * FROM Projects")->fetchAll(PDO::FETCH_ASSOC);
	$Audiences = array();
	foreach ($Projects as $p) {


		$auds = $db->query("SELECT * FROM Audiences WHERE projectsId = $p[id] ")->fetchAll(PDO::FETCH_ASSOC);
		$dims = $db->query("SELECT * FROM Dimensiones WHERE type = 'audiences' AND elemId = $p[id] ")->fetchAll(PDO::FETCH_ASSOC);

		foreach ($dims as $d) {
			$Dimensiones[] = $d;
			$dimsElem = $db->query("SELECT * FROM DimensionesElem WHERE dimensionesId = $d[id]")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($dimsElem as $de) {
				$DimensionesElem[] = $de;
			}
		}

		foreach ($auds as $a) {
			$Audiences[] = $a;
		}

	}





	$Consultations = $db->query("SELECT * FROM Consultations WHERE finishDate > '$now' ")->fetchAll(PDO::FETCH_ASSOC);

	$ConsultationsChecklist = $db->query("SELECT t.* 
		FROM ConsultationsChecklist t
		LEFT JOIN Consultations c ON c.id = t.consultationsId 
		WHERE c.finishDate > '$now'
		")->fetchAll(PDO::FETCH_ASSOC);

	foreach ($ConsultationsChecklist as $t) {
		// $db->query("SELECT * 
		// 	FROM TargetsChecklist tc
		// 	LEFT JOIN ChecklistEst ce ON ce.checklistId = tc.checklistId		
		// ")->fetchAll(PDO::FETCH_ASSOC)	;

		$chks = $db-> query("SELECT c.*, ce.estructura as est
			FROM Checklist c
			LEFT JOIN ChecklistEst ce ON ce.checklistId = c.id
			WHERE c.id = $t[checklistId]
		")->fetchAll(PDO::FETCH_ASSOC);

		foreach ($chks as $chk) {
			if(empty($chk['est'])){
				$est = estructuraEXT($chk['id']);
				// print2($est);
				// $prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $c[id], estructura = ?");
				$prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $chk[id], estructura = ?");
				$estj = atj($est);
				$prep -> execute(array($estj));
				$chk['est'] = $estj;
			}

			$Checklist[$chk['id']] = $chk;
		}
	}

	$Studyarea = array();
	foreach ($Checklist as $c) {
		# code...
		$sas = $db->query("SELECT sa.id, ST_AsGeoJSON(sa.geometry) as geometry, p.id as preguntasId, sa.type
			FROM Studyarea sa 
			LEFT JOIN Preguntas p ON sa.preguntasId = p.id
			LEFT JOIN Areas a ON a.id = p.areasId
			LEFT JOIN Bloques b ON b.id = a.bloquesId
			LEFT JOIN Checklist c ON c.id = b.checklistId
			WHERE c.id = $c[id] AND sa.id IS NOT NULL
		")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($sas as $sa) {
			$Studyarea[] = $sa;
		}
	}



	$UsersConsultationsChecklist = $db->query("SELECT * 
		FROM UsersConsultationsChecklist
		WHERE usersId = $usrId
	")->fetchAll(PDO::FETCH_ASSOC);

	$UsersAudiences = $db->query("SELECT * 
		FROM UsersAudiences
		WHERE usersId = $usrId
	")->fetchAll(PDO::FETCH_ASSOC);

	$ConsultationsAudiencesCache = $db->query("SELECT t.* 
		FROM ConsultationsAudiencesCache t
		LEFT JOIN Consultations c ON c.id = t.consultationsId 
		WHERE c.finishDate > '$now'
		")->fetchAll(PDO::FETCH_ASSOC);


	$General = $db->query("SELECT * FROM General")->fetchAll(PDO::FETCH_ASSOC);
	


	$Checklist = array_values($Checklist);
	// print2($Checklist);

	// print2($Checklist);
	$resp['UsersTargets'] = $UsersTargets;
	$resp['Targets'] = $Targets;
	$resp['Checklist'] = $Checklist;
	$resp['Dimensiones'] = $Dimensiones;
	$resp['DimensionesElem'] = $DimensionesElem;
	$resp['TargetsElems'] = $TargetsElems;
	$resp['TargetsChecklist'] = $TargetsChecklist;
	$resp['Frequencies'] = $Frequencies;
	$resp['Visitas'] = $Visitas;
	$resp['RespuestasVisita'] = $RespuestasVisita;
	$resp['Problems'] = $Problems;
	$resp['Categories'] = $Categories;
	$resp['Studyarea'] = $Studyarea;
	$resp['General'] = $General;

	$resp['Projects'] = $Projects;
	$resp['Consultations'] = $Consultations;
	$resp['Audiences'] = $Audiences;
	$resp['ConsultationsChecklist'] = $ConsultationsChecklist;
	$resp['UsersConsultationsChecklist'] = $UsersConsultationsChecklist;
	$resp['ConsultationsAudiencesCache'] = $ConsultationsAudiencesCache;
	$resp['UsersAudiences'] = $UsersAudiences;

	$response->getBody()->write(atj($resp));
	return $response;
  });


});
