<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(50);// checaAcceso Public consultations


$elementos = $db->query("SELECT pc.*
	FROM PublicConsultations pc
	ORDER BY pc.name") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#pcTable .edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(eleId);
			popUp('admin/administration/publicConsultations/pcAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administration/financiadores/financiadoresAdd.php',{eleId:eleId})
		});

		$(".pcInfo").click(function(event) {
			var pcId = $(this).closest('tr').attr('id').split('_')[1];
			$('#pcInfo').load(rz+'admin/administration/publicConsultations/pcInfo.php',{pcId:pcId});
		});
	});
</script>

<table class="table" id="pcTable">
	<thead>
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
					<i class="glyphicon glyphicon-th manita pcInfo"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>