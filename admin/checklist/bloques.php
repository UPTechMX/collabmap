<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addBloque').click(function(event) {
			var checklistId = <?php echo $_POST['checklistId']; ?>;
			popUp('admin/checklist/bloquesAdd.php',{checklistId:checklistId},function(e){},{});
		});

		$('#saveBloqueOrden').click(function(event) {
			var bloques = [];
			var orden = 1;
			$.each($('.bloqueEle'), function(index, val) {
				if(this.id != ''){
					bloques.push({"id":this.id.split('_')[1],"orden":orden++});
				}
			});
			var rj = jsonF('admin/checklist/json/json.php',{bloques:bloques,acc:5,opt:5,chkId:checklistId});
			// console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#saveBloqueOrden').hide();
			}
		});

		$('[data-toggle="tooltip"]').tooltip({
			html:true,
		});



	});
</script>
<div class="nuevo titleL3Bkg">
	<?php echo TR('blocks'); ?>
	<i class="glyphicon glyphicon-info-sign" style="margin-left: 30px;" 
		data-toggle="tooltip" data-placement="right" title="<?= TR('blocksTooltip') ?>"></i>

</div>
	<span class="btn btn-shop btn-sm" id="addBloque">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('addBlock'); ?>
	</span>
	<span class="btn btn-shop btn-sm" id="saveBloqueOrden" style="display:none">
		<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i><?php echo TR('saveOrder') ?>
	</span>

<div id="bloquesList"><?php include_once 'bloquesList.php'; ?></div>
