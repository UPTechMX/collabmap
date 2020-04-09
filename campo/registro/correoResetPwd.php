<?php
$to = $em;
$subject = "Cambio de contraseña Shoppers Consulting";
$from = "Shoppers Consulting <no-reply@sistema.shoppersconsulting.com>";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From:" . $from;

$message = file_get_contents('../registro/header.php');
$link = "http://sistema.shoppersconsulting.com/campo/registro/cambiarPwd.php?em=$em&h=$h";

$message .= 'Se ha solicitado cambiar la contraseña del sistema de Shoppers Consulting.<br/>
	Para continuar dar click en el 
	<strong><a href="'.$link.'">link</a></strong>.
	<br/><br/>
	O copia y pega la siguiente liga en tu navegador:
	<br>
	<strong>'.$link.'</strong>
	<br/>
	<br/>
	Si no fuiste tu quien solicitó el cambio de contraseña, ignora este correo';

$message .= file_get_contents('../registro/footer.php');

// $Vdata = file_get_contents('http:google.com');
mail($to,$subject,$message,$headers);
// echo ($message);
?>
