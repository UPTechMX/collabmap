<?php

	include_once '../../lib/j/j.func.php';
	@session_start();
	// print2($_POST);

	switch ($_POST['acc']) {
		case 'signup':


			$message = $_POST['message'];
			$token = strtolower($_POST['token']);

				// validate captcha code 		
	if (isset($_SESSION['captcha_token']) && $_SESSION['captcha_token'] == $token) {

		//success your code here
		//echo "success";
			

			$rand = rand(0,1000000);

			$_POST['data']['hashConf'] = substr(encriptaUsr($rand), 7);

			$_POST['data']['pwd'] = encriptaUsr($_POST['data']['pwd']);
				
			try{
				$stmt = $db->prepare(
					"INSERT INTO Users SET
					username = :username,
					name = :name,
					confirmed = 1,
					validated = 1,
					-- lastname = :lastname,
					-- email = :email,
					-- gender = :gender,
					-- age = :age,
					telephone = :telephone,
					pwd = :pwd,
					hashConf = :hashConf
				");
				//unset($json[$_POST['data']['token']]);
				//print2($_POST);//comentar después

				$stmt->execute($_POST['data']);
				$uId = $db->lastInsertId();

				$buscTrgt = $db->prepare("SELECT * FROM Targets WHERE code = ?");
				$buscTrgt->execute(array($_POST['trgtCode']));

				$trgt = $buscTrgt->fetchAll(PDO::FETCH_ASSOC);
				if(!empty($trgt)){
					$trgtId = $trgt[0]['id'];
					$db->query("INSERT INTO UsersTargets SET usersId = $uId, targetsId = $trgtId");
				}
				// print2($trgt);
				
				 $_SESSION['CM']['questionnaires']['usrId']  = $uId;
    		     $_SESSION['CM']['questionnaires']['validated']  = 1;
    			 $_SESSION['CM']['questionnaires']['name']  = $_POST['data']['name'];

				echo '{"ok":"1"}';
				// include raiz().'register/confMail.php';
			}catch(PDOException $e){
				echo '{"ok":"0","e":"'.$e->getMessage(). '", "Linea": '.$e->getLine(). '"}';
			}
			} else {
							echo '{"ok":"2"}';
			
			}			

			break;
		
		default:
			# code...
			break;
	}

?>