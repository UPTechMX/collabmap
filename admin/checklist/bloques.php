<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

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


	});
</script>
<div class="nuevo">
	Bloques
</div>
	<span class="btn btn-shop btn-sm" id="addBloque">
		<i class="glyphicon glyphicon-plus">&nbsp;</i>Agregar un bloque
	</span>
	<span class="btn btn-shop btn-sm" id="saveBloqueOrden" style="display:none">
		<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i>Guardar orden
	</span>

<div id="bloquesList"><?php include_once 'bloquesList.php'; ?></div>
