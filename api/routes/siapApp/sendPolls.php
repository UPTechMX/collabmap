<?php  

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;

include_once '../../lib/php/checklist.php';

$app->group('/sendPolls', function () use ($app) {
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

	$polls = json_decode($postArr['polls'],true);

	foreach ($polls as $p) {
		// print2($te);
		
		$rj = insertaPolls($p);
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
		$db->rollback();
		return '{"ok":0,"err":"'.$err.'"}';
	}

  });

});



?>