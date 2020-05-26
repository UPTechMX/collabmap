<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$audiences = $db->query("SELECT * FROM Audiences WHERE projectsId = $consultation[pId]")->fetchAll(PDO::FETCH_ASSOC);

// print2($consultation);
$_POST['consultationsId'] = $consultation['id'];

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#audSel').change(function(event) {
			var audId = $(this).val();

			if(audId != ''){
				var consultationsId = <?php echo $consultation['id']; ?>;
				$('#audStruc').load(rz+'admin/administration/consultations/audStruct.php',{
					elemId:audId,
					type:'audiences',
					consultationsId:consultationsId
				})
			}
		});
	});
</script>
<div class="nuevo"><?php echo TR('audiences'); ?></div>
<div>
	<div style="font-weight: bold;font-size: .8em;">*<?php echo TR('noAudienceMsg'); ?></div>
	<select class="form-control" id="audSel">
		<option value=""><?php echo TR('audience'); ?></option>
		<?php foreach ($audiences as $a){ ?>
			<option value="<?php echo $a['id']; ?>"><?php echo $a['name']; ?></option>
		<?php } ?>
	</select>
</div>
<div id="audStruc"></div>
<div id="audiencesList"><?php include 'audiencesList.php'; ?></div>
