<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(10);// checaAcceso complaints

	$compG = $db -> query("SELECT e.code as st, c.*, e.name as eName, con.name as conName, de.nombre as deName
		FROM Complaints c
		LEFT JOIN Consultations con ON con.id = c.consultationsId
		LEFT JOIN DimensionesElem de ON de.id = c.dimensionesElemId
		LEFT JOIN Estatus e ON e.code = c.status AND e.tabla = 'Complaints'
		ORDER BY  con.name, c.timestamp
	")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$status = $db->query("SELECT * FROM Estatus WHERE tabla = 'Complaints' ORDER BY code")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.seeComplaint').click(function(event) {
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(cId);
			$('#complaintsInfo').load(rz+'admin/complaints/complaintsInfo.php',{complaintsId:cId});
		});
	});
</script>

<div class="nuevo"><?php echo TR("complaints"); ?></div>

<?php 
foreach ($status as $s ){ 
	switch ($s['name']) {
		case 'received':
			$class = 'quejaRec';
			break;
		case 'read':
			$class = 'quejaRead';
			break;
		case 'channeled':
			$class = 'quejaChanneled';
			break;
		case 'attended':
			$class = 'quejaAttended';
			break;
		
		default:
			$class = 'azul';
			break;
	}

?>
	<div class="nuevo <?= $class; ?>" style="margin-top: 10px;">
		<?php echo TR($s['name']); ?>
		<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
			data-toggle="tooltip" data-placement="right" title="<?= TR($s['name'].'Tooltip') ?>"></i>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th><?php echo TR('consultation'); ?></th>
				<th><?php echo TR('date'); ?></th>
				<th><?php echo TR('area'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody id="tbodyComp_<?php echo $s['code'] ?>">
			<?php 
			$compG[$s['code']] = is_array($compG[$s['code']])?$compG[$s['code']]:array();
			foreach ($compG[$s['code']] as $c){ 
			?>
				<tr id="trComp_<?php echo $c['id'];?>">
					<td><?php echo $c['conName']; ?></td>
					<td><?php echo $c['timestamp']; ?></td>
					<td><?php echo $c['deName']; ?></td>
					<td>
						<i class="glyphicon glyphicon-eye-open manita seeComplaint"></i>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } ?>
