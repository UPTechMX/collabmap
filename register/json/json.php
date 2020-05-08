<?php

	include_once '../../lib/j/j.func.php';

	// print2($_POST);

	switch ($_POST['acc']) {
		case 'signup':

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
					pwd = :pwd,
					hashConf = :hashConf

				");
				// print2($_POST);
				// exit();

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



				echo '{"ok":"1"}';
				// include raiz().'register/confMail.php';
			}catch(PDOException $e){
				echo '{"ok":"0","e":"'.$e->getMessage(). '", "Linea": '.$e->getLine(). '"}';
			}


			break;
		
		default:
			# code...
			break;
	}

?>