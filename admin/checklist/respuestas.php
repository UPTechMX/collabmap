<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#respuestas_'+<?php echo $_POST['pregId']; ?>+' #addResp').click(function(event) {
			var pregId = $(this).closest('.respuestas').attr('id').split('_')[1];
			popUp('admin/checklist/respuestasAdd.php',{pregId:pregId},function(e){},{});

		});


		$('#respuestas_'+<?php echo $_POST['pregId']; ?>+' #saveRespsOrden').click(function(event) {
			
			var bloques = [];
			var orden = 1;
			$.each($('#respuestas_'+<?php echo $_POST['pregId']; ?>+' .respEle'), function(index, val) {
				if(this.id != ''){
					// console.log(this.id);
					bloques.push({"id":this.id.split('_')[1],"orden":orden++});
				}
			});
			// console.log(bloques);
			var rj = jsonF('admin/checklist/json/json.php',{bloques:bloques,acc:5,opt:8,chkId:checklistId});
			// console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#respuestas_'+<?php echo $_POST['pregId']; ?>+' #saveRespsOrden').hide();
			}

		});

	});
</script>


<span class="btn btn-shop btn-sm" id="addResp" style="margin-top:5px;">
	<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('addAnswer'); ?>
</span>


<span class="btn btn-shop btn-sm" id="saveRespsOrden" style="display: none; margin-top: 5px;">
	<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i><?php echo TR('saveOrder'); ?>
</span>


<div id="respuestasList_<?php echo $_POST['pregId']; ?>"><?php include 'respuestasList.php'; ?></div>