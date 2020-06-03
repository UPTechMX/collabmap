<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(10);// checaAcceso complaints
	$usrId =  $_SESSION['CM']['admin']['usrId'];

	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = '';
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
			$today = date('Y-m-d H:i:s');
			// print2($today);
			$db->beginTransaction();
			$ok = true;
			$p['tabla'] = "Complaints";
			$p['datos'] = $_POST['datos'];
			$p['datos']['adminId'] = $usrId;
			$p['datos']['reviewDate'] = $today;
			$rp = json_decode(atj(upd($p)),true);

			if($rp['ok'] == 1){

				$pp['tabla'] = "ComplaintsHistory";
				$pp['timestamp'] = "timestamp";
				$pp['datos']['complaintsId'] = $_POST['datos']['id'];
				$pp['datos']['status'] = $_POST['datos']['status'];
				$pp['datos']['comment'] = $_POST['datos']['comment'];
				$pp['datos']['adminId'] = $usrId;

				$rpp = json_decode(atj(inserta($pp)),true);

				if($rpp['ok'] != 1){
					$ok = false;
					$err = $rpp['err'];

				}

			}else{
				$ok = false;
				$err = $rp['err'];
			}
			
			if($ok){
				$db->commit();
				echo '{"ok":1}';
			}else{
				$db->rollBack();
				echo '{"ok":0,"err":"'.$err.'"}';
			}


			break;	

		default:
			# code...
			break;
	}


?>