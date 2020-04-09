<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#addArea').click(function(event) {
			var bloqueId = <?php echo $_POST['bloqueId']; ?>;
			popUp('admin/checklistExt/areasAdd.php',{bloqueId:bloqueId},function(e){},{});
		});

		$('#saveAreaOrden').click(function(event) {
			var areas = [];
			var orden = 1;
			$.each($('.areaEle'), function(index, val) {
				if(this.id != ''){
					areas.push({"id":this.id.split('_')[1],"orden":orden++});
				}
			});
			var rj = jsonF('admin/checklistExt/json/json.php',{bloques:areas,acc:5,opt:6});
			// console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#saveAreaOrden').hide();
			}
		});


	});
</script>
<div class="nuevo">
	Areas del bloque <?php echo $_POST['nomBloq']; ?>
</div>
	<span class="btn btn-shop btn-sm" id="addArea">
		<i class="glyphicon glyphicon-plus">&nbsp;</i>Agregar área 
	</span>
	<span class="btn btn-shop btn-sm" id="saveAreaOrden" style="display:none">
		<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i>Guardar orden
	</span>

<div id="areasList"><?php include_once 'areasList.php'; ?></div>
