<?php

include_once '../../../lib/j/j.func.php';
session_start();
$uId = $_SESSION['IU']['admin']['usrId'];


$h = "NL-".$_POST['datos']['visitasId']."-".$uId."-".$_POST['datos']['preguntasId']."-$_POST[pId]";
// echo $h;
$vh = password_verify($h,$_POST['hash']);
// echo "<br/> ---- $vh ----<br/>";
if(!$vh){
	exit('{"ok":"0"}');
}

$sCHK = $_SESSION['IU']['chk'][$_POST['datos']['visitasId']];

if( !isset( $sCHK['res'][$_POST['pId']] ) ){
	exit('{"ok":"0"}');
}

// print2($_POST['datos']);
try {
	$inserta = $db->prepare("REPLACE INTO RespuestasVisita 
		SET visitasId = :visitasId, preguntasId = :preguntasId, respuesta = :respuesta, 
		justificacion = :justificacion, identificador = :identificador");
	$inserta->execute($_POST['datos']);

	$_SESSION['IU']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['respuesta'] = $_POST['datos']['respuesta'];
	$_SESSION['IU']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['justificacion'] = $_POST['datos']['justificacion'];
	$_SESSION['IU']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['valResp'] = $_POST['valResp'];
	// print2( $_SESSION['IU']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']] ) ;
	exit('{"ok":"1"}');
} catch (PDOException $e) {
	echo $e->getMessage()."<br/>";
	exit('{"ok":"0","err":"Error al insertar la respuesta Err:RRI998"}');	
}




?>

