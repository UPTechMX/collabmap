<?php
	session_start();
	include_once '../../../lib/j/j.func.php';

	checaAcceso(50); // checaAcceso analysis;
	

	switch ($_POST['option']) {
		case 'targets':
			$targets = $db->query("SELECT id as val, name as nom, 'class' as clase 
				FROM Targets WHERE projectsId = $_POST[prjId]")->fetchAll(PDO::FETCH_ASSOC);

			echo atj($targets);

			break;
		case 'checklist':
			$checklist = $db->query("SELECT tc.id as val, c.nombre as nom, 'class' as clase
				FROM TargetsChecklist tc 
				LEFT JOIN Checklist c ON c.id = tc.checklistId
				WHERE tc.targetsId = $_POST[trgtId]")->fetchAll(PDO::FETCH_ASSOC);

			echo atj($checklist);

			break;
		
		default:
			# code...
			break;
	}





?>
