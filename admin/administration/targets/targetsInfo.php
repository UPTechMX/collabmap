<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(50);// checaAcceso Targets

$target = $db -> query("SELECT t.name as tName, p.name as pName, t.addStructure
	FROM Targets t 
	LEFT JOIN Projects p ON p.id = t.projectsId
	WHERE t.id = $_POST[targetId]")->fetchAll(PDO::FETCH_ASSOC)[0];

?>
<div class="nuevo titleL3Bkg"><?php echo TR('targetInfo'); ?></div>
<div>
	<table class="table">
		<tr>
			<td><?php echo TR('project'); ?>:</td>
			<td><?php echo $target['pName']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR('name'); ?>:</td>
			<td><?php echo $target['tName']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR('addStructure'); ?>:</td>
			<td><?php echo $target['addStructure'] == 1?TR('yes'):TR('no'); ?></td>
		</tr>
	</table>
</div>

<div id="targetsSurveys"><?php include_once 'targetsSurveys.php'; ?></div>
