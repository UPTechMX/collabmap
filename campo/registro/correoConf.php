<?php
$to = $em;
$subject = "Activación sistema Shoppers Consulting";
$from = "Shoppers Consulting <no-reply@sistema.shoppersconsulting.com>";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From:" . $from;

$message = file_get_contents('../registro/header.php');
$link = "http://sistema.shoppersconsulting.com/campo/registro/confirmacion.php?em=$em&h=$h";

$message .= 'Gracias por registrarte en el portal de Shoppers Consulting.<br/>
	Para terminar tu registro, es necesario que des click en el 
	<strong><a href="'.$link.'">link</a></strong>.
	<br/><br/>
	O copia y pega la siguiente liga en tu navegador:
	<br>
	<strong>'.$link.'</strong>';

$message .= file_get_contents('../registro/footer.php');

// $Vdata = file_get_contents('http:google.com');
mail($to,$subject,$message,$headers);
// echo ($message);
?>
