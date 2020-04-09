<?php

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/shoppers.php';

session_start();
$uId = $_SESSION['IU']['admin']['usrId'];


switch ($_POST['acc']) {
	case 1:
		$rots = getRotDisp($uId);
		echo atj($rots);
		break;
	case 2:
		// print2($_POST);
		$visUsr = $db->prepare("SELECT COUNT(*) FROM VisitasHistorial WHERE visitasId = ? AND shoppersId = $uId");
		$visUsr -> execute(array($_POST['vId']));
		$cuenta = $visUsr -> fetchAll(PDO::FETCH_NUM)[0][0];
		// print2($cuenta);
		if($cuenta > 0){
			echo updRotVis(null,$_POST['vId'],2,null,0,$uId,$_POST['coms']);
		}
		break;
	
	default:
		# code...
		break;
}




?>