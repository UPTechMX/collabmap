<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$documents = $db->query("SELECT * FROM Documents 
	WHERE consultationsId = $_POST[consultationsId]
	ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtDoc').click(function(event) {
			var dId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(dId);
			var consultationsId = <?php echo $_POST['consultationsId']; ?>;
			popUp('admin/administration/consultations/edtDoc.php',{eleId:dId,consultationsId:consultationsId});
		});
		$('.delDoc').click(function(event) {
			var tr = $(this).closest('tr');
			var dId = tr.attr('id').split('_')[1];
			conf('<?php echo TR("confdeleteDoc");?>',{tr:tr,dId:dId},function(e){
				var rj = jsonF('admin/administration/consultations/json/json.php',{acc:5,dId:e.dId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					e.tr.remove();
				}
			});

		});
		$('.strucDoc').click(function(event) {
			var dId = $(this).closest('tr').attr('id').split('_')[1];
			var elemName = $(this).closest('tr').find('.tdName').text();
			$('#structures').load(rz+'admin/structures/index.php',{type:'documents',elemId:dId,elemName:elemName});
		});
	});
</script>
<table class="table">
	<thead>
		<tr>
			<th><?php echo TR("file"); ?></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($documents as $d){ ?>
			<tr id="trDoc_<?php echo $d['id']; ?>">
				<td class="tdName"><?php echo $d['name']; ?></td>
				<td>
					<i class="glyphicon glyphicon-pencil manita edtDoc"></i>
				</td>
				<td>
					<i class="glyphicon glyphicon-th manita strucDoc"></i>
				</td>
				<!-- <td>
					<i class="fas fa-chart-bar manita statDoc"></i>
				</td> -->
				<td>
					<i class="glyphicon glyphicon-trash manita rojo delDoc"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>