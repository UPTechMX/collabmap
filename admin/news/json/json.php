<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso news

	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'News';
			break;
		case 2:
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
			// print2($post);
			echo atj(inserta($post));
			break;
		case 2:
			$post['datos'] = $_POST['datos'];
			// print2($post);
			echo atj(upd($post));
			break;	
		case 3:
			// print2($_POST);
			$post['tabla'] = 'News';
			$post['where'] = "id = $_POST[nId]";;

			echo atj(del($post));
			break;	
		case 4:
			break;	
		default:
			# code...
			break;
	}


?>