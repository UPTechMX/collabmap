<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(60);// checaAcceso Usuarios

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addUsr').click(function(event) {
			popUp('admin/administration/usuarios/usuariosAdd.php',{},function(){},{});
		});
	});
</script>

<div class="row">
	<div class="col-12">
		<div class="nuevo"><?php echo TR("internalUsers"); ?></div>
		<div class="row justify-content-between" style="margin: 20px 0px;">
			<div class="col-4" style="font-weight: bold;text-align: left;">
				<?= TR('internalUsers'); ?>
			</div>
			<div class="col-4" style="text-align: right;">

				<span class="btn btn-sm btn-shop" id="addUsr">
					<i class="glyphicon glyphicon-plus"></i>
					<?php echo TR('add'); ?>
				</span>


				
			</div>
		</div>
		<div id="usuariosList"><?php include_once 'usuariosList.php'; ?></div>
	</div>

	<div class="col-12" id="privilegiosList"></div>
</div>