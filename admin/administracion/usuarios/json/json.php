<?php  
	include_once '../../../../lib/j/j.func.php';

	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'usrAdmin';
			if(!empty($_POST['datos']['pwd'])){
				$_POST['datos']['pwd'] = encriptaUsr($_POST['datos']['pwd']);
			}else{
				unset($_POST['datos']['pwd']);
			}
			break;
		case 2:
			$post['tabla'] = 'AdminProyectos';
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
			$post['tabla'] = 'AdminProyectos';
			$post['where'] = "id = $_POST[prvId]";;

			echo atj(del($post));
			break;	
		case 4:
			// print2($_POST);

			try {			
				$prepare = $db->prepare("REPLACE INTO usrAdminBancarios 
					SET usuariosId = :usuariosId, titular = :titular, banco = :banco, cuenta = :cuenta, 
					CLABE = :CLABE, localidad = :localidad ");

				$prepare -> execute($_POST['datos']);

				echo '{"ok":"1"}';
			} catch (PDOException $e) {
				echo $e->getMessage();
				echo '{"ok":"0","err":"Err: IDB522"}';

			}

			break;	

		default:
			# code...
			break;
	}


?>