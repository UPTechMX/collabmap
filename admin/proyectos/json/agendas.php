<?php  
session_start();
include_once '../../../lib/j/j.func.php';
checaAcceso(49);
$usrId = $_SESSION['CM']['admin']['usrId'];


switch ($_POST['acc']) {
	case 1:
		// print2($_POST);
		$post['tabla'] = 'Visitas';
		$post['timestamp'] = 'timestamp';
		$post['datos'] = $_POST['datos'];
		$post['datos']['usuariosCreador'] = $usrId;
		$db->beginTransaction();
		$rj = atj(inserta($post));
		$r = json_decode($rj,true);
		$ok = true;
		if($r['ok'] == 1){
			switch ($post['datos']['etapa']) {
				case 'visita':
					$estatus = 32;
					break;
				case 'instalacion':
					$estatus = 44;
					break;
				case 'reparacion':
					$estatus = 55;
					break;
				
				default:
					# code...
					break;
			}
			$comentarios = $_POST['comentarios'];
			$rj = updEstVisCte($r['nId'],$estatus,$usrId,$comentarios);
			$r = json_decode($rj,true);
			if($r['ok'] != 1){
				$ok = false;
			}
		}else{
			$ok = false;
		}

		if($ok){
			$db->commit();
			echo '{"ok":"1"}';
		}else{
			echo $rj;
			$db->rollBack();
		}

		break;
	case 2:
		// print2($_POST);
		$post['tabla'] = 'Visitas';
		$post['timestamp'] = 'timestamp';
		$post['datos'] = $_POST['datos'];
		// print2($post);
		$db->beginTransaction();
		$rj = atj(upd($post));
		$r = json_decode($rj,true);
		$ok = true;
		if($r['ok'] == 1){
			// print2($_POST);
			if($_POST['act'] == 'instalacion'){
				$estInst = 45;
				$vId = $post['datos']['id'];
				$instalador = $db->query("SELECT ei.instalador 
					FROM Visitas v
					LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
					WHERE v.id = $vId")->fetchAll(PDO::FETCH_NUM)[0][0];

				// print2($instalador);
				$estInst = empty($instalador)?45:46;
				// echo "estInst : $estInst\n";
			}

			if($_POST['act'] == 'reparacion'){
				$estInst = 56;
				$vId = $post['datos']['id'];
				$instalador = $db->query("SELECT ei.instalador 
					FROM Visitas v
					LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
					WHERE v.id = $vId")->fetchAll(PDO::FETCH_NUM)[0][0];

				// print2($instalador);
				$estInst = empty($instalador)?56:57;
				// echo "estInst : $estInst\n";

			}

			$estatus = $_POST['act'] == 'visita'?33:$estInst;
			// echo "estatus : $estatus , Act : $_POST[act] \n";
			$rj = updEstVisCte($post['datos']['id'],$estatus,$usrId,'');
			$r = json_decode($rj,true);
			if($r['ok'] != 1){
				$ok = false;
			}
		}else{
			$ok = false;
		}

		if($ok){
			$db->commit();
			echo '{"ok":"1"}';
		}else{
			echo $rj;
			$db->rollBack();
		}

		break;
	case 3:
		switch ($_POST['act']) {
			case 'visita':
				$estatus = 31;
				break;
			case 'instalacion':
				$estatus = 42;
				break;
			case 'reparacion':
				$estatus = 60;
				break;
			
			default:
				# code...
				break;
		}

		// $estatus = $_POST['act'] == 'visita'?31:42;
		// echo "Estatus : $estatus\n";
		// print2($_POST);
		// print2($estatus);
		// exit();
		echo atj(updEstVisCte($_POST['datos']['visitasId'],$estatus,$usrId,$_POST['datos']['comentarios']));
		break;

	
	default:
		# code...
		break;
}

?>