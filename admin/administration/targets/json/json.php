<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Targets

	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Targets';
			break;
		case 2:
			$post['tabla'] = 'TargetsChecklist';
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
			$post['tabla'] = '';
			$post['where'] = "id = $_POST[prvId]";;

			echo atj(del($post));
			break;	

		default:
			# code...
			break;
	}


?>