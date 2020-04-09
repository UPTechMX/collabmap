<?php

	include_once '../../lib/j/j.func.php';

	switch ($_POST['opt']) {
		case '1':
			break;
		
		default:
			# code...
			break;
	}

	switch ($_POST['acc']) {
		case '1':
			// print2($_POST);
			$_POST['datos']['pwd'] = encriptaUsr($_POST['datos']['pwd']);
			$semilla = 'notLandShoppers'.$_POST['datos']['username'];
			// echo "$semilla \n";
			$hash = encriptaUsr($semilla);
			$hash = substr($hash, 7);
			$_POST['datos']['hashConf'] = $hash;
			// print2($_POST);

			$post['tabla'] = 'Shoppers';
			$post['datos'] = $_POST['datos'];

			$rj = atj(inserta($post));
			echo $rj;

			$r = json_decode($rj,true);
			if($r['ok'] == 1){
				$h = $post['datos']['hashConf'];
				$em = $post['datos']['email'];
				include raiz().'campo/registro/correoConf.php';
			}
			// print2($post);
			break;
		case 2:
			// print2($_POST);
			$datos = $_POST['datos'];
			$buscUsr = $db -> prepare("SELECT * FROM Shoppers WHERE email = ?");
			$buscUsr -> execute(array($datos['email']));
			$usr = $buscUsr->fetchAll(PDO::FETCH_ASSOC)[0];
			// print2($usr);

			$ok = true;
			if(!empty($usr)){
				$semilla = rand(1,100000).$usr['username'];
				// echo $semilla;
				$hash = encriptaUsr($semilla);
				$hash = substr($hash, 7);
				$post['tabla'] = 'cambiarPwd';
				$post['datos']['shoppersId'] = $usr['id'];
				$post['datos']['hash'] = $hash;
				$post['datos']['expira'] = time()+60*60*3;

				$rj = atj(inserta($post));
				$r = json_decode($rj,true);
				if($r['ok'] == 1){
					$em = $datos['email'];
					$h = $hash;
					include raiz().'campo/registro/correoResetPwd.php';
				}else{
					$ok = false;
				}
			}

			if($ok){
				echo '{"ok":"1"}';
			}else{
				echo '{"ok":"0","err":"Error RPD345"}';
			}

			break;
		case 3:
			$pwd = encriptaUsr($_POST['datos']['pwd']);

			$datos = $_POST['datos'];
			$stmt = $db->prepare("SELECT c.*, s.email 
				FROM cambiarPwd c
				LEFT JOIN Shoppers s ON s.id = c.shoppersId
				WHERE c.hash = ? AND s.email = ?");
			$stmt->execute(array($datos['h'],$datos['em']));
			$liga = $stmt -> fetchAll(PDO::FETCH_ASSOC)[0];
			$hora = time();

			// print2($liga);
			$activo = $hora <= $liga['expira'];

			$post['tabla'] = 'Shoppers';
			$post['datos']['pwd'] = $pwd;
			$post['datos']['id'] = $liga['shoppersId'];
			// print2($_POST);

			if($activo){
				$rj =  atj(upd($post));
				$r = json_decode($rj,true);

				if($r['ok'] == 1){
					$rm = $db->query("DELETE FROM cambiarPwd WHERE id = $liga[id]");
					echo '{"ok":"1"}';
				}else{
					echo '{"ok":"0","err":"ERROR FPW883"}';
				}
			}
			break;

		default:
			# code...
			break;
	}


























?>