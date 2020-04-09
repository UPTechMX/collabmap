<?php
	session_start();
	include_once '../../../lib/j/j.func.php';

	// print2($_SESSION);


	switch ($_POST['opt']) {
		case '1':
			$post['tabla'] = 'Shoppers';
			$post['datos']['pwd'] = encriptaUsr($_POST['pwd']);
			$post['datos']['id'] = $_SESSION['CM']['admin']['usrId'];
			// print2($post);

			break;
		default:
			# code...
			break;
	}

	switch ($_POST['acc']) {
		case '1':
			// print2($post);
			echo atj(upd($post));
			break;	
		default:
			# code...
			break;
	}
?>

