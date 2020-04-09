<?php  

	include_once '../j/j.func.php';

	$idRep = $_GET['id'];

	if ( isset($_GET['id'])) {
		$db->query("DELETE FROM tBnms WHERE repeticionesId = $idRep");
		echo 'Listo';
	}else{
		echo 'NO HAY IDENTIFICADOR DE LA REPETICION';
	}




?>