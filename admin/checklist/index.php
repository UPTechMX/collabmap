<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist

	$checklist = $db->query("SELECT * FROM Checklist ORDER BY nombre") -> fetchAll(PDO::FETCH_ASSOC);


?>

<script type="text/javascript">
	var checklistId = '';
	$(document).ready(function() {

		$('#chkSel').change(function(event) {
			event.preventDefault();
			checklistId = $(this).val();
			if(checklistId != ''){			
				$('#chkMods').load(rz+'admin/checklist/chkMods.php',{checklistId:checklistId});
				$('#bloques').load(rz+'admin/checklist/bloques.php',{checklistId:checklistId});
				$('#general').load(rz+'admin/checklist/general.php',{checklistId:checklistId});
				$('#areas').empty();
				$('#preguntas').empty();
			}else{
				$('#chkMods').empty();
				$('#bloques').empty();
				$('#general').empty();
				$('#areas').empty();
				$('#preguntas').empty();
			}
		});

		$('#addChk').click(function(event) {
			popUp('admin/checklist/checklistAdd.php',{},function(e){},{});
		});

		$('#dupChk').click(function(event) {
			popUp('admin/checklist/checklistCopy.php',{},function(e){},{});
		});

	});
</script>
<div class="row" style="margin-top: 10px;">
	<div class="col-5" >
		<div class="nuevo"><?php echo TR('surveys'); ?></div>
		<div>
			<select class="form-control" id="chkSel">
				<option value="">- - - <?php echo TR('surveys'); ?> - - -</option>
				<?php foreach ($checklist as $c){ ?>
					<option value="<?php echo $c['id'] ?>"><?php echo $c['nombre']; ?></option>
				<?php } ?>
			</select>
			<span id="addChk" class="btn btn-sm btn-shop" style="margin: 10px 0px;">
				<i class="glyphicon glyphicon-plus"></i> &nbsp;<?php echo TR('survey'); ?>
			</span>
			<!-- <span id="dupChk" class="btn btn-sm btn-shop"><?php echo TR('duplicateSurvey'); ?></span> -->
		</div>
	</div>
	<div class="col-7" id="chkMods"></div>
</div>
<hr/>
<div class="row" id="general" style="margin-top:20px;margin-bottom: 10px;"></div>
<div class="row">
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="bloques"></div>
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="areas"></div>
</div>
<div id="preguntas"></div>
