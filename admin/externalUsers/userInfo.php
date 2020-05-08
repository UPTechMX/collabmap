<?php
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(60);// checaAcceso externalUsers

	$usrInfo = $db->query("SELECT * FROM Users WHERE id = $_POST[usrId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($usrInfo);


?>
<div class="nuevo"><?php echo TR('userInfo'); ?></div>
<div>
	<table class="table">
		<tbody>
			<tr>
				<td width="30%"><?php echo TR('phone'); ?></td>
				<td><?php echo $usrInfo['username']; ?></td>
			</tr>
			<tr>
				<td><?php echo TR('name'); ?></td>
				<td><?php echo "$usrInfo[name] $usrInfo[lastname]"; ?></td>
			</tr>
			<!-- <tr>
				<td><?php echo TR('email'); ?></td>
				<td><?php echo "$usrInfo[email]"; ?></td>
			</tr> -->
			<!-- <tr>
				<td><?php echo TR('confirmed'); ?></td>
				<td><?php echo $usrInfo['confirmed'] == 1?TR('yes'):TR('no'); ?></td>
			</tr> -->
			<!-- <tr>
				<td><?php echo TR('validated'); ?></td>
				<td><?php echo $usrInfo['validated'] == 1?TR('yes'):TR('no'); ?></td>
			</tr> -->
		</tbody>
	</table>
</div>

<div>
	<div class="nuevo"><?php echo TR('targets'); ?></div>
	<div id="usrTargets"><?php include_once 'usrTargets.php'; ?></div>
</div>