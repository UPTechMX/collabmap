<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

checaAcceso(50);

$municipios = $db->query("SELECT m.estadosId, m.id as val, m.nombre as nom, 'clase' as clase 
	FROM Municipios m ORDER BY m.nombre")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
// print2(datCodigoPostal('85203'));
$estados = $db->query("SELECT  e.id as val, e.nombre as nom, 'clase' as clase 
	FROM Estados e ORDER BY e.nombre")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	var municipios = <?php echo atj($municipios); ?>;
	var estados = <?php echo atj($estados); ?>;

	$(document).ready(function() {
		$('#addFinanciador').click(function(event) {
			popUp('admin/administracion/financiadores/financiadoresAdd.php',{},function(){},{});
		});
	});
</script>
<div class="row">
	<div class="col-12">
		<div class="nuevo">Financiadores</div>
		<div style="margin:10px;">
			<span class="btn btn-sm btn-shop" id="addFinanciador">Agregar financiador</span>
		</div>

		<div id="financiadoresList"><?php include 'financiadoresList.php'; ?></div>
	</div>
	<div class="col-6">
		<div id="infoFinanciador"></div>
	</div>
</div>