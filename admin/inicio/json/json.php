<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/usrInt.php';
checaAcceso(50);

$usrId = $_SESSION['IU']['admin']['usrId'];
$usr = new Usuario($usrId);


switch ($_POST['opt']) {
	case '1':
		$post['tabla'] = 'usrAdmin';
		$post['datos']['pwd'] = encriptaUsr($_POST['pwd']);
		$post['datos']['id'] = $usrId;
		// print2($post);

		break;
	default:
		# code...
		break;
}


switch ($_POST['acc']) {
	case 1:
		
		$rep = $db->query("SELECT * FROM Repeticiones WHERE id = $_POST[repId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		$rotTotales = $usr->getRotTotales($_POST['repId']);
		// print2($rep);
		// print2($rotTotales);

		$rotTotales['fechaIni'] = $rep['fechaIni'];
		$rotTotales['fechaFin'] = $rep['fechaFin'];
		$rotTotales['fechaMax'] = $rep['fechaMax'];
		$rotTotales['fechaMaxFact'] = $rep['fechaMaxFact'];
		$rotTotales['publica'] = $rep['publica'] == 1 ? 'Sí':'No';

		echo atj($rotTotales);
		break;

	case '2':
		// print2($post);
		echo atj(upd($post));
		break;	

	
	default:
		# code...
		break;
}


?>