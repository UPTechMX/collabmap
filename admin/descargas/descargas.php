<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

	include_once raiz().'lib/php/usrInt.php';
	$usrId = $_SESSION['CM']['admin']['usrId'];
	$usr = new Usuario($usrId);

	// print2($_POST);
	if(!is_numeric($_POST['pryId']) && $_POST['pryId'] != ''){
		exit();
	}
	$pryId = $_POST['pryId'];

	switch ($_POST['tipoRep']) {
		case 1:
			include_once 'tiposReporte/califShoppers.php';
			break;
		case 2:
			include_once 'tiposReporte/shoppers.php';
			break;
		case 3:
			include_once 'tiposReporte/cancelaciones.php';
			break;
		case 4:
			include_once 'tiposReporte/asignaciones.php';
			break;
		case 5:
			include_once 'tiposReporte/facturaciones.php';
			break;
		case 6:
			include_once 'tiposReporte/visFaltantes.php';
			break;
		case 7:
			include_once 'tiposReporte/costos.php';
			break;
		case 8:
			include_once 'tiposReporte/faltaRecibir.php';
			break;
		
		default:
			# code...
			break;
	}
	// print2($_POST);

?>
