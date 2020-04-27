<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Public consultations

$pcInfo = $db->query("SELECT pc.*, p.name as pName
	FROM PublicConsultations pc
	LEFT JOIN Projects p ON p.id = pc.projectsId
	WHERE pc.id = $_POST[pcId]")->fetchAll(PDO::FETCH_ASSOC)[0];

?>

<div class="nuevo"><?php echo TR('information'); ?></div>

<table class="table">
	<tr>
		<td><?php echo TR('name'); ?></td>
		<td><?php echo $pcInfo['name']; ?></td>
	</tr>
	<tr>
		<td><?php echo TR('code'); ?></td>
		<td><?php echo $pcInfo['code']; ?></td>
	</tr>
	<?php if (!empty($pcInfo['pName'])){ ?>
		<tr>
			<td><?php echo TR('project'); ?></td>
			<td><?php echo $pcInfo['pName']; ?></td>
		</tr>
	<?php } ?>
	<tr>
		<td><?php echo TR('emailReq'); ?></td>
		<td><?php echo $pcInfo['emailReq'] == 1?TR('yes'):TR('no'); ?></td>
	</tr>
	<tr>
		<td><?php echo TR('oneAns'); ?></td>
		<td><?php echo $pcInfo['multAns'] == 1?TR('yes'):TR('no'); ?></td>
	</tr>
</table>