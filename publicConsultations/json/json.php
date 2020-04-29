<?php

	include '../../lib/j/j.func.php';

	$stmt = $db->prepare("SELECT * FROM PublicConsultations WHERE code = ?");
	$stmt -> execute([$_POST['code']]);

	$pcInfo = $stmt ->fetchAll(PDO::FETCH_ASSOC)[0];

	$ok = true;
	
	if(empty($pcInfo)){
		exit('{"ok":0,"err":"EPTPC001"}');
	}

	switch ($_POST['acc']) {
		case '1':
			if($pcInfo['emailReq'] == 1 && $pcInfo['oneAns'] == 1){
				$stmt = $db->prepare("SELECT COUNT(*) as cuenta
					FROM Visitas v
					LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
					LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
					WHERE v.type = 'pubC' AND pc.id = $pcInfo[id] AND pcu.email = ? AND v.finalizada = 1
					");

				$stmt -> execute([$_POST['email']]);
				$c = $stmt -> fetchAll(PDO::FETCH_NUM)[0][0];

				if($c>0){
					exit('{"ok":2}');
				}else{
					
					$stmt = $db->prepare("SELECT v.id
						FROM Visitas v
						LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
						LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
						WHERE v.type = 'pubC' AND pc.id = $pcInfo[id] AND pcu.email = ? AND v.finalizada IS NULL
					");

					$stmt -> execute([$_POST['email']]);
					$vId = $stmt -> fetchAll(PDO::FETCH_NUM)[0][0];
					// print2($vId);
					if(!empty($vId)){
						exit('{"ok":"1","vId":"'.$vId.'"}');
					}
				}
			}

			$p['tabla'] = 'PublicConsultationsUsers';
			$p['datos']['email'] = $_POST['email'];
			$p['datos']['publicConsultationsId'] = $pcInfo['id'];
			$p['timestamp'] = 'timestamp';

			$prj = atj(inserta($p));
			$pr = json_decode($prj,true);
			if($pr['ok'] != 1){
				$ok = false;
				$err= "ERIPCUx4553";
			}
			if($ok){
				$v['tabla'] = 'Visitas';
				$v['timestamp'] = 'timestamp';
				$v['datos']['type'] = 'pubC';
				$v['datos']['elemId'] = $pr['nId'];
				$v['datos']['checklistId'] = $pcInfo['checklistId'];

				$vrj = atj(inserta($v));
				$vr = json_decode($vrj,true);

				if($vr['ok'] != 1){
					$ok = false;
					$err = "EIVPCx5344";
				}
			}

			if($ok){
				echo '{"ok":1,"vId":"'.$vr['nId'].'"}';
			}else{
				echo '{"ok":0,"err":"'.$err.'"}';
			}
			// print2($_POST);
			// print2($pcInfo);
			break;
		
		default:
			# code...
			break;
	}


?>