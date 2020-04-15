<?php

include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';
session_start();

$uId = $_SESSION['CM']['admin']['usrId'];

// print2($_POST);

$sCHK = $_SESSION['CM']['chk'][$_POST['vId']];

if( empty( $sCHK ) ){
	exit('{"ok":"0"}');
}
// echo "ZZZ";

switch ($_POST['acc']) {
	case 1:
		$ok = true;
		// print2($_POST);
		$db->beginTransaction();
		try {
			$db->query("UPDATE Visitas SET finishDate = NOW(), finalizada = 1 WHERE id = $_POST[vId]");

			// echo "AAA";
			if($ok){
				// echo "BBB";
				$chk = new Checklist($_POST['vId']);
				$rj = atj($chk-> insertaCacheVisita($_POST['vId']));
				// $rj = insertaCacheVisita($_POST['vId']);
				// echo $rj;
				$r = json_decode($rj,true);

				if($r['ok'] != 1){
					$ok = false;
					// echo $rj;
					$err = 'Error al insertar el cache del cuestionario: Err: UEV322';
				}
			}

			if($ok){
				$db->commit();
				echo '{"ok":"1"}';
			}else{
				$db->rollBack();
				echo '{"ok":"0","err":"'.$err.'"}';
			}
			// echo $rj;
		} catch (PDOException $e) {
			$db->rollBack();
			echo '{"ok":"0","err":"Error al regustrar el cuestionario"}';
		}
		break;
	case 2:

		include_once raiz().'lib/php/calcCuest.php';
		include_once raiz().'lib/php/checklist.php';
		$chk = new Checklist($_POST['vId']);
		$chkInf = $chk->getGeneral();
		$vInfo = $chk->getVisita();
		$ok = true;
		$db->beginTransaction();
		$p['tabla'] = 'Visitas';
		$p['datos'] = $_POST['datos'];
		$p['datos']['id'] = $_POST['vId'];
		// $p['datos']['usuarioRealizo'] = $uId;

		$rj = atj(upd($p));
		// echo $rj;
		$r = json_decode($rj,true);
		if($r['ok'] != 1){
			$ok = false;
			$errF = $r['err'];
			$err = 'Error al actualizar la visita. ERR:UPDV023';
		}

		if($ok){
			$db->commit();
			echo '{"ok":"1"}';
		}else{
			$db->rollBack();
			echo '{"ok":"0","err":"'.$err.'", "errF":"'.$errF.'"}';
		}

		// print2($_POST);
		break;
	case 3:
		$p['tabla'] = 'Multimedia';
		$datos['visitasId'] = $_POST['vId'];
		switch ($_POST['tipo']) {
			case 1:
				$datos['tipo'] = 'img';
				break;
			case 2:
				$datos['tipo'] = 'audio';
				break;
			case 3:
				$datos['tipo'] = 'video';
				break;
			default:
				break;
		}
		$datos['nombre'] = $_POST['dat']['nombreArchivo'];
		$datos['archivo'] = $_POST['dat']['prefijo'].$_POST['dat']['nombreArchivo'];
		$p['datos'] = $datos;
		// print2($p);
		echo atj(inserta($p));
		break;
	case 4:
		$multimedia = $db->query("SELECT * FROM Multimedia WHERE id = $_POST[mId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		$db->exec("DELETE FROM Multimedia WHERE id = $_POST[mId]");
		unlink(raiz()."campo/archivosCuest/$multimedia[archivo]");
		// print2($multimedia);
		echo '{"ok":"1"}';
		break;
	case 5:
		$post['tabla'] = 'Clientes';
		$post['datos']['id'] = $_POST['cId'];
		$post['datos']['instalacionSug'] = $_POST['instalacionSug'];

		echo atj(upd($post));
		// print2($_POST);

		break;
	case 6:
		// print2($_POST);
		$ok = true;
		$post['tabla'] = 'Clientes';
		$post['datos']['id'] = $_POST['cId'];
		$post['datos']['instalacionRealizada'] = $_POST['instalacionRealizada'];
		$post['datos']['costo'] = $_POST['costo'];

		// print2($post);
		$db->beginTransaction();
		$rj =  atj(upd($post));
		// echo $rj;
		$r = json_decode($rj,true);
		if($r['ok'] != 1){
			$ok = false;
			// echo $rj;
			$err = 'Error al actualizar al cliente. Err: UCIR:004';
		}


		if($ok){
			// print2($_POST);
			$db->query("DELETE FROM ClientesComponentes WHERE clientesId = $_POST[cId]");
			try {			
				$insComp = $db->prepare("INSERT INTO ClientesComponentes 
					SET clientesId = $_POST[cId], dimensionesElemId = :componente, cantidad = :cantidad ");
				foreach ($_POST['componentes'] as $k => $c) {
					$arr['componente'] = explode('_',$k)[1];
					$arr['cantidad'] = $c;
					$insComp->execute($arr);
				}
			} catch (PDOException $e) {
				$ok = 0;
				// echo $e->getMessage();
				$err = 'Error al insertar los componentes. Err: ICC:544';
			}
		}

		if($ok){
			$db->commit();
			echo '{"ok":"1"}';
		}else{
			$db->rollBack();
			echo '{"ok":"0","err":"'.$err.'"}';
		}

		break;
	case 7:
		$post['tabla'] = 'Clientes';
		$post['datos'] = $_POST['dat'];
		// print2($_POST);
		echo upd($post);
		// echo "AAA"
		break;
	default:
		# code...
		break;
}




?>

