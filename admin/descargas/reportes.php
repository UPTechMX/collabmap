<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);
	$nivel = $_SESSION['CM']['admin']['nivel'];

	include_once raiz().'lib/php/usrInt.php';
	$usrId = $_SESSION['CM']['admin']['usrId'];
	$usr = new Usuario($usrId);

	$revisores = $db->query("SELECT id as val, CONCAT(nombre,' ',aPat,' ',aMat) as nom, 'clase' as clase 
		FROM usrAdmin WHERE nivel = 10")->fetchAll(PDO::FETCH_ASSOC);
	// print2($revisores)

	$prys = $usr->getProyectos();
	// print2($prys);

	foreach ($prys as $cId => $p) {
		$p = $p[0];
		$tmpC['val'] = $cId;
		$tmpC['nom'] = $p['cNom'];
		$tmpC['clase'] = 'clase';
		$clientes[] = $tmpC;

		$tmpP['val'] = $p['id'];
		$tmpP['nom'] = $p['nom'];
		$tmpP['clase'] = $cId;
		$proyectos[$cId][] = $tmpP;
	}

	// $clientes = $db->query("SELECT id as val, nombre as nom, 'clase' as clase FROM Clientes")->fetchAll(PDO::FETCH_ASSOC);


	$proyectos = $db->query("SELECT clientesId, id as val, nombre as nom, clientesId as clase 
		FROM Proyectos") -> fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$repeticiones = $db->query("SELECT proyectosId, id as val, nombre as nom, proyectosId as clase 
		FROM Repeticiones")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	// print2($repeticiones);
?>
<script type="text/javascript">
	$(document).ready(function() {
		
		vis = {};

		var revisores = <?php echo atj($revisores); ?>;
		optsSel(revisores,$('#revSel'),false,'- - - Selecciona un revisor - - -',false);
		var clientes = <?php echo atj($clientes); ?>;
		optsSel(clientes,$('#cltSel'),false,'- - - Selecciona un cliente - - -',false);
		var proyectos = <?php echo atj($proyectos); ?>;
		var repeticiones = <?php echo atj($repeticiones); ?>;

		$('#cltSel').change(function(event) {
			var clienteId = $(this).val();
			if(clienteId != ''){
				optsSel(proyectos[clienteId],$('#prySel'),false,'- - - Selecciona un proyecto - - -',false)
			}else{
				optsSel([],$('#prySel'),false,'- - - Selecciona un proyecto - - -',false)
			}
			$('#prySel').trigger('change');
		});

		$('#prySel').change(function(event) {
			var pryId = $(this).val();
			$('#pryId').val(pryId);
		});

		$('#tipo').change(function(event) {
			var tipoRep = $(this).val();
			$('#tipoRep').val(tipoRep);

			if(tipoRep == 2 || tipoRep == 5){
				$('.ocultarTipo').hide();
			}else{
				$('.ocultarTipo').show();
			}
		});

		$('#descargar').click(function(event) {
			var tipoRep = $('#tipoRep').val();
			// console.log(tipoRep);
			if(($('#pryId').val() != '' && tipoRep != '') || tipoRep == 2 || tipoRep == 5){
				$('#forma').submit();
			}
		});

	});
</script>


<div class="nuevo">
	Descargas de reportes
</div>
<div class="row">
	<div class="col-md-4">
		<div class="nuevo">Tipo de reporte</div>
		<select class="form-control" id="tipo">
			<option value = ''>- - - Selecciona un tipo de reporte - - -</option>
			<option value="2">Base de datos shoppers</option>
			<option value="1">Calificaciones shoppers</option>
			<option value="3">Cancelaciones</option>
			<option value="4">Tipos de asignación</option>
			<option value="6">Visitas faltantes</option>
			<option value="8">Visitas asignadas y no recibidas</option>
			<?php if ($nivel >= 30){ ?>
				<option value = ''>- - - Sólo coordinadores - - -</option>
				<option value="7">Reporte de costos</option>
			<?php } ?>
			<?php if ($nivel >= 40){ ?>
				<option value = ''>- - - Sólo administradores - - -</option>
				<option value="5">Reporte de pagos recibidos</option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-4 ocultarTipo">
		<div class="nuevo">Cliente</div>
		<select class="form-control" id="cltSel"></select>
	</div>
	<div class="col-md-4 ocultarTipo">
		<div class="nuevo">Proyecto</div>
		<select class="form-control" id="prySel">
			<option value = ''>- - - Selecciona un proyecto - - -</option>
		</select>
	</div>
</div>
<div style="text-align: right;margin-top: 10px;">
	<span class="btn btn-sm btn-shop" id="descargar">Descargar</span>
</div>

<form action="descargas/descargas.php" method="post" target="_blank" id="forma" style="display: none;">
	<input type="hidden" name="pryId" id="pryId" value="">
	<input type="hidden" name="tipoRep" id="tipoRep" value="">
</form>



