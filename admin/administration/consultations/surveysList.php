<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

// print2($_POST);
$consultationsChecklist = $db->query("SELECT c.nombre, c.id as cId, cc.id as tcId, cc.frequency, f.code
	FROM ConsultationsChecklist cc 
	LEFT JOIN Checklist c ON c.id = cc.checklistId
	LEFT JOIN Frequencies f ON f.id = cc.frequency
	WHERE cc.consultationsId = $_POST[consultationId]
	ORDER BY cc.frequency")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delSurv').click(function(event) {
			var tcId = this.id.split('_')[1];
			conf('<?php echo TR("surveyDelAlert") ?>',{tcId:tcId,elem:this},function(e){
				var rj = jsonF('admin/administration/consultations/json/json.php',{opt:3,acc:3,tcId:tcId})
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$(e.elem).closest('tr').remove();
				}
			})
		});

		$('.edtSurv').click(function(event) {
			var tcId = this.id.split('_')[1];
			popUp('admin/administration/consultations/chFreq.php',{eleId:tcId});
		});

		$('.dwlSurv').click(function(event) {
			var checklistId = $(this).closest('tr').attr('id').split('_')[1];
			var consultationsId = <?php echo $_POST['consultationId']; ?>;

			$('<form>')
			.attr({
				id: 'descPrueba',
				action: rz+'lib/php/dwlCheckList.php',
				consultation:'_blank',
				method:'post'
			})
			.html(
				'<input type="text" name="chkId" value="'+checklistId+'"\>'+
				'<input type="text" name="consultationsId" value="'+consultationsId+'"\>'
			)
			.appendTo(document.body)
			.submit()
			.remove();
		});

		$('.uplSurv').click(function(event) {
			var checklistId = $(this).closest('tr').attr('id').split('_')[1];
			var consultationsId = <?php echo $_POST['consultationId']; ?>;
			popUp('admin/administration/consultations/uploadChecklist.php',{checklistId:checklistId,consultationsId:consultationsId});

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
		foreach ($consultationsChecklist as $tc){

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
					<i class="glyphicon glyphicon-trash rojo manita delSurv" id="trashTC_<?php echo $tc['tcId']; ?>"></i>
				</td>
			</tr>
		<?php } ?>
	</body>
</table>
