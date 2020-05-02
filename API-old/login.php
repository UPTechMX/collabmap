<?php

include_once '../lib/j/j.func.php';
$v = verifPWD($_POST['usuario'],$_POST['pwd'],'admin');

$login = $v['verif'];
if($login){
	$usrId  = $v['usrId'];
	$nombre  = $v['nombre'];
	$nivel = $v['nivel'];
	$hash = encriptaUsr("NL_$v[usrId]_$v[nivel]");
}else{
	$usrId  = 0;
	$nombre  = "";
	$nivel = 0;
	$hash = '';
}



?>

{ 
	"login": <?php echo $login?'true':'false'; ?>, 
	"userId": <?php echo $usrId; ?>, 
	"nombre":"<?php echo $nombre; ?>",
	"nivel":<?php echo $nivel; ?>,
	"hash":"<?php echo $hash; ?>" 
}