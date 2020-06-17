	
<?php

// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];
$Act = $_REQUEST['Act'];

switch ($Act) {
	case 'tutorials':
		// if($nivel >= 50)
		include_once raiz().'questionnaires/home/tutorials.php';
		break;
	default:
		include_once raiz().'questionnaires/targets/index.php';
		break;			
}





?>


