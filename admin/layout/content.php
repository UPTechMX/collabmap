	
<?php

// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];
$Act = $_REQUEST['Act'];

switch ($Act) {
	case 'usrInt':
		if($nivel >= 60)
			include_once raiz().'admin/administration/usuarios/usrInt.php';
		break;
	case 'trg':
		if($nivel >= 50)
			include_once raiz().'admin/administration/targets/index.php';
		break;
	case 'prjs':
		if($nivel >= 50)
			include_once raiz().'admin/administration/projects/index.php';
		break;
	case 'extUsr':
		if($nivel >= 50)
			include_once raiz().'admin/externalUsers/index.php';
		break;
	case 'chk':
		if($nivel >= 50)
			include_once raiz().'admin/checklist/index.php';
		break;
	default:
		// include_once raiz().'admin/proyectos/index.php';
		break;			
}





?>


