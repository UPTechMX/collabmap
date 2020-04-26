<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Public consultations

	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'publicConsultations';
			if(empty($_POST['datos']['projectsId'])){
				unset($_POST['datos']['projectsId']);
			}
			break;
		case 2:
			break;
		case 3:
			break;		
		default:
			# code...
			break;
	}

	// print2($_POST);
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
			break;	

		default:
			# code...
			break;
	}


?>