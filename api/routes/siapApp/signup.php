
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
                $to = $p['datos']['email'];
                $from = "<sistemas@capsus.mx>";
                $subject = "Colabmap | Registro exitoso";
                $mail = "Estimado usuario: \n\nSe le envía el presente correo para notificarle sobre";
                $mail .= " su registro exitoso en Colabmap. Esperamos disfrute su uso.\n\n";
                $mail .= "Este es un mensaje automatizado. No es necesario que conteste o tome otra acción.";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From:" . $from . "\r\n";
                $headers .= "Bcc:" . $from . "\r\n";
                mail($to,$subject,$mail,$headers);
                return '{"ok":1}';
        }else{
                $db->rollback();
                return '{"ok":0,"err":"'.$err.'"}';
        }

  });

});



?>