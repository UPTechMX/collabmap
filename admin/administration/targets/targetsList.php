<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Targets


$elementos = $db->query("SELECT t.*, p.name as pName
	FROM Targets t
	LEFT JOIN Projects p ON t.projectsId = p.id
	WHERE (p.inactive IS NULL OR p.inactive = 0)
	ORDER BY p.name, t.name") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#targetsTable .edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(eleId);
			popUp('admin/administration/targets/targetsAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administration/financiadores/financiadoresAdd.php',{eleId:eleId})
		});

		$(".trgtInfo").click(function(event) {
			var targetId = $(this).closest('tr').attr('id').split('_')[1];
			$('#targetsInfo').load(rz+'admin/administration/targets/targetsInfo.php',{targetId:targetId});
			$('#targetsStructure').load(rz+'admin/administration/targets/targetsStructure.php',{targetsId:targetId});

			$(this).closest('table').find('tr').removeClass('seleccionado');
			$(this).closest('tr').addClass('seleccionado');

			
		});
	});
</script>

<table class="table" id="targetsTable">
	<thead>
		<tr>
			<th>Project</th>
			<th>Name</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="fond_<?php echo $e['id']; ?>">
				<td><?php echo $e['pName']; ?></td>
				<td><?php echo $e['name']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-pencil manita edtEle"></i></td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-th manita trgtInfo"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>