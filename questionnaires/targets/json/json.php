<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAccesoQuest();
	$usrId = $_SESSION['CM']['questionnaires']['usrId'];
	// echo "usrId: $usrId\n";
	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'TargetsElems';
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
			if($_POST['opt'] == 1){
				$post['datos']['usersId'] = $usrId;
			}
			// print2($post);
			echo atj(inserta($post));
			break;
		case 2:
			$post['datos'] = $_POST['datos'];
			// print2($post);
			echo atj(upd($post));
			break;	
		case 3:
			// echo " // crea visita\n";

			// print2($_POST);
			$p['tabla'] = 'Visitas';
			$p['timestamp'] = 'timestamp';
			$p['datos']['checklistId'] = $_POST['checklistId'];
			$p['datos']['elemId'] = $_POST['targetsElemId'];
			$p['datos']['type'] = 'trgt';

			echo atj(inserta($p));

			break;	

		default:
			# code...
			break;
	}


?>