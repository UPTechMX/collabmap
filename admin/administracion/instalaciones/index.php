<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

checaAcceso(50);
// print2(datCodigoPostal('85203'));
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#addInstalacion').click(function(event) {
			popUp('admin/administracion/instalaciones/instalacionesAdd.php',{},function(){},{});
		});
	});
</script>
<div class="row">
	<div class="col-12">
		<div class="nuevo">Instalaciones</div>
		<div style="margin:10px;">
			<span class="btn btn-sm btn-shop" id="addInstalacion">Agregar instalacion</span>
		</div>

		<div id="instalacionesList"><?php include 'instalacionesList.php'; ?></div>
	</div>
	<div class="col-6">
		<div id="infoFinanciador"></div>
	</div>
</div>