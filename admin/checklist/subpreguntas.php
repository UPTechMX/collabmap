<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#subpreguntas_'+<?php echo $_POST['pregId']; ?>+' #addSubpreg').click(function(event) {
			var pregId = $(this).closest('.subpreguntas').attr('id').split('_')[1];
			// console.log(pregId);
			popUp('admin/checklist/subpreguntasAdd.php',{pregId:pregId},function(e){},{});

		});

	});
</script>


<span class="btn btn-shop btn-sm" id="addSubpreg" style="margin-top:5px;">
	<i class="glyphicon glyphicon-plus">&nbsp;</i>Agregar pregunta
</span>


<div id="subpreguntasList_<?php echo $_POST['pregId']; ?>"><?php include 'subpreguntasList.php'; ?></div>