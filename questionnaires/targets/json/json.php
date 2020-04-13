<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAccesoQuest();
	$usrId = $_SESSION['CM']['questionnaires']['usrId'];
	// echo "usrId: $usrId\n";
	
	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'TargetsElems';
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
			// echo " // crea visita\n";

			// print2($_POST);
			$p['tabla'] = 'Visitas';
			$p['timestamp'] = 'timestamp';
			$p['datos']['checklistId'] = $_POST['checklistId'];
			$p['datos']['elemId'] = $_POST['targetsElemId'];
			$p['datos']['type'] = 'trgt';

			echo atj(inserta($p));

			break;
		case 4:
			// print2($_POST);
			$exist = $db->query("SELECT COUNT(*) as cuenta 
				FROM TargetsElems 
				WHERE usersId = $usrId AND id = $_POST[teId]
			")->fetchAll(PDO::FETCH_NUM)[0][0];

			if($exist == 0 ){
				exit('{"acc":"null","vId":0}');
			}

			$vis = $db->query("SELECT v.*, f.code as fCode
				FROM Visitas v
				LEFT JOIN TargetsElems te ON te.id = v.elemId
				LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId AND tc.checklistId = v.checklistId
				LEFT JOIN Frequencies f ON f.id = tc.frequency
				WHERE v.type = 'trgt' AND v.elemId = $_POST[teId] AND v.checklistId = $_POST[cId]
				ORDER BY v.timestamp DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC)[0];

			if(empty($vis)){
				$acc = 'newVisita';
				$vId = 0;
			}elseif(empty($vis['finalizada'])){
				$acc = 'contVisita';
				$vId = $vis['id'];
			}elseif($vis['finalizada'] == 1){
				$visDate = date('Y-m-d', strtotime($vis['finishDate']));
				$today = date('Y-m-d');

				switch ($vis['fCode']) {
					case "daily":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 day'));
						break;
					case "weekly":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 week'));
						break;
					case "2weeks":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +2 week'));
						break;
					case "3weeks":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +3 week'));
						break;
					case "monthly":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 month'));
						break;
					case "2months":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +2 month'));
						break;
					case "3months":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +3 month'));
						break;
					case "4months":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +4 month'));
						break;
					case "6months":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +6 month'));
						break;
					case "yearly":
						$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 year'));
						break;
					default:
						# code...
						break;
				}
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

		default:
			# code...
			break;
	}


?>