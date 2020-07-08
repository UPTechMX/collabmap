<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;

include_once '../../lib/php/checklist.php';

$app->group('/getGeneral', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->get('/privacy', function (Request $request, Response $response, array $args) {
  	global $db;
  	$resp = $db->query("SELECT * FROM General WHERE name = 'privacy'")->fetchAll(PDO::FETCH_ASSOC)[0];
  	$response->getBody()->write(atj($resp));
  	return $response;
  });


});
