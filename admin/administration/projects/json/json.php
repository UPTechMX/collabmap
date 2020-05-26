<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Projects

	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Projects';
			break;
		case 2:
			$post['tabla'] = 'Audiences';
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
			$post['tabla'] = 'KML';
			$post['where'] = "id = $_POST[KMLId]";

			echo atj(del($post));
			break;	
		case 4:
			// print2($_POST);
			$post['tabla'] = 'Audiences';
			$post['where'] = "id = $_POST[eleId]";

			echo atj(del($post));
			break;	

		default:
			# code...
			break;
	}


?>