<?php  

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;

include_once '../../lib/php/checklist.php';

$app->group('/sendVisitas', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->post('/user/{usrId}', function (Request $request, Response $response, array $args) {
	global $db;

	$db->beginTransaction();
	$ok = true;

	$usrId = $args['usrId'];	
	$token = getToken($request);
	$verif = tokenVerif($token,$usrId);
	
	if(!$verif){
		$response->getBody()->write('{"err":"Invalid token"}');
		return $response;
	}

	$postArr  = $request->getParsedBody();

	$visitas = json_decode($postArr['visitas'],true);

	foreach ($visitas as $v) {
		// print2($te);
		$vis = $v['visita'];
		$rj = insertaVisitasA($vis['elemId'],$v,true);
		$r = json_decode($rj,true);
		if($r['ok'] != 1){
			$ok = false;
			$err = $r['err'];
		}
	}

	if($ok){
		$db->commit();
		return '{"ok":1}';
	}else{
		$db->commit();
		return '{"ok":0,"err":"'.$err.'"}';
	}

  });

});



?>