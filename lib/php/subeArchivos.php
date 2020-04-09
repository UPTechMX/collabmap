<?php
session_start();
include '../j/j.func.php';

switch ($_POST['rutaId']) {
	case 1:
		$dir = 'campo/archivosCuest';
		break;
	case 2:
		$dir = 'img/checklist';
		break;
	case 3:
		$dir = 'admin/administracion/vehiculos/img';
		break;
	case 4:
		$dir = 'archivos';
		break;
	case 5:
		$dir = 'img/logos';
		break;
	case 6:
		$dir = 'videosCap';
		break;
	case 7:
		$dir = 'admin/administracion/equipos/archivos';
		break;
	case 8:
		$dir = 'archivos/tmp';
		break;
	case 9:
		$dir = 'archivos/imgPry';
		break;
	case 10:
		$dir = 'archivos/ubicaciones/foto';
		break;
	case 11:
		$dir = 'archivos/lideres/foto';
		break;
	case 12:
		$dir = 'archivos/juntas/foto';
		break;
	case 13:
		$dir = 'archivos/imports';
		break;
	
	default:
		# code...
		break;
}
if($_POST['borraArchivo']==1){
	unlink ($_POST['archivo']);
}else{

	if($_POST['evitarNombre'] == "true")
		echo subearchivos($_POST['prefijo'],$_FILES,$dir, $_POST['evitarNombre']);
	else
		echo subearchivos($_POST['prefijo'],$_FILES,$dir);
	
}
?>