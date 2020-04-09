<?php

include_once '../../../lib/j/j.func.php';
session_start();
$uId = $_SESSION['CM']['admin']['usrId'];
$nivel = $_SESSION['CM']['admin']['nivel'];

checaAcceso(49);

switch ($_POST['opt']) {
	case 1:
		$post['tabla'] = 'LlamadasHist';
		$post['timestamp'] = 'timestamp';
		$_POST['datos']['usuariosId'] = $uId;
		# code...
		break;
	case 2:
		$post['tabla'] = 'EquiposInstalacion';
		break;

	
	default:
		# code...
		break;
}

switch ($_POST['acc']) {
	case '1':
		$post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(inserta($post));
		break;
	case '2':
		$post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(upd($post));
		break;	
	case '3':
		echo atj(updEstCliente($_POST['datos']['cteId'],4,$uId,null,$_POST['datos']['comentarios']));
		break;	
	case '4':
		echo atj(instalaciones($_POST['fecha'],$_POST['pryId']));
		break;	
	
	default:
		# code...
		break;
}
?>

