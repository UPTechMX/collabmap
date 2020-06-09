<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(10);// checaAcceso complaints

	// print2($_POST);
	// $today = date('Y-m-d H:i:s');
	// print2($today);


	$complaint = $db->query("SELECT c.*, e.name as eName, con.name as conName, de.nombre as deName, 
		ua.name as uaName, ua.lastname as uaLastname
		FROM Complaints c
		LEFT JOIN usrAdmin ua ON ua.id = adminId
		LEFT JOIN Consultations con ON con.id = c.consultationsId
		LEFT JOIN DimensionesElem de ON de.id = c.dimensionesElemId
		LEFT JOIN Estatus e ON c.status = e.code AND e.tabla = 'complaints'
		WHERE c.id = $_POST[complaintsId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	
	$sql = "SELECT c.*, e.name as eName, 
		ua.name as uaName, ua.lastname as uaLastname
		FROM ComplaintsHistory c
		LEFT JOIN usrAdmin ua ON ua.id = adminId
		LEFT JOIN Estatus e ON c.status = e.code AND e.tabla = 'complaints'
		WHERE c.complaintsId = $_POST[complaintsId]
		ORDER BY c.timestamp";
	// print2($sql);
	$complaintHistory = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#updateComplaint').click(function(event) {
			var complaintsId = <?php echo $_POST['complaintsId']; ?>;
			popUp('admin/complaints/complaintsUpd.php',{complaintsId:complaintsId});
		});
	});
</script>

<div class="nuevo"><?php echo TR('complaint'); ?></div>
<div>
	<table class="table">
		<tr>
			<td><?php echo TR("consultation"); ?></td>
			<td><?php echo $complaint['conName']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR("date"); ?></td>
			<td><?php echo $complaint['timestamp']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR("area"); ?></td>
			<td><?php echo $complaint['deName']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR("description"); ?></td>
			<td><?php echo $complaint['description']; ?></td>
		</tr>
		<tr>
			<td><?php echo TR("status"); ?></td>
			<td><?php echo TR($complaint['eName']); ?></td>
		</tr>
		<tr>
			<td><?php echo TR("comment"); ?></td>
			<td><?php echo $complaint['comment']; ?></td>
		</tr>
	</table>
	<div style="margin: 10px;text-align: center;">
		<span class="btn btn-shop" id="updateComplaint"><?php echo TR('update'); ?></span>
	</div>
</div>

<div class="nuevo"><?php echo TR('history'); ?></div>
<table class="table">
	<thead>
		<tr>
			<td><?php echo TR('date'); ?></td>
			<td><?php echo TR('user'); ?></td>
			<td><?php echo TR('status'); ?></td>
			<td><?php echo TR('comment'); ?></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($complaintHistory as $ch){ ?>
			<tr>
				<td><?php echo $ch['timestamp']; ?></td>
				<td><?php echo "$ch[uaName] $ch[uaLastname]" ?></td>
				<td><?php echo TR($ch['eName']); ?></td>
				<td><?php echo $ch['comment']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>









