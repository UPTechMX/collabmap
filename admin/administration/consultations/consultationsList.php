<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(50);// checaAcceso Consultations


$elementos = $db->query("SELECT t.*, p.name as pName
	FROM Consultations t
	LEFT JOIN Projects p ON t.projectsId = p.id
	WHERE (p.inactive IS NULL OR p.inactive = 0)
	ORDER BY p.name, t.name") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#consultationsTable .edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(eleId);
			popUp('admin/administration/consultations/consultationsAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administration/financiadores/financiadoresAdd.php',{eleId:eleId})
		});

		$(".trgtInfo").click(function(event) {
			var consultationId = $(this).closest('tr').attr('id').split('_')[1];
			$('#consultationsInfo').load(rz+'admin/administration/consultations/consultationsInfo.php',{consultationId:consultationId});


			$(this).closest('table').find('tr').removeClass('seleccionado');
			$(this).closest('tr').addClass('seleccionado');

			// $('#consultationsStructure').load(rz+'admin/administration/consultations/consultationsStructure.php',
			// 	{consultationsId:consultationId});
		});
	});
</script>

<table class="table" id="consultationsTable">
	<thead>
		<tr>
			<th><?= TR('icon'); ?></th>
			<th><?= TR('project'); ?></th>
			<th><?= TR('name') ?></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="fond_<?php echo $e['id']; ?>">
				<td><i class="fas <?php echo $e['icon']; ?>"></i></td>
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