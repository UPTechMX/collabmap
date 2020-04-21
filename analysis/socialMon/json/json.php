<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso analysis;
	$usrId = $_SESSION['CM']['questionnaires']['usrId'];
	// echo "usrId: $usrId\n";
	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = '';
			break;
		case 3:
		
		default:
			# code...
			break;
	}

	switch ($_POST['acc']) {
		case 1:
			$post['datos'] = $_POST['datos'];
			echo atj(inserta($post));
			break;
		case 2:
			$post['datos'] = $_POST['datos'];
			// print2($post);
			echo atj(upd($post));
			break;	

		default:
			# code...
			break;
	}


?>