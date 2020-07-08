<?php  

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;

include_once '../../lib/php/checklist.php';

$app->group('/signup', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->post('/', function (Request $request, Response $response, array $args) {
	global $db;

	$db->beginTransaction();
	$ok = true;
	
	$postArr  = $request->getParsedBody();
	$datos = json_decode($postArr['datos'],true);
	// print2($datos);

	$stm = $db->prepare("SELECT COUNT(*) FROM Users WHERE username = ?");


	$stm->execute(array($datos['username']));
	$cuenta = $stm -> fetch(PDO::FETCH_NUM)[0];
	// echo 
	if($cuenta > 0){
		return '{"ok":2}';
	}

	$p['tabla'] = 'Users';
	$p['datos'] = $datos;
	$p['datos']['pwd'] = encriptaUsr($p['datos']['pwd']);
	$p['datos']['confirmed'] = 1;
	$p['datos']['validated'] = 1;

	$r = json_decode(atj(inserta($p)),true);
	if($r['ok'] != 1){
		$ok = false;
		$err = 'Err: EINU454';
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