<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

checaAcceso(50);
$areas = $db->query("SELECT * FROM AreasEquipos")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#addEquipo').click(function(event) {
			popUp('admin/administracion/equipos/equiposAdd.php',{},function(){},{});
		});
		$('#areasId').change(function(event) {
			var areasId = $(this).val();
			if(areasId != ''){
				$('#equipos').load(rz+'admin/administracion/equipos/dimensiones.php',{areasId:areasId});
			}
		});

	});
</script>
<div class="nuevo">Equipos</div>
<div class="row">
	<div class="col-6">
		<select class="form-control" id="areasId">
			<option value="">- - - Area - - -</option>
			<?php foreach ($areas as $a){ ?>
				<option value="<?php echo $a['id']; ?>"><?php echo $a['nombre']; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-6">
		<div style="margin:10px;">
			<span class="btn btn-sm btn-shop" id="addEquipo">Agregar equipo</span>
		</div>
	</div>
</div>
<div id="equipos"></div>