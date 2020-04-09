<?php  
	include_once '../../../../lib/j/j.func.php';

	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Instalaciones';
			break;
		
		default:
			# code...
			break;
	}

	switch ($_POST['acc']) {
		case 1:
			$post['datos'] = $_POST['datos'];
			// print2($post);
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