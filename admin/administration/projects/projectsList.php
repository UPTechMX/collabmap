<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(50);// checaAcceso Projects


$elementos = $db->query(" SELECT * FROM Projects ORDER BY name") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#projectsTable .edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(eleId);
			popUp('admin/administration/projects/projectsAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administration/financiadores/financiadoresAdd.php',{eleId:eleId})
		});
		$('#projectsTable .prjInfo').click(function(event) {
			var prjId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(prjId);
			$('#projectsInfo').load(rz+'admin/administration/projects/projectsInfo.php',{prjId:prjId});
			
			$(this).closest('table').find('tr').removeClass('seleccionado');
			$(this).closest('tr').addClass('seleccionado');
			
		});

	});
</script>
<div class="nuevo rojoBkg">
	<?= TR('projectList'); ?>
</div>
<table class="table" id="projectsTable">
	<thead class="rojoBorderBottom">
		<tr>
			<th>Name</th>
			<th>Code</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="fond_<?php echo $e['id']; ?>">
				<td><?php echo $e['name']; ?></td>
				<td><?php echo $e['code']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-pencil manita edtEle"></i></td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-th manita prjInfo"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>