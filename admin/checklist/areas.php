<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#addArea').click(function(event) {
			var bloqueId = <?php echo $_POST['bloqueId']; ?>;
			popUp('admin/checklist/areasAdd.php',{bloqueId:bloqueId},function(e){},{});
		});

		$('#saveAreaOrden').click(function(event) {
			var areas = [];
			var orden = 1;
			$.each($('.areaEle'), function(index, val) {
				if(this.id != ''){
					areas.push({"id":this.id.split('_')[1],"orden":orden++});
				}
			});
			var rj = jsonF('admin/checklist/json/json.php',{bloques:areas,acc:5,opt:6,chkId:checklistId});
			// console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#saveAreaOrden').hide();
			}

		});

		$('[data-toggle="tooltip"]').tooltip({
			html:true,
		});


	});
</script>
<div class="nuevo grisBkg">
	<?php echo TR('areas'); ?> (<?php echo TR('block').": ". $_POST['nomBloq']; ?>)
	<i class="glyphicon glyphicon-info-sign" style="margin-left: 30px;" 
		data-toggle="tooltip" data-placement="right" title="<?= TR('areasTooltip') ?>"></i>

</div>
	<span class="btn btn-shop btn-sm" id="addArea">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('addArea'); ?>
	</span>
	<span class="btn btn-shop btn-sm" id="saveAreaOrden" style="display:none">
		<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i><?php echo TR('saveOrder'); ?>
	</span>

<div id="areasList"><?php include_once 'areasList.php'; ?></div>
