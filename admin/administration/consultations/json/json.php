<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Consultations

	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Consultations';
			break;
		case 2:
			$post['tabla'] = 'ConsultationsChecklist';
			break;
		case 3:
			$post['tabla'] = 'Documents';
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
		case 3:
			// print2($_POST);
			$post['tabla'] = 'ConsultationsChecklist';
			$post['where'] = "id = $_POST[tcId]";;

			echo atj(del($post));
			break;	
		case 4:
			// print2($_POST);
			$post['tabla'] = 'ConsultationsAudiences';
			$post['where'] = "id = $_POST[eleId]";;

			echo atj(del($post));
			break;
		case 5:
			// print2($_POST);
			$post['tabla'] = 'Documents';
			$post['where'] = "id = $_POST[dId]";;

			echo atj(del($post));
			break;	


		default:
			# code...
			break;
	}


?>