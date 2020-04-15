<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Projects


$elementos = $db->query(" SELECT * FROM Projects ORDER BY name") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#projectsTable .edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(eleId);
			popUp('admin/administration/projects/projectsAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administration/financiadores/financiadoresAdd.php',{eleId:eleId})
		});
	});
</script>

<table class="table" id="projectsTable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Code</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="fond_<?php echo $e['id']; ?>">
				<td><?php echo $e['name']; ?></td>
				<td><?php echo $e['code']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-pencil manita edtEle"></i></td>
				<!-- <td><i class="glyphicon glyphicon-eye-open manita verEle"></i></td> -->
			</tr>
		<?php } ?>
	</tbody>
</table>