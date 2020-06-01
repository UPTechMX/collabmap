<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../../lib/j/j.func.php';
	}

	checaAccesoConsult();
	$usrId = $_SESSION['CM']['consultations']['usrId'];

	if(!is_numeric($_POST['elemId'])){
		exit('{"ok":0}');
	}
	// print2($_POST);
	switch ($_POST['acc']) {
		case '1':
			if(!is_numeric($_POST['elemId'])){
				exit();
			}
			switch ($_POST['find']) {
				case 'audiences':
					$elems = $db->query("SELECT name as nom, id as val, 'clase' as clase
						FROM Audiences 
						WHERE projectsId = $_POST[elemId]")->fetchAll(PDO::FETCH_ASSOC);
					break;
				
				default:
					# code...
					break;
			}
			echo atj($elems);
			break;
		case '2':

			$p['tabla'] = 'UsersAudiences';
			$p['datos']['usersId'] = $usrId;
			$p['datos']['audiencesId'] = $_POST['datos']['audiencesId'];
			$p['datos']['dimensionesElemId'] = $_POST['datos']['padre'];

			$stmt = $db->prepare("SELECT COUNT(*) FROM UsersAudiences 
				WHERE usersId = :usersId AND audiencesId = :audiencesId AND dimensionesElemId = :dimensionesElemId");
			$stmt -> execute($p['datos']);

			$cuenta = $stmt ->fetchAll(PDO::FETCH_NUM)[0][0];
			
			if($cuenta == 0){
				echo atj(inserta($p));
			}else{
				echo '{"ok":2}';
			}


			break;
		case 3:
			$stmt = $db->prepare("SELECT COUNT(*) FROM UsersAudiences WHERE usersId = $usrId AND id = ?");
			$stmt -> execute(array($_POST['elemId']));
			$count = $stmt->fetchAll(PDO::FETCH_NUM)[0][0];

			if($count>0){
				$p['tabla'] = 'UsersAudiences';
				$p['where'] = "id = $_POST[elemId]";
				echo atj(del($p));
				// print2($p);
			}



		default:
			# code...
			break;
	}


?>
