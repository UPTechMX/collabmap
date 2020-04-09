<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

checaAcceso(50);

$vehiculos = $db->query("SELECT *
	FROM Vehiculos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
// print2(datCodigoPostal('85203'));

?>

<script type="text/javascript">

	$(document).ready(function() {
		$('#addVehiculo').click(function(event) {
			popUp('admin/administracion/vehiculos/vehiculosAdd.php',{},function(){},{});
		});
	});
</script>
<div class="row">
	<div class="col-6">
		<div class="nuevo">Vehiculos</div>
		<div style="margin:10px;">
			<span class="btn btn-sm btn-shop" id="addVehiculo">Agregar vehículo</span>
		</div>

		<div id="vehiculosList"><?php include 'vehiculosList.php'; ?></div>
	</div>
	<div class="col-6">
		<div id="infoEle"></div>
	</div>
</div>