<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Targets

// print2($_POST);
$targetsChecklist = $db->query("SELECT c.nombre, c.id as cId, tc.id as tcId, tc.frequency, f.code
	FROM TargetsChecklist tc 
	LEFT JOIN Checklist c ON c.id = tc.checklistId
	LEFT JOIN Frequencies f ON f.id = tc.frequency
	WHERE tc.targetsId = $_POST[targetId]
	ORDER BY tc.frequency")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delSurv').click(function(event) {
			var tcId = this.id.split('_')[1];
			conf('<?php echo TR("surveyDelAlert") ?>',{tcId:tcId,elem:this},function(e){
				var rj = jsonF('admin/administration/targets/json/json.php',{opt:3,acc:3,tcId:tcId})
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$(e.elem).closest('tr').remove();
				}
			})
		});

		$('.edtSurv').click(function(event) {
			var tcId = this.id.split('_')[1];
			popUp('admin/administration/targets/chFreq.php',{eleId:tcId});
		});

		$('.dwlSurv').click(function(event) {
			var checklistId = $(this).closest('tr').attr('id').split('_')[1];
			var targetsId = <?php echo $_POST['targetId']; ?>;

			$('<form>')
			.attr({
				id: 'descPrueba',
				action: rz+'lib/php/dwlCheckList.php',
				target:'_blank',
				method:'post'
			})
			.html(
				'<input type="text" name="chkId" value="'+checklistId+'"\>'+
				'<input type="text" name="targetsId" value="'+targetsId+'"\>'
			)
			.appendTo(document.body)
			.submit()
			.remove();
		});

		$('.uplSurv').click(function(event) {
			var checklistId = $(this).closest('tr').attr('id').split('_')[1];
			var targetsId = <?php echo $_POST['targetId']; ?>;
			popUp('admin/administration/targets/uploadChecklist.php',{checklistId:checklistId,targetsId:targetsId});

		});

	});
</script>

<table class="table" style="margin-top: 10px;">
	<thead>
		<tr>
			<th><?php echo TR('survey'); ?></th>
			<th><?php echo TR('frequency'); ?></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<body>
		<?php 
		foreach ($targetsChecklist as $tc){
			$cuenta = $db->query("SELECT COUNT(*) 
				FROM Visitas v
				LEFT JOIN TargetsElems te ON te.id = v.elemId
				LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId AND v.checklistId = tc.checklistId
				WHERE  v.type = 'trgt' AND tc.id = $tc[tcId] 
			")->fetchAll(PDO::FETCH_NUM)[0][0];

		?>
			<tr id="trTC_<?php echo $tc['cId'] ?>">
				<td><?php echo $tc['nombre']; ?></td>
				<td><?php echo TR($tc['code']); ?></td>
				<td>
					<i class="glyphicon glyphicon-pencil manita edtSurv" id="edtTC_<?php echo $tc['tcId']; ?>"></i>
				</td>
				<td>
					<i class="glyphicon glyphicon-download-alt manita dwlSurv" id="dwlTC_<?php echo $tc['tcId']; ?>"></i>
				</td>
				<td>
					<i class="glyphicon glyphicon-upload manita uplSurv" id="uplTC_<?php echo $tc['tcId']; ?>"></i>
				</td>
				<td>
					<?php if ($cuenta == 0){ ?>
						<i class="glyphicon glyphicon-trash rojo manita delSurv" id="trashTC_<?php echo $tc['tcId']; ?>"></i>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</body>
</table>
