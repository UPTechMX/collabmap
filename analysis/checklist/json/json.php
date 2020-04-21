<?php

include_once '../../../lib/j/j.func.php';

switch ($_POST['acc']) {
	case 9:
		// print2($_POST);
		$sas = $db->query("SELECT sa.id, sa.id as saId, p.*
			FROM Studyarea sa
			LEFT JOIN StudyareaPoints p ON p.studyareaId = sa.id
			WHERE preguntasId = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

		echo atj($sas);
		break;


	default:
		# code...
		break;
}




?>

