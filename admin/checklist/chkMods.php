<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist

	$checklist = $db->query("SELECT * FROM Checklist WHERE id = $_POST[checklistId]") -> fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($checklist);

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#edtInfoChk').click(function(event) {
			event.preventDefault();
			var checklistId = <?php echo $_POST['checklistId']; ?>;
			// console.log(checklistId);
			popUp('admin/checklist/checklistAdd.php',{checklistId:checklistId},function(e){},{});

		});

		$('#condChk').click(function(event) {
			event.preventDefault();
			var checklistId = <?php echo $_POST['checklistId']; ?>;
			popUp('admin/checklist/condiciones.php',{eleId:checklistId,aplicacion:'chk'},function(e){},{});
		});

		$('#tPromChk').click(function(event) {
			event.preventDefault();
			var checklistId = <?php echo $_POST['checklistId']; ?>;
			popUp('admin/checklist/chkTipoProm.php',{chkId:checklistId},function(e){},{});
		});


	});
</script>
<div class="nuevo"><?php echo TR('surveyConfig'); ?></div>
<div class="row">
	<table class="table">
		<thead>
			<tr>
				<th><?= TR('name'); ?></th>
				<th><?= TR('code'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?= $checklist['nombre']; ?></td>
				<td><?= $checklist['siglas']; ?></td>
				<td style="text-align: right;">
					<i class="glyphicon glyphicon-pencil manita" id="edtInfoChk"></i>
					
				</td>
			</tr>
		</tbody>
	</table>
	<!-- <div class="col-4" style="border:none 1px;">
		<span class="btn btn-shop" id="condChk"><?php echo TR('conditional'); ?></span>
	</div> -->
	<!-- <div class="col-4" style="border:none 1px;">
		<span class="btn btn-shop" id="tPromChk"><?php echo TR('average'); ?></span>
	</div> -->
</div>
