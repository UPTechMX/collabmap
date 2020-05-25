<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Consultations

	print2($_POST);

	switch ($_POST['levelType']) {
		case '1':
			if($_POST['padre'] == 0){
				
			}
			$stmt = $db->prepare("SELECT * FROM ConsultationsAudiences 
				WHERE consultationsId = ? AND dimensionesElemId = ?");
			$stmt -> execute(array($_POST['consultationsId'],$_POST['padre']));

			break;
		
		default:
			# code...
			break;
	}