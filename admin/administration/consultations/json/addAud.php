<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Consultations

	// print2($_POST);

	$ok = true;
	$db->beginTransaction();

	$p['tabla'] = 'ConsultationsAudiences';
	$p['datos']['audiencesId'] = $_POST['audiencesId'];
	$p['datos']['consultationsId'] = $_POST['consultationsId'];
	$p['datos']['dimensionesElemId'] = $_POST['padre'];
	$p['datos']['levelType'] = $_POST['levelType'];

	$stmt = $db->prepare("SELECT COUNT(*) as cuenta FROM ConsultationsAudiences 
		WHERE audiencesId = :audiencesId AND consultationsId = :consultationsId AND 
		dimensionesElemId = :dimensionesElemId AND levelType = :levelType");

	$stmt->execute($p['datos']);
	$cuenta = $stmt ->fetchAll(PDO::FETCH_NUM)[0][0];

	if($cuenta > 0){
		exit('{"ok":"2"}');
	}

	$rj = atj(inserta($p));
	// print2($p);
	// print2($rj);
	$r = json_decode($rj,true);
	if($r['ok'] != 1){
		$ok = false;
		$err = "Err: EICA:TT5465";
	}
	$caId = $r['nId'];

	$stmt = $db->prepare("SELECT * FROM Dimensiones WHERE type = 'audiences' AND elemId = ? ");
	$stmt -> execute(array($_POST['audiencesId']));
	$dims = $stmt->fetchAll(PDO::FETCH_ASSOC);


	if($ok){
		switch ($_POST['levelType']) {
			case '1':
				if($_POST['padre'] != 0){				
					$pc['tabla'] = 'ConsultationsAudiencesCache';
					$pc['datos']['audiencesId'] = $_POST['audiencesId'];
					$pc['datos']['consultationsId'] = $_POST['consultationsId'];
					$pc['datos']['dimensionesElemId'] = $_POST['padre'];
					$pc['datos']['consultationAudiencesId'] = $caId;
					$roj = atj(inserta($pc));
					$ro = json_decode($roj,true);
					if($ro['ok'] != 1){
						$ok = false;
						$err = 'Err: ICAC:4433';
						break;
					}
				}
				break;
			case '3':
				if($_POST['padre'] != 0){				
					$pc['tabla'] = 'ConsultationsAudiencesCache';
					$pc['datos']['audiencesId'] = $_POST['audiencesId'];
					$pc['datos']['consultationsId'] = $_POST['consultationsId'];
					$pc['datos']['dimensionesElemId'] = $_POST['padre'];
					$pc['datos']['consultationAudiencesId'] = $caId;
					$roj = atj(inserta($pc));
					$ro = json_decode($roj,true);
					if($ro['ok'] != 1){
						$ok = false;
						$err = 'Err: ICAC:4433';
						break;
					}
				}
			case '2':
				$stmt = $db->prepare("SELECT de.* 
					FROM DimensionesElem de 
					LEFT JOIN Dimensiones d ON d.id = de.dimensionesId AND type = 'audiences'
					LEFT JOIN Audiences a ON a.id = d.elemId
					WHERE de.padre = ? AND a.id = ?");
				$stmt -> execute(array($_POST['padre'],$_POST['audiencesId']));
				$dimsElems = $stmt -> fetchAll(PDO::FETCH_ASSOC);
				break;

			case '5':
				if($_POST['padre'] != 0){				
					$pc['tabla'] = 'ConsultationsAudiencesCache';
					$pc['datos']['audiencesId'] = $_POST['audiencesId'];
					$pc['datos']['consultationsId'] = $_POST['consultationsId'];
					$pc['datos']['dimensionesElemId'] = $_POST['padre'];
					$pc['datos']['consultationAudiencesId'] = $caId;
					$roj = atj(inserta($pc));
					$ro = json_decode($roj,true);
					if($ro['ok'] != 1){
						$ok = false;
						$err = 'Err: ICAC:4433';
						break;
					}
				}

			case '4':
				// echo "BBB\n";
				if($_POST['padre'] == 0){

					foreach ($dims as $d) {
						$de = $db->query("SELECT * FROM DimensionesElem WHERE dimensionesId = $d[id]")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($de as $d) {
							$dimsElems[] = $d;
						}
					}
				}else{
					$dimsElems = array();
					getOffspring($_POST['padre'],$dimsElems);
					// print2($dimElems);
				}
				break;
			default:
				# code...
				break;
		}
		$dimsElems = empty($dimsElems)?array():$dimsElems;
		// echo "aa\n";
		// print2($dimsElems);
		foreach ($dimsElems as $de) {
			// print2($de);
			$pc['tabla'] = 'ConsultationsAudiencesCache';
			$pc['datos']['audiencesId'] = $_POST['audiencesId'];
			$pc['datos']['consultationsId'] = $_POST['consultationsId'];
			$pc['datos']['dimensionesElemId'] = $de['id'];
			$pc['datos']['consultationAudiencesId'] = $caId;
			// print2($pc);
			$rcj = atj(inserta($pc));
			// print2($rcj);
			$rc = json_decode($rcj,true);

			if($rc['ok'] != 1){
				$ok = false;
				$err = 'Err: EICC: 4922';
				break;
			}
		}

	}

	if($ok){
		$db->commit();
		// $db->rollBack();
		echo '{"ok":1}';
	}else{
		$db->rollBack();
		echo '{"ok":0,"err":"'.$err.'"}';
	}




