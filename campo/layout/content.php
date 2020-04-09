
<?php

// include_once '../seguridad/seguridad.php';

$Act = $_REQUEST['Act'];

switch ($Act) {
	case 'visitas':
		include_once raiz().'campo/visitas/index.php';
		break;
	case 'inst':
		include_once raiz().'campo/instalaciones/index.php';
		break;
	case 'reco':
		include_once raiz().'campo/reconocimientos/index.php';
		break;
	case 'seg':
		include_once raiz().'campo/seguimiento/index.php';
		break;
	default:
		if($nivel == 46)
			include_once raiz().'campo/instalaciones/index.php';
		else
			include_once raiz().'campo/visitas/index.php';
		break;
}


?>


