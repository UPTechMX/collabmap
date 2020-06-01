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
				// console.log(rj);
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

	});
</script>

<table class="table" style="margin-top: 10px;">
	<thead>
		<tr>
			<th><?php echo TR('survey'); ?></th>
			<th><?php echo TR('frequency'); ?></th>
			<th></th>
			<!-- <th></th> -->
			<!-- <th></th> -->
			<th></th>
		</tr>
	</thead>
	<body>
		<?php 
		foreach ($consultationsChecklist as $cc){

		?>
			<tr id="trCC_<?php echo $cc['cId'] ?>">
				<td><?php echo $cc['nombre']; ?></td>
				<td><?php echo TR($cc['code']); ?></td>
				<td>
					<i class="glyphicon glyphicon-pencil manita edtSurv" id="edtCC_<?php echo $cc['tcId']; ?>"></i>
				</td>
				<!-- <td>
					<i class="glyphicon glyphicon-download-alt manita dwlSurv" id="dwlCC_<?php echo $cc['tcId']; ?>"></i>
				</td> -->
				<!-- <td>
					<i class="glyphicon glyphicon-upload manita uplSurv" id="uplCC_<?php echo $cc['tcId']; ?>"></i>
				</td> -->
				<td>
					<i class="glyphicon glyphicon-trash rojo manita delSurv" id="trashCC_<?php echo $cc['tcId']; ?>"></i>
				</td>
			</tr>
		<?php } ?>
	</body>
</table>
