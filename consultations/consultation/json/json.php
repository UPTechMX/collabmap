<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAccesoConsult();
	// echo "\nAAA\n";
	$usrId = $_SESSION['CM']['consultations']['usrId'];
	// echo "usrId: $usrId\n";
	
	// print2($_POST);
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = '';
			break;
		case 2:
			$post['tabla'] = '';
			break;
		case 3:
		
		default:
			# code...
			break;
	}

	switch ($_POST['acc']) {
		case 1:
			$post['datos'] = $_POST['datos'];
			if($_POST['opt'] == 1){
				$post['datos']['usersId'] = $usrId;
			}
			// print2($post);
			echo atj(inserta($post));
			break;
		case 2:
			$post['datos'] = $_POST['datos'];
			// print2($post);
			echo atj(upd($post));
			break;	
		case 3:
			$uccPrep = $db->prepare("SELECT ucc.* 
				FROM UsersConsultationsChecklist ucc 
				LEFT JOIN ConsultationsChecklist cc ON cc.id = ucc.consultationsChecklistId
				WHERE cc.checklistId = :checklistId AND cc.consultationsId = :consultationsId AND ucc.usersId = $usrId
			");

			$arr['checklistId'] = $_POST['checklistId'];
			$arr['consultationsId'] = $_POST['consultationId'];
			$uccPrep -> execute($arr);
			$ucc = $uccPrep ->fetchAll(PDO::FETCH_ASSOC)[0];

			if(empty($ucc)){
				// print2($_POST);
				$ccPrep = $db->prepare("SELECT cc.*
					FROM ConsultationsChecklist cc 
					WHERE cc.checklistId = :checklistId AND cc.consultationsId = :consultationsId 
				");

				$ccPrep -> execute($arr);
				$cc = $ccPrep ->fetchAll(PDO::FETCH_ASSOC)[0];
				
				$p['tabla'] = 'UsersConsultationsChecklist';
				$p['datos']['usersId'] = $usrId;
				$p['datos']['consultationsChecklistId'] = $cc['id'];
				$rj = atj(inserta($p));
				// print2($rj);
				$r = json_decode($rj,true);
				
				$uccId = $r['nId'];

			}else{
				$uccId = $ucc['id'];
			}

			
			$pv['tabla'] = 'Visitas';
			$pv['timestamp'] = 'timestamp';
			$pv['datos']['checklistId'] = $_POST['checklistId'];
			$pv['datos']['elemId'] = $uccId;
			$pv['datos']['type'] = 'cons';


			echo atj(inserta($pv));



			break;
		case 4:
			// print2($_POST);

			$vis = $db->query("SELECT v.*, f.code as fCode
				FROM Visitas v
				LEFT JOIN UsersConsultationsChecklist ucc ON v.elemId = ucc.id
				LEFT JOIN ConsultationsChecklist cc ON cc.id = ucc.consultationsChecklistId 
				LEFT JOIN Frequencies f ON f.id = cc.frequency
				WHERE v.type = 'cons' AND ucc.usersId = $usrId AND v.checklistId = $_POST[cId] 
					AND cc.consultationsId = $_POST[consultationId]
				ORDER BY v.finishDate DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC)[0];

			if(empty($vis)){
				$acc = 'newVisita';
				$vId = 0;
			}elseif(empty($vis['finalizada'])){
				$acc = 'contVisita';
				$vId = $vis['id'];
			}elseif($vis['finalizada'] == 1){
				$visDate = date('Y-m-d', strtotime($vis['finishDate']));
				$today = date('Y-m-d');

				$nextDate = getNextDate($vis['fCode'],$vis['finishDate']);
				if ($vis['fCode'] == 'oneTime'){
					$acc = 'seeResults';
					$vId = $vis['id'];
				}elseif($today >= $nextDate){
					$acc = 'newVisita';
					$vId = 0;
				}elseif($today < $nextDate){
					$acc = 'seeResults';
					$vId = $vis['id'];
				}
			}
			echo '{"acc":"'.$acc.'","vId":"'.$vId.'"}';

			break;
		case 5:
			// print2($_POST);
			$dimensionesElemId = $_POST['datos']['dimensionesElemId'];
			if(!is_numeric($dimensionesElemId)){
				exit();
			}
			$count = $db->query("SELECT COUNT(*) FROM TargetsElems 
				WHERE dimensionesElemId = $dimensionesElemId AND usersId = $usrId
			")->fetchAll(PDO::FETCH_NUM)[0][0];

			if($count > 0){
				exit('{"ok":2}');
			}
			$p['tabla'] = 'TargetsElems';
			$p['datos'] = $_POST['datos'];
			$p['datos']['usersId'] = $usrId;

			echo atj(inserta($p));

			break;
		case 6:
			$padre = $_POST['datos']['padre'];
			if(!is_numeric($padre) || !is_numeric($_POST['targetId'])){
				exit('{"ok":0}');
			}
			
			$dimension = $db->query("SELECT d.nivel
				FROM DimensionesElem de
				LEFT JOIN Dimensiones d ON d.id = de.dimensionesId 
				WHERE de.id = $padre")->fetchAll(PDO::FETCH_ASSOC)[0];

			$target = $db->query("SELECT * FROM Targets WHERE id = $_POST[targetId]")->fetchAll(PDO::FETCH_ASSOC)[0];
			$dimensiones = $db->query("SELECT *
				FROM Dimensiones 
				WHERE type = 'structure' AND elemId = $_POST[targetId]
				ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

			$numDim = count($dimensiones);

			if($dimension['nivel'] == $numDim -1 && $target['addStructure'] == 1){
				$p['tabla'] = 'DimensionesElem';
				$p['datos']['padre'] = $padre;
				$p['datos']['nombre'] = $_POST['datos']['nombre'];
				$p['datos']['dimensionesId'] = $dimensiones[$numDim-1]['id'];

				echo atj(inserta($p));

			}else{
				echo '{"ok":2}';
			}

			
			break;

		case 7:

			$db->beginTransaction();
			$p['tabla'] = 'Complaints';
			$p['timestamp'] = 'timestamp';
			$p['datos']['usersId'] = $usrId;
			$p['datos']['description'] = $_POST['description'];
			$p['datos']['consultationsId'] = $_POST['consultationsId'];
			$p['datos']['dimensionesElemId'] = $_POST['padre'];
			$p['datos']['status'] = 10;

			$rj = atj(inserta($p));

			$r = json_decode($rj,true);



			$pp['tabla'] = 'ComplaintsHistory';
			$pp['datos']['complaintsId'] = $r['nId'];
			$pp['datos']['status'] = 10;
			$pp['timestamp'] = 'timestamp';

			// print2($pp);

			echo atj(inserta($pp));


			$db->commit();
			// print2($p);
			break;

		default:
			# code...
			break;
	}


?>