<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso externalUsers

	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Users';
			if(!empty($_POST['datos']['pwd'])){
				$_POST['datos']['pwd'] = encriptaUsr($_POST['datos']['pwd']);
			}else{
				unset($_POST['datos']['pwd']);
			}
			break;
		case 2:
			$post['tabla'] = 'UsersTargets';
			break;
		case 3:
			$post['tabla'] = 'AdminProyectos';
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
			$post['tabla'] = 'UsersTargets';
			$post['where'] = "id = $_POST[tuId]";;

			echo atj(del($post));
			break;	

		default:
			# code...
			break;
	}


?>