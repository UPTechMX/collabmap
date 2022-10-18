<?php
session_start();
include '../j/j.func.php';

switch ($_POST['rutaId']) {
	case 1:
		$dir = 'chkPhotos';
		break;
	case 2:
		$dir = 'problemsPhotos';
		break;
	case 3:
		$dir = 'externalFiles';
		break;
	case 4:
		$dir = 'kml';
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