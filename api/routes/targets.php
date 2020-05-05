<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;



$app->group('/targets', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->post('/user/{usrId}', function (Request $request, Response $response, array $args) {
		global $db;
	$usrId = $args['usrId'];
	
	$token = getToken($request);

	// echo "\n\n$token\n\n";

	$verif = tokenVerif($token,$usrId);

	if($verif){
		$ut = $db->query("SELECT * FROM UsersTargets WHERE usersId = $usrId")->fetchAll(PDO::FETCH_ASSOC);
	}else{
		$ut['err'] = 'Invalid token';
	}

	$response->getBody()->write(atj($ut));
	return $response;
  });


});
