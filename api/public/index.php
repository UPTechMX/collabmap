<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

require '../routes/index.php';

// API group
// $app->group('/api', function ($app) {
 
//     // version1
//     $app->group('/v1', function ($request, $response, $args) {
 
//         // Get book with ID
//         $app->get('/book/:id', function ($id) {
//  			$response->getBody()->write("Hello, $id");
//         });
       
//         // other versions one route
 
//     });
 
// })->add($mw);


$app->run();