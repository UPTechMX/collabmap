<?php  
	include_once '../../../lib/j/j.func.php';
	session_start();
	$uId = $_SESSION['CM']['admin']['usrId'];

	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Reconocimientos';
			$_POST['datos']['usuariosId'] = $uId;
			break;
		case 2:
			$post['tabla'] = 'ReconocimientoMediosPorImplementar';
			break;
		case 4:
			$post['tabla'] = 'ReconocimientosColonias';
			// print2($_POST);
			// $_POST['datos']['colonia'] = trim($_POST['datos']['colonia']);
			break;
		case 5:
			$post['tabla'] = 'ReconocimientosUbicaciones';
			// print2($_POST);
			// $_POST['datos']['colonia'] = trim($_POST['datos']['colonia']);
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
			$post['tabla'] = 'ReconocimientosColonias';
			$post['where'] = "id = $_POST[elemId]";
			// print2($post);
			echo atj(del($post));
			break;
		case 4:
			$ok = 1;
			// print2($_POST);
			$post['tabla'] = 'ReconocimientoMedios';
			foreach ($_POST['datos'] as $d) {
				$post['datos'] = $d;
				$rj = replace($post);
				$r = json_decode($rj,true);
				if($r['ok'] != 1){
					$ok = 0;
					$err = $r['err'];
					echo '{"ok":"'.$ok.'"}';
					break;
				}
			}
			echo '{"ok":"'.$ok.'","err":"'.$err.'"}';
			// print2($post);
			// echo atj(del($post));
			break;
		case 5:
			$post['tabla'] = 'ReconocimientoMediosPorImplementar';
			$post['where'] = "id = $_POST[elemId]";
			// print2($post);
			echo atj(del($post));
			break;
		case 6:
			$post['tabla'] = 'ReconocimientosUbicaciones';
			$post['where'] = "id = $_POST[elemId]";
			// print2($post);
			echo atj(del($post));
			break;
		default:
			# code...
			break;
	}


?>