<?php
// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];

$Act = $_REQUEST['Act'];



switch ($Act) {
	case 'chk':
		break;
	default:
		if( !empty($_GET['trgtChk']) && is_numeric($_GET['trgtChk'])){
			include 'inicio/tabs.php';
		}else{
			
			include 'inicio/selChk.php';
		}
		break;			
}





?>


