<?php

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';
session_start();
$uId = $_SESSION['CM']['admin']['usrId'];
$nivel = $_SESSION['CM']['admin']['nivel'];

// print2($_SESSION['CM']['admin']);
// print2($_POST);

$vId = $_POST['datos']['visitasId'];

if( empty( $uId ) ){
	exit('{"ok":"0","err":"no logueado"}');
}

if($nivel <= 10){
	$busq = $db->prepare("SELECT * FROM VisitasUsuarios WHERE usuariosId = ? AND visitasId = ?");
	$busq -> execute([$uId,$_POST['datos']['visitasId']]);
	$b = $busq->fetchAll(PDO::FETCH_ASSOC)[0];
	if($b['asignada'] != 1 && $b['estatus'] < 70){
		exit('{"ok":"0","err":"no asignada"}');
	}	
}

// print2([$_POST['datos']['visitasId']]);
$busqv = $db->prepare("SELECT * FROM Visitas WHERE id = ?");
$busqv -> execute([$_POST['datos']['visitasId']]);
$bv = $busqv->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($bv);

// if($bv['aceptada'] < 60 || $bv['aceptada'] > 90){
// 	exit('{"ok":"0","err":"no se puede editar"}');
// }



$h = "NL-".$_POST['datos']['visitasId']."-".$uId."-".$_POST['datos']['preguntasId']."-$_POST[pId]";
// echo $h;
$vh = password_verify($h,$_POST['hash']);
// echo "<br/> ---- $vh ----<br/>";
if(!$vh){
	exit('{"ok":"0"}');
}
// print2($_POST);
$db->beginTransaction();
try {
	$inserta = $db->prepare("REPLACE INTO RespuestasVisita SET visitasId = :visitasId, preguntasId = :preguntasId, respuesta = :respuesta, justificacion = :justificacion");
	$inserta->execute($_POST['datos']);

	$chk = new Checklist($vId);
	$chk -> insertaCacheVisita($vId);

	creaCambio('Visitas', $vId);

	$db->commit();
	// print2( $_SESSION['shopper']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']] ) ;
	exit('{"ok":"1"}');
} catch (PDOException $e) {
	$db->rollBack();
	// echo $e->getMessage()."\n";
	exit('{"ok":"0","err":"Error al insertar la respuesta Err:RRI996"}');	
}




?>

