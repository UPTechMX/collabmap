<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Projects

	$project = $db -> query("SELECT *
		FROM Projects p 
		WHERE p.id = $_POST[prjId]")->fetchAll(PDO::FETCH_ASSOC)[0];


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#importar').click(function(event) {
			var prjId = <?php echo $_POST['prjId']; ?>;
			popUp('admin/administration/projects/importKML.php',{prjId:prjId});
		});
	});
</script>

<div class="nuevo"><?php echo $project['name']; ?></div>
<div>
	<table class="table">
		<tr>
			<td><?php echo TR('project'); ?>:</td>
			<td><?php echo $project['name']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR('inactive'); ?>:</td>
			<td><?php echo $project['inactive'] == 1?TR('yes'):TR('no'); ?></td>
		</tr>
	</table>
</div>

<div>
	<div class="nuevo"><?php echo TR('maps'); ?></div>
	<div style="text-align: right;">
		<span class="btn btn-shop" id="importar"><?php echo TR('importKML'); ?></span>
	</div>
	<div id="KMLlist" style="margin-top: 10px;"><?php include_once  'KMLlist.php'; ?></div>
</div>