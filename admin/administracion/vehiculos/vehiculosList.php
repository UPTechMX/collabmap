<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

$elementos = $db->query(" SELECT * FROM Vehiculos") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(eleId);
			popUp('admin/administracion/vehiculos/vehiculosAdd.php',{eleId:eleId},function(){},{});
		});
		$('.verEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(eleId);
			$('#infoEle').load(rz+'admin/administracion/vehiculos/vehiculosInfo.php',{eleId:eleId})
		});
	});
</script>

<table class="table">
	<thead>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="elem_<?php echo $e['id']; ?>">
				<td><?php echo $e['nombre']; ?></td>
				<td><i class="glyphicon glyphicon-pencil manita edtEle"></i></td>
				<td><i class="glyphicon glyphicon-eye-open manita verEle"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>