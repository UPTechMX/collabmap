<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$consultation = $db -> query("SELECT t.name as tName, p.name as pName
	FROM Consultations t 
	LEFT JOIN Projects p ON p.id = t.projectsId
	WHERE t.id = $_POST[consultationId]")->fetchAll(PDO::FETCH_ASSOC)[0];

?>
<div class="nuevo"><?php echo TR('consultationInfo'); ?></div>
<div>
	<table class="table">
		<tr>
			<td><?php echo TR('project'); ?>:</td>
			<td><?php echo $consultation['pName']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR('name'); ?>:</td>
			<td><?php echo $consultation['tName']; ?></td>
		</tr>
	</table>
</div>

<div id="consultationsSurveys"><?php include_once 'consultationsSurveys.php'; ?></div>
