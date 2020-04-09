<?php

include_once '../../../lib/j/j.func.php';
// include_once raiz().'lib/php/shoppers.php';

session_start();
$uId = $_SESSION['CM']['admin']['usrId'];


switch ($_POST['acc']) {
	case 1:

		$checa = $db->query("SELECT id FROM Visitas WHERE etapa = '$_POST[etapa]' AND clientesId = $_POST[cId]")->fetchAll(PDO::FETCH_NUM)[0][0];

		if(empty($checa)){		
			$cteInfo = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cId]")->fetchAll(PDO::FETCH_ASSOC)[0];
			$post['tabla'] = 'Visitas';
			$post['timestamp'] = 'timestamp';
			$post['datos']['proyectosId'] = $cteInfo['proyectosId'];
			$post['datos']['etapa'] = $_POST['etapa'];
			$post['datos']['clientesId'] = $_POST['cId'];
			$post['datos']['usuariosCreador'] = $uId;
			$post['datos']['usuarioProgramado'] = $uId;
			$post['datos']['fecha'] = date('Y-m-d');
			$post['datos']['estatus'] = $cteInfo['estatus'];

			// print2($post);
			echo atj(inserta($post));
		}else{
			echo '{"ok":"1","nId":"'.$checa.'"}';
		}

		// print2($_POST);
		break;
	case 2:

		$checa = $db->query("SELECT id FROM Visitas 
			WHERE etapa = '$_POST[etapa]' AND clientesId = $_POST[cId] 
			AND (finalizada IS NULL OR finalizada != 1)")->fetchAll(PDO::FETCH_NUM)[0][0];

		if(empty($checa)){		
			$cteInfo = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cId]")->fetchAll(PDO::FETCH_ASSOC)[0];
			$post['tabla'] = 'Visitas';
			$post['timestamp'] = 'timestamp';
			$post['datos']['proyectosId'] = $cteInfo['proyectosId'];
			$post['datos']['etapa'] = $_POST['etapa'];
			$post['datos']['clientesId'] = $_POST['cId'];
			$post['datos']['usuariosCreador'] = $uId;
			$post['datos']['usuarioProgramado'] = $uId;
			$post['datos']['fecha'] = date('Y-m-d');
			$post['datos']['estatus'] = $cteInfo['estatus'];

			// print2($post);
			echo atj(inserta($post));
		}else{
			echo '{"ok":"1","nId":"'.$checa.'"}';
		}

		// print2($_POST);
		break;
	
	default:
		# code...
		break;
}




?>