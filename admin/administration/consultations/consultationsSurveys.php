<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$checklist = $db->query("SELECT * FROM Checklist ORDER BY nombre") -> fetchAll(PDO::FETCH_ASSOC);
$frequencies = $db->query("SELECT * FROM Frequencies ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#addTrgUsr').click(function(event) {
			var chkId = $('#chkSel').val();
			var freq = $('#freqSel').val();
			if(chkId != '' && freq != ''){
				if($('#trTC_'+chkId).length > 0){
					alertar('<?php echo TR('surveyAlert'); ?>')
				}else{
					var dat = {};
					dat.frequency = freq;
					dat.checklistId = chkId;
					dat.consultationsId = <?php echo $_POST['consultationId']; ?>;
					
					var rj = jsonF('admin/administration/consultations/json/json.php',{opt:2,acc:1,datos:dat});
					// console.log(rj);
					var r = $.parseJSON(rj);

					if(r.ok == 1){
						$('#surveysList').load(rz+'admin/administration/consultations/surveysList.php',{consultationId:dat.consultationsId});
					}

				}
			}
		});
	});
</script>

<div class="nuevo grisBkg"><?php echo TR('surveys'); ?></div>
<div class="row">
	<div class="col-6">
		<select class="form-control" id="chkSel">
			<option value="">- - - <?php echo TR('surveys'); ?> - - -</option>
			<?php foreach ($checklist as $c){ ?>
				<option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-6">
		<select class="form-control" id="freqSel">
			<option value="">- - - <?php echo TR('frequency'); ?> - - -</option>
			<?php foreach ($frequencies as $f){ ?>
				<option value="<?php echo $f['id'] ?>"><?php echo TR($f['code']); ?></option>
			<?php } ?>
		</select>
	</div>
</div>
<div class="row justify-content-between" style="margin: 20px 0px;">
	<div class="col-4" style="font-weight: bold;text-align: left;text-transform: uppercase;">
		<?= TR('survey'); ?>
	</div>
	<div class="col-4" style="text-align: right;">

		<span class="btn btn-shop" id="addTrgUsr">
			<i class="glyphicon  glyphicon-plus"></i>&nbsp;<?php echo TR("add"); ?>
		</span>


		
	</div>
</div>


<div id="surveysList"><?php include_once 'surveysList.php'; ?></div>

