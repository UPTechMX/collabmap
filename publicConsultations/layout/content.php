	
<?php

// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];
$Act = $_REQUEST['Act'];

switch ($Act) {
	case 'chk':
		// if($nivel >= 50)
		// 	include_once raiz().'admin/checklist/index.php';
		break;
	default:
		include_once raiz().'questionnaires/targets/index.php';
		break;			
}





?>


