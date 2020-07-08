<?php

include '../../lib/j/j.func.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;



$app->group('/v1', function () use ($app) {
  // Version group
	// $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
	//     $name = $args['name'];
	//     $response->getBody()->write("Hello, $name");

	//     return $response;
	// });

	require 'targets.php';
	require 'users.php';

});

$app->group('/siapApp', function () use ($app) {
	
	require 'siapApp/getAll.php';
	require 'siapApp/getGeneral.php';
	require 'siapApp/sendDimensionesElems.php';
	require 'siapApp/sendTargetsElems.php';
	require 'siapApp/sendVisitas.php';
	require 'siapApp/sendUserConsultationsChecklist.php';
	require 'siapApp/sendPolls.php';
	require 'siapApp/signup.php';
	require 'users.php';

});