<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {


		$('#addPregs').click(function(event) {
			var areaId = <?php echo $_POST['areaId']; ?>;
			popUp('admin/checklist/preguntasAdd.php',{areaId:areaId},function(e){},{});
		});

		$('#savePregsOrden').click(function(event) {
			
			var bloques = [];
			var orden = 1;
			$.each($('.pregEle'), function(index, val) {
				if(this.id != ''){
					// console.log(this.id);
					bloques.push({"id":this.id.split('_')[1],"orden":orden++});
				}
			});
			var rj = jsonF('admin/checklist/json/json.php',{bloques:bloques,acc:5,opt:7,chkId:checklistId});
			// console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#savePregsOrden').hide();
			}

		});


	});
</script>
<div class="nuevo">
	Preguntas del área <?php echo $_POST['nomArea']; ?>
</div>
<span class="btn btn-shop btn-sm" id="addPregs">
	<i class="glyphicon glyphicon-plus">&nbsp;</i>Agregar pregunta
</span>
<span class="btn btn-shop btn-sm" id="savePregsOrden" style="display: none;">
	<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i>Guardar orden
</span>
<div id="preguntasList"><?php include_once 'preguntasList.php'; ?></div>
		
