<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

checaAcceso(50);
// print2(datCodigoPostal('16059'));
$municipios = $db->query("SELECT m.estadosId, m.id as val, m.nombre as nom, 'clase' as clase 
	FROM Municipios m ORDER BY m.nombre")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
$estados = $db->query("SELECT m.id as val, m.nombre as nom, 'clase' as clase 
	FROM Estados m ORDER BY m.nombre")->fetchAll(PDO::FETCH_ASSOC);

$areasEquipos = $db->query("SELECT ae.nombre as nom, ae.id as val, COUNT(*) as clase 
	FROM AreasEquipos ae
	LEFT JOIN Dimensiones d ON ae.id = d.areasId
	GROUP BY ae.id
	ORDER BY ae.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$elems = $db->query("SELECT d.id as dId, de.nombre as nom, de.id as val, CONCAT('padre_',de.padre) as clase, d.nivel 
	FROM DimensionesElem de
	LEFT JOIN Dimensiones d ON d.id = de.dimensionesId
	ORDER BY de.nombre
")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

$dimensiones = $db->query("SELECT d.areasId as aId, d.nombre as nom, d.id as val, 'clase' as clase, d.nivel
	FROM Dimensiones d
	ORDER BY d.nivel, d.nombre
")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);


?>

<script type="text/javascript">
	var municipios = <?php echo atj($municipios); ?>;
	var estados = <?php echo atj($estados); ?>;
	var areasEquipos = <?php echo atj($areasEquipos); ?>;
	var elems = <?php echo atj($elems); ?>;
	var dimensiones = <?php echo atj($dimensiones); ?>;

	$(document).ready(function() {
		$('#addFinanciador').click(function(event) {
			popUp('admin/administracion/proyectos/proyectosAdd.php',{},function(){},{});
		});
	});
</script>
<div class="row">
	<div class="col-6">
		<div class="nuevo">Proyectos</div>
		<div style="margin:10px;">
			<span class="btn btn-sm btn-shop" id="addFinanciador">Agregar proyecto</span>
		</div>

		<div id="proyectosList"><?php include 'proyectosList.php'; ?></div>
	</div>
	<div class="col-6">
		<div id="infoEle"></div>
	</div>
</div>