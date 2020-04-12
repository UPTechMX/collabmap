<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Targets

// print2($_POST);
$targetsChecklist = $db->query("SELECT c.nombre, c.id as cId, tc.id as tcId, tc.frequency
	FROM TargetsChecklist tc 
	LEFT JOIN Checklist c ON c.id = tc.checklistId

	WHERE tc.targetsId = $_POST[targetId]
	ORDER BY tc.frequency")->fetchAll(PDO::FETCH_ASSOC);

	// print2($targetsChecklist);
	$freqNames['0']= TR('oneTime');
	$freqNames['1']= TR('daily');
	$freqNames['2']= TR('weekly');
	$freqNames['3']= TR('2weeks');
	$freqNames['4']= TR('3weeks');
	$freqNames['5']= TR('monthly');
	$freqNames['6']= TR('2months');
	$freqNames['7']= TR('3months');
	$freqNames['8']= TR('4months');
	$freqNames['9']= TR('6months');
	$freqNames['10']= TR('yearly');

?>

<table class="table" style="margin-top: 10px;">
	<thead>
		<tr>
			<th><?php echo TR('survey'); ?></th>
			<th><?php echo TR('frequency'); ?></th>
			<th></th>
		</tr>
	</thead>
	<body>
		<?php 
		foreach ($targetsChecklist as $tc){ 
			// $cuenta = $db->query("SELECT COUNT(*) 
			// 	FROM Visitas v
			// 	LEFT JOIN TargetsElems te ON te.id = v.elemId
			// 	LEFT JOIN TargetsChecklist tc ON tc.targetId = te.targetId
			// 	WHERE  
			// ")->fetchAll(PDO::FETCH_NUM)[0][0];
		?>
			<tr id="trTC_<?php echo $tc['cId'] ?>">
				<td><?php echo $tc['nombre']; ?></td>
				<td><?php echo $freqNames[$tc['frequency']]; ?></td>
				<td>
					<i class="glyphicon glyphicon-trash rojo manita" id="trashTC_<?php echo $tc['tcId']; ?>"></i>
				</td>
			</tr>
		<?php } ?>
	</body>
</table>
