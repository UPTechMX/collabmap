<?php

include_once '../../../lib/j/j.func.php';

switch ($_POST['acc']) {
	case 9:
		// print2($_POST);
		$sas = $db->query("SELECT sa.id, ST_AsGeoJSON(sa.geometry) as geometry
			FROM Studyarea sa
			WHERE preguntasId = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC);

		echo atj($sas);
		break;


	default:
		# code...
		break;
}




?>

