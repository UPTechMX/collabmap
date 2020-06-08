<?php
// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];

$Act = $_REQUEST['acc'];



switch ($Act) {
	case 'trgt':
		if( !empty($_GET['trgtChk']) && is_numeric($_GET['trgtChk'])){
			include 'inicio/tgtTabs.php';
		}else{	
			include 'inicio/selChk.php';
		}
		break;
	case 'cons':
		if( !empty($_GET['consChk']) && is_numeric($_GET['consChk'])){
			include 'consultations/index.php';
		}else{	
			include 'inicio/selChk.php';
		}
		break;
	case 'pc':
		if( !empty($_GET['pcId']) && is_numeric($_GET['pcId'])){
			include 'checklistPC/index.php';
		}else{	
			include 'inicio/selChk.php';
		}
		break;
	case 'hs':
		if( !empty($_GET['trgtId']) && is_numeric($_GET['trgtId'])){
			include 'hotspots/index.php';
		}else{	
			include 'inicio/selChk.php';
		}
		break;
	default:
		include 'inicio/selChk.php';
		break;			
}





?>


