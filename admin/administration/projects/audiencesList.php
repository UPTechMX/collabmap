<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Projects

	$audiences = $db->query("SELECT * FROM Audiences WHERE projectsId = $_POST[prjId] ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".edtAud").click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			var prjId = <?php echo $_POST['prjId']; ?>;
			popUp('admin/administration/projects/audiencesAdd.php',{prjId:prjId,eleId:eleId});
		});

		$(".audStruc").click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			var elemName = $(this).closest('tr').find('.audName').text();
			$('#audStructures').load(rz+'admin/structures/index.php',{type:'audiences',elemId:eleId,elemName:elemName});
		});

		$(".delAud").click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			conf('<?php echo TR('delAud'); ?>',{eleId:eleId,elem:$(this)},function(e){
				// console.log(e);
				var rj = jsonF('admin/administration/projects/json/json.php',{acc:4,eleId:e.eleId});
				var r = $.parseJSON(rj);;

				if(r.ok == 1){
					e.elem.closest('tr').remove();
				}
			})
			
		});

	});
</script>

<table class="table" id="audiencesTable">
	<thead>
		<tr>
			<th><?php echo TR('name'); ?></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($audiences as $a){ ?>
			<tr id="audTr_<?php echo $a['id'] ?>">
				<td class="audName"><?php echo $a['name']; ?></td>
				<td>
					<i class="glyphicon glyphicon-pencil manita edtAud"></i>
				</td>
				<td>
					<i class="glyphicon glyphicon-trash manita rojo delAud"></i>
				</td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-th manita audStruc"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>