<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

checaAcceso(50);
// print2(datCodigoPostal('16059'));
$municipios = $db->query("SELECT m.estadosId, m.id as val, m.nombre as nom, 'clase' as clase 
	FROM Municipios m ORDER BY m.nombre")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
$estados = $db->query("SELECT e.id as val, e.nombre as nom, 'clase' as clase 
	FROM Estados e ORDER BY e.nombre")->fetchAll(PDO::FETCH_ASSOC);

?>


<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-core.js"></script>
<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-service.js"></script>
<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-ui.js"></script>
<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-mapevents.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 -->

<script type="text/javascript" src="../lib/js/jquery-confirm/dist/jquery-confirm.min.js"></script>
<!-- <script type="text/javascript" src="bootstrap4/js/bootstrap.min.js"></script> -->

<!-- BODY -->
<!-- <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.min.css" media="screen" /> -->
<link rel="stylesheet" type="text/css" href="../lib/js/jquery-confirm/dist/jquery-confirm.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../lib/js/hereMaps/mapsjs-ui.css" media="screen" />


<script type="text/javascript">
	var municipios = <?php echo atj($municipios); ?>;
	var estados = <?php echo atj($estados); ?>;

	$(document).ready(function() {
		$('#addReconocimiento').click(function(event) {
			// console.log('asas');
			popUp('admin/administracion/reconocimientos/reconocimientosAdd.php',{},function(){});
		});

		paramsBusq = {};
		$('#buscarRec').click(function(event) {
			popUp('admin/administracion/reconocimientos/busca.php',paramsBusq, function(){},{});
		});
	});
</script>
<div class="row">
	<div class="col-5">
		<div class="nuevo">Reconocimientos</div>
		<div style="margin:10px;" class="row">
			<div class="col-6">
				<span class="btn btn-sm btn-shop" id="addReconocimiento">Agregar reconocimiento</span>
			</div>


			<div class="col-6" style="text-align: right;">
				<span class="btn btn-sm btn-shop" id="buscarRec">Buscar</span>
				<br/>
			</div>
		</div>
				<div id="contador" class="contGen"></div>

		<div id="reconocimientosList" style="max-height: 700px;overflow-y: auto;"><?php include 'reconocimientosList.php'; ?></div>
	</div>
	<div class="col-7">
		<div id="infoEle"></div>
	</div>
</div>