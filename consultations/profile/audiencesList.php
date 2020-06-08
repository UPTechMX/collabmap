<?php

if (!function_exists('raiz')) {
	include_once '../../lib/j/j.func.php';
}
checaAccesoConsult();// checaAcceso Consultations
session_start();
$usrId = $_SESSION['CM']['consultations']['usrId'];

$audiences = $db->query("SELECT ua.id, de.nombre as deName, d.nombre as dName, a.name as aName, p.name as pName
	FROM UsersAudiences ua
	LEFT JOIN DimensionesElem de ON de.id = ua.dimensionesElemId
	LEFT JOIN Dimensiones d ON d.id = de.dimensionesId
	LEFT JOIN Audiences a ON a.id = d.elemId
	LEFT JOIN Projects p ON p.id = a.projectsId
	WHERE ua.usersId = $usrId")->fetchAll(PDO::FETCH_ASSOC);

// print2($audiences);
?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.delAud').click(function(event) {
			var tr = $(this).closest('tr');
			var audId = tr.attr('id').split('_')[1];

			conf('<?php echo TR("delAud"); ?>',{elemId:audId,tr:tr},function(e){
				var rj = jsonF('consultations/profile/json/json.php',{elemId:e.elemId,acc:3})
				console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					e.tr.remove();
				}

			});



		});
	});
</script>

<table class="table">
	<thead>
		<th><?php echo TR('project'); ?></th>
		<th><?php echo TR('audience'); ?></th>
		<th><?php echo TR('level'); ?></th>
		<th></th>
	</thead>
	<tbody>
		<?php foreach ($audiences as $a){ ?>
			<tr id="trAud_<?php echo $a['id'];?>">
				<td><?php echo $a['pName']; ?></td>
				<td><?php echo $a['aName']; ?></td>
				<td><?php echo $a['deName']; ?></td>
				<td>
					<i class="glyphicon glyphicon-trash manita rojo delAud"></i>
				</td>
			</tr>			
		<?php } ?>
	</tbody>
</table>