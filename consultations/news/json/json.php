<?php

	session_start();
	if(!function_exists('raiz')){
		include '../../../lib/j/j.func.php';
	}

	$usrId = $_SESSION['CM']['consultations']['usrId'];
	checaAccesoConsult();

	if(empty($_POST['newsId']) || !is_numeric($_POST['newsId'])){
		exit('No tienes accesos');
	}

	switch ($_POST['acc']) {
		case 1:
			$cuenta = $db->query("SELECT COUNT(*) as count
				FROM UsersLikes 
				WHERE usersId = $usrId AND newsId = $_POST[newsId]")->fetchAll(PDO::FETCH_NUM)[0][0];
			
			
			if($cuenta == 0){
				$db->query("INSERT INTO UsersLikes SET usersId = $usrId, newsId = $_POST[newsId]");
				echo '{"ok":1}';
			}else{
				$db->query("DELETE FROM UsersLikes WHERE usersId = $usrId AND newsId = $_POST[newsId]");
				echo '{"ok":2}';
			}

			break;
		default:
			# code...
			break;
	}


?>
