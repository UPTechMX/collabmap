<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/usrInt.php';
checaAcceso(50);

$usrId = $_SESSION['CM']['admin']['usrId'];

// print2($_POST);
switch ($_POST['opt']) {
	case '1':
		$post['tabla'] = 'usrAdmin';
		$post['datos']['pwd'] = encriptaUsr($_POST['pwd']);
		$post['datos']['id'] = $usrId;
		// print2($post);

		break;
	default:
		# code...
		break;
}


switch ($_POST['acc']) {
	case 1:		
		break;

	case '2':
		// print2($post);
		echo atj(upd($post));
		break;	

	
	default:
		# code...
		break;
}


?>