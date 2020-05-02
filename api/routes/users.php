<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Interfaces\RouteGroupInterface;



$app->group('/users', function () use ($app) {
  // $app->get('/empleados', 'obtenerEmpleados');
  $app->post('/login/', function (Request $request, Response $response, array $args) {

  		//POST or PUT
  		$allPostPutVars = $request->getParsedBody();
  		$username = $allPostPutVars['username'];
  		$pwd = $allPostPutVars['pwd'];

  		$v = verifPWD($username,$pwd,'questionnaires');

  		$login = $v['verif'];
  		if($login){
  			$resp['usrId']  = $v['usrId'];
  			$resp['name']  = $v['name'];
  			// $resp['validated'] = $v['validated'];
  			$resp['token'] = $v['validated'] == 1 ? substr(encriptaUsr("UPT_$v[usrId]_$v[validated]"),7):"";
  		}else{
  			$resp['usrId']  = 0;
  			$resp['name']  = "";
  			// $resp['validated'] = 0;
  			$resp['token'] = '';
  		}

    	$response->getBody()->write(atj($resp));

      return $response;
  });


});
