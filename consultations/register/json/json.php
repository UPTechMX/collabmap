<?php

	include_once '../../../lib/j/j.func.php';

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
					lastname = :lastname,
					email = :email,
					gender = :gender,
					age = :age,
					pwd = :pwd,
					hashConf = :hashConf,
					confirmed = 1,
					validated = 1
				");
				// print2($_POST['data']);
				$stmt->execute($_POST['data']);

				echo '{"ok":"1"}';
				include raiz().'register/confMail.php';
			}catch(PDOException $e){
				echo '{"ok":"0","e":"'.$e->getMessage(). '", "Linea": '.$e->getLine(). '"}';
			}


			break;
		case 'recover':

			$stmt = $db -> prepare("SELECT * FROM Users WHERE username = ?");
			$stmt -> execute(array($_POST['data']['username']));
			$userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

			$p['tabla'] = 'pwdRecover';
			$p['timestamp'] = 'timestamp';
			$p['datos']['hash'] = substr(encriptaUsr($rand), 7);
			$p['datos']['usersId'] = $userInfo['id'];

			
			try{
				echo atj(inserta($p));
				include raiz().'consultations/register/recoverMail.php';
			}catch(PDOException $e){
				echo '{"ok":"0","e":"'.$e->getMessage(). '", "Linea": '.$e->getLine(). '"}';
			}


			break;
		case 'chPwd':

			$stmt = $db->prepare("SELECT * FROM pwdRecover WHERE used IS NULL AND hash = :hash AND usersId = :usersId AND `timestamp` > '$minDate'");
			$arr['hash'] = $_POST['datos']['h'];
			$arr['usersId'] = $_POST['datos']['u'];
			$stmt -> execute($arr);
			$pwdInfo = $stmt -> fetchAll(PDO::FETCH_ASSOC)[0];

			// print2($pwdInfo);

			if(empty($pwdInfo)){
				exit('{"ok":0}');
			}
			// print2($_POST);

			$p['tabla'] = 'Users';
			$p['datos']['id'] = $_POST['datos']['u'];
			$p['datos']['pwd'] = encriptaUsr($_POST['datos']['pwd']);

			$r = json_decode(atj(upd($p)),true);
			
			// print2($r);

			if($r['ok'] == 1){
				$db->query("UPDATE pwdRecover SET used = 1 WHERE $pwdInfo[id]");
				echo '{"ok":1}';
			}

			// try{
			// 	echo atj(inserta($p));
			// 	include raiz().'consultations/register/recoverMail.php';
			// }catch(PDOException $e){
			// 	echo '{"ok":"0","e":"'.$e->getMessage(). '", "Linea": '.$e->getLine(). '"}';
			// }


			break;
		
		default:
			# code...
			break;
	}

?>