<?php
session_start();

include_once '../../../lib/j/j.func.php';
include_once '../../../lib/php/checklist.php';
include_once '../../../lib/php/calcCuest.php';
$uId = $_SESSION['IU']['admin']['usrId'];
$nivel = $_SESSION['IU']['admin']['nivel'];

// print2($_SESSION['IU']['admin']);

if( empty( $uId ) ){
	exit('{"ok":"0","err":"no logueado"}');
}

if($nivel <= 10){
	$busq = $db->prepare("SELECT * FROM VisitasUsuarios WHERE usuariosId = ? AND visitasId = ?");
	$busq -> execute([$uId,$_POST['vId']]);
	$b = $busq->fetchAll(PDO::FETCH_ASSOC)[0];
	if($b['asignada'] != 1 && $b['estatus'] < 70){
		exit('{"ok":"0","err":"no asignada"}');
	}	
}

$busqv = $db->prepare("SELECT * FROM Visitas WHERE id = ?");
$busqv -> execute([$_POST['vId']]);
$bv = $busqv->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($bv);

// if($bv['aceptada'] < 60 || $bv['aceptada'] > 90){
// 	exit('{"ok":"0","err":"no se puede editar"}');
// }



// echo "aaa";

switch ($_POST['acc']) {
	case 1:

		if($_POST['opt'] == 1)
			$estatus = 90;
		else
			$estatus = 91;

		$db->beginTransaction();

		$shoCalif = $db->prepare("UPDATE Visitas SET shopperCalif = ? WHERE id = ?");
		$shoCalif -> execute(array($_POST['calif'],$_POST['vId']));

		$vInfP = $db->prepare("SELECT * FROM Visitas WHERE id = ?");
		$vInfP -> execute(array($_POST['vId']));
		$shopId = $vInfP -> fetchAll(PDO::FETCH_ASSOC)[0]['shoppersId'];

		$prom = $db->query("SELECT AVG(shopperCalif) FROM Visitas WHERE shoppersId = $shopId")->fetchAll(PDO::FETCH_NUM)[0][0];
		$db->query("UPDATE Shoppers SET calificacion = $prom WHERE id = $shopId");


		$estatusPago[3] = 10;
		$rj =  atj(updEstatusVis($_POST['vId'],$estatus,$uId,null,$estatusPago,null));
		$r = json_decode($rj,true);
		if($r['ok'] == 1){
			try {

				$chk = new Checklist($_POST['vId']);
				$chkId = $chk->id;
				$chk -> getGeneral();
				// print2($chk->general);
				$visUsr = $db->prepare("UPDATE VisitasUsuarios 
					SET costoRevision = :costoRevision, estatus = :estatus, estatusPagoRev = :estatusPagoRev  
					WHERE usuariosId = $uId AND visitasId = :vId");
				$arrVisUsr = array('vId'=>$_POST['vId'],'estatus'=>$estatus,"estatusPagoRev"=>10,
					'costoRevision'=>$chk->general['costoRevision']);

				$visRev = $db->prepare("UPDATE VisitasRevisores
					SET costoRevision = :costoRevision, estatus = :estatus, estatusPagoRev = :estatusPagoRev, fecha = NOW()
					WHERE usuariosId = $uId AND visitasId = :vId");

				$arrRevUsr = array('vId'=>$_POST['vId'],'estatus'=>$estatus,"estatusPagoRev"=>10,
					'costoRevision'=>$chk->general['costoRevision']);

				$prepareUPD = $db -> prepare("UPDATE Pagos SET fecha = NOW() WHERE visitasId = ? AND concepto = 3");
				$prepareUPD->execute(array($_POST['visitasId']));

				$visUsr -> execute($arrVisUsr);
				$visRev -> execute($arrRevUsr);
				$db->commit();
				echo '{"ok":"1"}';
			} catch (Exception $e) {
				$db->rollBack();
				// print2($e->getMessage());
				echo '{"ok":"0","err":"Error UVU344"}';
			}
		}else{
			echo '{"ok":"0","err":"Error UVU345"}';
		}
		// print2($_POST);
		break;
	case 2:
		// print2($_POST);
		$p['tabla'] = 'Visitas';
		$p['datos'] = $_POST['datos'];
		$p['datos']['id'] = $_POST['vId'];
		echo upd($p);
		// print2($arrUpd);
		break;
	case 3:
		// print2($_POST);
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
	// print2($_POST);
		$multimedia = $db->query("SELECT * FROM Multimedia WHERE id = $_POST[mId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		$db->exec("DELETE FROM Multimedia WHERE id = $_POST[mId]");
		@unlink(raiz()."campo/archivosCuest/$multimedia[archivo]");
		// print2($multimedia);
		echo '{"ok":"1"}';
		break;
	case 6:
		// print2($_POST);
		$ok = true;

		$campo = $_POST['etapa'] == 'instalacion' ? 'instalacionRealizada' : 'instalacionSug';
		$post['tabla'] = 'Clientes';
		$post['datos']['id'] = $_POST['cId'];
		$post['datos'][$campo] = $_POST['instalacionRealizada'];
		$post['datos']['costo'] = $_POST['costo'];


		$db->beginTransaction();
		$rj =  atj(upd($post));
		// echo $rj;
		$r = json_decode($rj,true);
		if($r['ok'] != 1){
			$ok = false;
			// echo $rj;
			$err = 'Error al actualizar al cliente. Err: UCIR:004';
		}


		if($ok && $_POST['etapa'] == 'instalacion'){
			// print2($_POST);
			$db->query("DELETE FROM ClientesComponentes WHERE clientesId = $_POST[cId]");
			try {			
				$insComp = $db->prepare("INSERT INTO ClientesComponentes 
					SET clientesId = $_POST[cId], dimensionesElemId = :componente, cantidad = :cantidad ");
				$_POST['componentes'] = is_array($_POST['componentes'])?$_POST['componentes']:array();
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

	default:
		break;
}




?>

