<?php
	session_start();
	include_once '../../../lib/j/j.func.php';

	// print2($_SESSION);


	switch ($_POST['opt']) {
		case '1':
			$post['tabla'] = 'Usuarios';
			$post['datos']['pwd'] = encriptaUsr($_POST['pwd']);
			$post['datos']['id'] = $_SESSION['pub']['usrId'];
			// print2($post);

			$p['tabla'] = 'chPwd';
			$p['datos']['usuariosId'] = $_SESSION['pub']['usrId'];
			$p['datos']['cambio'] = 1;
			$p['timestamp'] = 'fecha';

			inserta($p);
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

