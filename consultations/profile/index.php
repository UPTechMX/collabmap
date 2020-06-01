<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	checaAccesoConsult();
	$usrId = $_SESSION['CM']['consultations']['usrId'];

	$usrInfo = $db->query("SELECT * FROM Users WHERE id = $usrId")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($usrInfo);
?>

<div style="margin-top: 50px;">
	<div class="nuevo"><?php echo TR('userdata'); ?></div>
	<div class="row">
		<div class="col-md-6">
			<div class="nuevo"><?php echo TR('generaldata'); ?></div>
			<form>
				<table class="table">
					<tr>
						<td><?php echo TR('username'); ?></td>
						<td><?php echo $usrInfo['username']; ?></td>
					</tr>
					<tr>
						<td><?php echo TR('name'); ?></td>
						<td>
							<input type="text" name="name" id="name" class="form-control oblig" value="<?php echo $usrInfo['name']; ?>">
						</td>
					</tr>
					<tr>
						<td><?php echo TR('last_name'); ?></td>
						<td>
							<input type="text" name="lastname" id="lastname" class="form-control oblig" value="<?php echo $usrInfo['lastname']; ?>">
						</td>
					</tr>
					<tr>
						<td><?php echo TR('email'); ?></td>
						<td>
							<input type="text" name="email" id="email" class="form-control oblig" value="<?php echo $usrInfo['email']; ?>">
						</td>
					</tr>
					<tr>
						<td><?php echo TR('age'); ?></td>
						<td>
							<input type="text" name="age" id="age" class="form-control oblig" value="<?php echo $usrInfo['age']; ?>">
						</td>
					</tr>
					<tr>
						<td><?php echo TR('gender'); ?></td>
						<td>
							<select class="form-control" name="gender" id="gender">
								<option value="F" <?php echo $usrInfo['gender'] == "F"?'selected':'' ?> >
									<?php echo TR('female'); ?>
								</option>
								<option value="M" <?php echo $usrInfo['gender'] == "M"?'selected':'' ?> >
									<?php echo TR('male'); ?>
								</option>
							</select>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="col-md-6" id="audiences"><?php include 'audiences.php'; ?></div>
	</div>
</div>