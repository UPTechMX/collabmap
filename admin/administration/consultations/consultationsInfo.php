<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$consultation = $db -> query("SELECT c.name as tName, p.name as pName, p.id as pId, c.id as id, c.initDate, c.finishDate
	FROM Consultations c 
	LEFT JOIN Projects p ON p.id = c.projectsId
	WHERE c.id = $_POST[consultationId]")->fetchAll(PDO::FETCH_ASSOC)[0];

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
		<tr>
			<td><?php echo TR('initDate'); ?>:</td>
			<td><?php echo $consultation['initDate']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR('finishDate'); ?>:</td>
			<td><?php echo $consultation['finishDate']; ?></td>
		</tr>
	</table>
</div>

<div id="consultationsSurveys"><?php include_once 'consultationsSurveys.php'; ?></div>
<div id="consultationsAudiences"><?php include_once 'consultationsAudiences.php'; ?></div>