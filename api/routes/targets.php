<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;



$app->group('/targets', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
      $name = $args['name'];
      $response->getBody()->write("Hello, $name");

      return $response;
  });


});