<script type="text/javascript">
	$(document).ready(function() {
		$('#proyectosAdd').click(function(event) {
			popUp('admin/administracion/usuarios/proyectosAdd.php',{usrId:<?php echo $_POST['usrId'];?>},function(){},{});
		});
	});
</script>

<div style="text-align: right; margin:10px 0px;">
	<span class="btn btn-sm btn-shop" id="proyectosAdd">Agregar proyecto</span>
</div>

<div id="proyectosList"><?php include_once'proyectosList.php'; ?></div>