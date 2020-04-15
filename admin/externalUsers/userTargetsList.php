<?php
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(60);// checaAcceso externalUsers

	$usrTrgs = $db->query("SELECT ut.id, t.id as tId, t.name as tName, p.name as pName
		FROM UsersTargets ut 
		LEFT JOIN Targets t ON t.id = ut.targetsId
		LEFT JOIN Projects p ON p.id = t.projectsId
		WHERE ut.usersId = $_POST[usrId]
		ORDER BY p.name, t.name
	") -> fetchAll(PDO::FETCH_ASSOC);

	// print2($usrTrgs);
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.delTrgUsr').click(function(event) {
			var tuId = $(this).closest('td').attr('id').split('_')[1];
			var rj = jsonF('admin/externalUsers/json/json.php',{acc:3,tuId:tuId,opt:2});
			var r = $.parseJSON(rj);;
			if(r.ok == 1){
				$(this).closest('tr').remove();
			}
		});
	});
</script>

<table class="table">
	<thead>
		<tr>
			<th>Project</th>
			<th>Target</th>
			<th></th>
		</tr>
	</thead>
	<tbody id="trgsBody">
		<?php 
		foreach ($usrTrgs as $t){ 
			$count = $db->query("SELECT COUNT(*) as count 
				FROM TargetsElems WHERE usersId = $_POST[usrId] AND targetsId = $t[tId]")->fetchAll(PDO::FETCH_NUM)[0][0];
		?>
			<tr id="trUsrTrg_<?php echo $t['tId']; ?>">
				<td><?php echo $t['pName']; ?></td>
				<td><?php echo $t['tName']; ?></td>
				<td id="tdUsrTrg_<?php echo $t['id']; ?>">

					<?php if ($count == 0){ ?>
						<i class="glyphicon glyphicon-trash manita rojo delTrgUsr"></i>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
