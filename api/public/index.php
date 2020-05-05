<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            // 'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ]
];

$app = new \Slim\App($config);

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