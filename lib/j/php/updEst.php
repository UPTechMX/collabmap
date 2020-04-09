<?php

session_start();
include_once '../j.func.php';
$usrId = $_SESSION['IU']['admin']['usrId'];

// echo "usrId: $usrId\n";
// print2($_POST);

switch ($_POST['metodo']) {
	case 'cliente':
		echo atj(updEstCliente($_POST['cId'],$_POST['estatus'],$usrId,$_POST['vId'],$_POST['comentario']));
		break;

	case 'visita':
		echo atj(updEstVisCte($_POST['vId'],$_POST['estatus'],$usrId,$_POST['comentario']));
		break;
	
	default:
		# code...
		break;
}

?>