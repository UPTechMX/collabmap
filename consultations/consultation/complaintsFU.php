<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}
	$usrId = $_SESSION['CM']['consultations']['usrId'];
	$complaints = $db->query("
		SELECT c.*, e.name as eName, de.nombre as deName, con.name as conName 
		FROM Complaints c
		LEFT JOIN Consultations con ON con.id = c.consultationsId
		LEFT JOIN Estatus e ON e.code = c.status AND e.tabla = 'Complaints'
		LEFT JOIN DimensionesElem de ON de.id = c.dimensionesElemId
		WHERE usersId = $usrId
		ORDER BY con.name, c.timestamp
	")->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="modal-header nuevo" style="background-color: #e80000;">
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('complaintsFU'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<table class="table">
		<thead>
			<tr>
				<th><?php echo TR('consultation'); ?></th>
				<th><?php echo TR('area'); ?></th>
				<th><?php echo TR('description'); ?></th>
				<th><?php echo TR('status'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($complaints as $c){ ?>
				<tr>
					<td><?php echo $c['conName']; ?></td>
					<td><?php echo $c['deName']; ?></td>
					<td><?php echo $c['description']; ?></td>
					<td><?php echo TR($c['eName']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop"><?php echo TR('ok'); ?></span>
	</div>
</div>
