<?php
	session_start();
	include_once '../../../lib/j/j.func.php';

	checaAcceso(5); // checaAcceso analysis;
	

	switch ($_POST['option']) {
		case 'targets':
			$targets = $db->query("SELECT t.id as val, t.name as nom, 'class' as clase 
				FROM Targets t
				LEFT JOIN (SELECT elemId, COUNT(*) as cuenta 
					FROM Dimensiones d WHERE d.type = 'structure' GROUP BY elemId) dd ON dd.elemId = t.id
				WHERE projectsId = $_POST[prjId] AND dd.cuenta > 0")->fetchAll(PDO::FETCH_ASSOC);

			echo atj($targets);

			break;
		case 'consultations':
			$targets = $db->query("SELECT t.id as val, t.name as nom, 'class' as clase 
				FROM Consultations t
				WHERE projectsId = $_POST[prjId]")->fetchAll(PDO::FETCH_ASSOC);

			echo atj($targets);

			break;
		case 'checklist':
			$checklist = $db->query("SELECT tc.id as val, c.nombre as nom, 'class' as clase
				FROM TargetsChecklist tc 
				LEFT JOIN Checklist c ON c.id = tc.checklistId
				WHERE tc.targetsId = $_POST[trgtId]")->fetchAll(PDO::FETCH_ASSOC);

			echo atj($checklist);

			break;
		case 'checklistCons':
			$checklist = $db->query("SELECT tc.id as val, c.nombre as nom, 'class' as clase
				FROM ConsultationsChecklist tc 
				LEFT JOIN Checklist c ON c.id = tc.checklistId
				WHERE tc.consultationsId = $_POST[consId]")->fetchAll(PDO::FETCH_ASSOC);

			echo atj($checklist);

			break;
		
		default:
			# code...
			break;
	}





?>
