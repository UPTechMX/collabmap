<?php


$cuerpo = file_get_contents(raiz().'register/mailHeader.php');

$cuerpo .= "MENSAJE DEL CORREO";
$hash = $_POST['data']['hashConf'];
$cuerpo .= "liga  <a href='server/questionnaries/confirmation/?confId='.$hash  />";

$cuerpo .= "<strong>ATENTAMENTE:<br/><br/> CollabMap</strong>";

$cuerpo .= file_get_contents(raiz().'register/mailFooter.php');

// print2($pryCtas);
$from = "CollabMap <no-reply@capsus.mx>";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From:" . $from . "\r\n";
$headers .= "Bcc:" . $from . "\r\n";

$subject = "Collabmap mail confirmation";
$to = $_POST['data']['email'];
mail($to,$subject,$cuerpo,$headers);
