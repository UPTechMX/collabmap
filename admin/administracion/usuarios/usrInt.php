<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(60);

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addUsr').click(function(event) {
			popUp('admin/administracion/usuarios/usuariosAdd.php',{},function(){},{});
		});
	});
</script>

<div class="row">
	<div class="col-6">
		<div class="nuevo">Usuarios</div>
		<div style="text-align: right;margin:10px 0px;">
			<span class="btn btn-sm btn-shop" id="addUsr">Agregar usuario</span>
		</div>
		<div id="usuariosList"><?php include_once 'usuariosList.php'; ?></div>
	</div>

	<div class="col-6" id="privilegiosList"></div>
</div>