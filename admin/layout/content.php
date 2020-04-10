	
<?php

// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];
$Act = $_REQUEST['Act'];

switch ($Act) {
	case 'usrInt':
		if($nivel >= 60)
			include_once raiz().'admin/administracion/usuarios/usrInt.php';
		break;
	case 'financ':
		if($nivel >= 50)
			include_once raiz().'admin/administracion/financiadores/index.php';
		break;
	case 'rec':
		if($nivel >= 49)
			include_once raiz().'admin/administracion/reconocimientos/index.php';
		break;
	case 'inst':
		if($nivel >= 50)
			include_once raiz().'admin/administracion/instalaciones/index.php';
		break;
	case 'veh':
		if($nivel >= 50)
			include_once raiz().'admin/administracion/vehiculos/index.php';
		break;
	case 'admPry':
		if($nivel >= 50)
			include_once raiz().'admin/administracion/proyectos/index.php';
		break;
	case 'equ':
		if($nivel >= 50)
			include_once raiz().'admin/administracion/equipos/index.php';
		break;
	case 'chk':
		if($nivel >= 50)
			include_once raiz().'admin/checklist/index.php';
		break;
	case 'cte':
		if($nivel >= 49)
			include_once raiz().'general/clientes/index.php';
		break;
	case 'pReg':
		if($nivel >= 49)
			include_once raiz().'general/preregistro/index.php';
		break;
	case 'pry':
		if($nivel >= 50)
			include_once raiz().'admin/proyectos/index.php';
		break;
	case 'ubics':
		if($nivel >= 50)
			include_once raiz().'general/ubicaciones/index.php';
		break;
	case 'lideresCom':
		if($nivel >= 50)
			include_once raiz().'general/ubicaciones/indexLideres.php';
		break;
	case 'juntasCom':
		if($nivel >= 50)
			include_once raiz().'general/juntasComunitarias/index.php';
		break;
	case 'mapaStatus':
		if($nivel >= 49)
			include_once raiz().'general/ubicaciones/vistaStatus.php';
		break;
	case 'analisis':
		if($nivel >= 49)
			include_once raiz().'admin/analisis/index.php';
		break;
	case 'dwls':
		if($nivel >= 50)
			include_once raiz().'admin/descargas/index.php';
		break;
	default:
		// include_once raiz().'admin/proyectos/index.php';
		break;			
}





?>


