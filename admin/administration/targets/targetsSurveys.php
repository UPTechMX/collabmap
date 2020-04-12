<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Targets

$checklist = $db->query("SELECT * FROM Checklist ORDER BY nombre") -> fetchAll(PDO::FETCH_ASSOC);

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
					dat.targetsId = <?php echo $_POST['targetId']; ?>;
					
					var rj = jsonF('admin/administration/targets/json/json.php',{opt:2,acc:1,datos:dat});
					console.log(rj);
					var r = $.parseJSON(rj);

					if(r.ok == 1){
						$('#surveysList').load(rz+'admin/administration/targets/surveysList.php',{targetId:dat.targetsId});
					}

				}
			}
		});
	});
</script>

<div class="nuevo"><?php echo TR('surveys'); ?></div>
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
			<option value="0"><?php echo TR('oneTime'); ?></option>
			<option value="1"><?php echo TR('daily'); ?></option>
			<option value="2"><?php echo TR('weekly'); ?></option>
			<option value="3"><?php echo TR('2weeks'); ?></option>
			<option value="4"><?php echo TR('3weeks'); ?></option>
			<option value="5"><?php echo TR('monthly'); ?></option>
			<option value="6"><?php echo TR('2months'); ?></option>
			<option value="7"><?php echo TR('3months'); ?></option>
			<option value="8"><?php echo TR('4months'); ?></option>
			<option value="9"><?php echo TR('6months'); ?></option>
			<option value="10"><?php echo TR('yearly'); ?></option>
		</select>
	</div>
</div>
<div style="text-align: right;margin-top: 10px;">
	<span class="btn btn-shop" id="addTrgUsr">
		<i class="glyphicon  glyphicon-plus"></i>&nbsp;<?php echo TR("survey"); ?>
	</span>
</div>


<div id="surveysList"><?php include_once 'surveysList.php'; ?></div>

