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
		unlink(raiz()."chkPhotos/$multimedia[archivo]");
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
	case 8:
		// print2($_POST);
		$uId = $_SESSION['CM']['admin']['usrId'];
		$h = "NL-".$_POST['datos']['visitasId']."-".$uId."-".$_POST['datos']['preguntasId']."-$_POST[pId]";
		$vh = password_verify($h,$_POST['hash']);
		if(!$vh){
			exit('{"ok":"0"}');
		}
		$sCHK = $_SESSION['CM']['chk'][$_POST['datos']['visitasId']];
		$buscResp = $db->prepare("SELECT * FROM RespuestasVisita WHERE visitasId = :visitasId AND preguntasId = :preguntasId");
		$arr['visitasId'] = $_POST['datos']['visitasId'];
		$arr['preguntasId'] = $_POST['datos']['preguntasId'];

		$buscResp -> execute($arr);
		$result = $buscResp->fetchAll(PDO::FETCH_ASSOC);

		$ok = true;
		if( empty($result) ){
			$p['tabla'] = 'RespuestasVisita';
			$p['datos'] = $_POST['datos'];
			$p['datos']['respuesta'] = 'spatial';
			$rj = atj(inserta($p));
			$r = json_decode($rj,true);
			$rvId = $r['nId'];
			$_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['respuesta'] = 'spatial';
			$_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['justificacion'] = '';
			$_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['valResp'] = '-';
			if($r['ok'] != 1){
				$ok = false;
				$err = "Error: ERRIR:022";
			}
		}else{
			$rvId = $result[0]['id'];
			$_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['respuesta'] = 'spatial';
			$_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['justificacion'] = '';
			$_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]['valResp'] = '-';

		}
		// print2($_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res']);

		if($ok){
			$ip['tabla'] = 'Problems';
			$ip['datos'] = $_POST['problem'];
			$ip['datos']['respuestasVisitaId'] = $rvId;
			
			$rpj = inserta($ip);
			$rp = json_decode($rpj,true);
			$prId = $rp['nId'];
		}
		if($rp['ok'] != 1){
			$ok = false;
			$err = 'Error: ERIP:344';
		}

		if($ok){
			$pId = $rp['nId'];
			$point['tabla'] = 'Points';
			foreach ($_POST['latlngs'] as $l) {
				$point['datos'] = $l;
				$point['datos']['problemsId'] = $pId;

				$respPointJ = inserta($point);
				$respPoint = json_decode($respPointJ,true);
				if($respPoint['ok'] != 1){
					$ok = false;
					$err = "Error: ERIP:394";
					echo $respPointJ;
				}

			}
		}

		if($ok){
			echo '{"ok":"1","prId":"'.$prId.'"}';
		}else{
			echo '{"ok":"0","err":"'.$err.'"}';
		}

		// print2($_POST);


		break;
	case 9:
		// print2($_POST);
		$sas = $db->query("SELECT sa.id, sa.id as saId, p.*
			FROM Studyarea sa
			LEFT JOIN StudyareaPoints p ON p.studyareaId = sa.id
			WHERE preguntasId = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

		echo atj($sas);
		break;

	case 10:
		// print2($_POST);
		$sql = "SELECT pr.id, pr.*, p.id as pId, p.lat, p.lng, pr.type
			FROM RespuestasVisita rv
			LEFT JOIN Problems pr ON pr.respuestasVisitaId = rv.id
			LEFT JOIN Points p ON p.problemsId = pr.id
			WHERE rv.preguntasId = $_POST[pId] AND rv.visitasId = $_POST[vId] ";
		// echo $sql."\n\n";
		$prs = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

		echo atj($prs);

		break;
	case 11:

		if(is_numeric($_POST['prId'])){

			$ok = true;
			$db->query("DELETE FROM Points WHERE problemsId = $_POST[prId]");

			$pId = $_POST['prId'];
			$point['tabla'] = 'Points';
			foreach ($_POST['latlngs'] as $l) {
				$point['datos'] = $l;
				$point['datos']['problemsId'] = $pId;

				$respPointJ = inserta($point);
				$respPoint = json_decode($respPointJ,true);
				if($respPoint['ok'] != 1){
					$ok = false;
					$err = "Error: ERIP:394";
					echo $respPointJ;
				}
			}
			if($ok){
				echo '{"ok":"1"}';
			}

		}
		break;

	case 12:
		// print2($_POST);
		if(is_numeric($_POST['lIds'][0])){
			$prId = $_POST['lIds'][0];
			$rvId = $db->query("SELECT * FROM Problems WHERE id = $prId")->fetchAll(PDO::FETCH_ASSOC)[0]['respuestasVisitaId'];
		}

		foreach ($_POST['lIds'] as $lId) {
			if(is_numeric($lId)){
				$db->query("DELETE FROM Problems WHERE id = $lId");
			}
		}

		$count = $db->query("SELECT COUNT(*) as cuenta FROM Problems WHERE respuestasVisitaId = $rvId  ")->fetchAll(PDO::FETCH_NUM)[0][0];
		if($count == 0){
			$db -> query("DELETE FROM RespuestasVisita WHERE id = $rvId");
			unset($_SESSION['CM']['chk'][$_POST['datos']['visitasId']]['res'][$_POST['pId']]);
		}

		echo '{"ok":"1","count":"'.$count.'"}';

		break;
	case 13:
		$post['tabla'] = 'Problems';
		$post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(upd($post));
		break;	


	default:
		# code...
		break;
}




?>

