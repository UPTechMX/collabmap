<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

$elementos = $db->query(" SELECT * FROM Fondeadores") -> fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(eleId);
			popUp('admin/administracion/financiadores/financiadoresAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administracion/financiadores/financiadoresAdd.php',{eleId:eleId})
		});
	});
</script>

<table class="table">
	<thead>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="fond_<?php echo $e['id']; ?>">
				<td><?php echo $e['nombre']; ?></td>
				<td><i class="glyphicon glyphicon-pencil manita edtEle"></i></td>
				<!-- <td><i class="glyphicon glyphicon-eye-open manita verEle"></i></td> -->
			</tr>
		<?php } ?>
	</tbody>
</table>