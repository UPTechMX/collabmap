<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCache.php';


// print2($_POST);

switch ($_POST['busq']) {
	case 'areas':
		
		$areas = $db->query("SELECT a.id as val, a.nombre as nom, a.identificador as clase
			FROM Areas a
			LEFT JOIN Bloques b ON b.id = a.bloquesId
			LEFT JOIN Checklist chk ON b.checklistId = chk.id
			LEFT JOIN ProyectosChecklist pc ON pc.checklistId = chk.id

			WHERE b.identificador = '$_POST[bloque]' AND pc.proyectosId = $_POST[proyectoId] 
			AND (a.elim IS NULL OR a.elim != 1)
			GROUP BY a.identificador
			ORDER BY a.orden")->fetchAll(PDO::FETCH_ASSOC);

		echo atj($areas);

		break;
	case 'checklist':
		// print2($_POST);
		$areas = $db->query("SELECT c.id as val, c.nombre as nom, etapa as clase
			FROM Checklist c
			LEFT JOIN ProyectosChecklist pc ON pc.checklistId = c.id
			WHERE pc.proyectosId = $_POST[proyectoId]")->fetchAll(PDO::FETCH_ASSOC);

		echo atj($areas);

		break;
	case 'bloques':
		// print2($_POST);
		$areas = $db->query("SELECT b.nombre as nom, b.identificador as clase, b.id as val
			FROM Bloques b
			WHERE b.checklistId = $_POST[cuestionarioId]")->fetchAll(PDO::FETCH_ASSOC);

		echo atj($areas);

		break;
	case 'preguntas':
		
		$areas = $db->query("SELECT p.id as val, p.pregunta as nom, p.identificador as clase
			FROM Preguntas p
			LEFT JOIN Tipos t ON t.id = p.tiposId
			LEFT JOIN Areas a ON a.id = p.areasId
			LEFT JOIN Bloques b ON b.id = a.bloquesId
			LEFT JOIN Checklist chk ON b.checklistId = chk.id
			LEFT JOIN ProyectosChecklist pc ON pc.checklistId = chk.id
			LEFT JOIN Preguntas sa ON sa.id = p.subareasId
			WHERE a.identificador = '$_POST[area]' AND pc.proyectosId = $_POST[proyectoId] 
			AND (t.siglas = 'mult') AND (p.elim IS NULL OR p.elim != 1) AND (sa.elim IS NULL OR sa.elim != 1)
			GROUP BY p.identificador
			ORDER BY p.orden")->fetchAll(PDO::FETCH_ASSOC);

		echo atj($areas);

		break;
	
	default:
		# code...
		break;
}

?>