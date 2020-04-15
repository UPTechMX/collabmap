<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

?>

<script type="text/javascript">
	var proyectos = <?php echo atj($proyectos); ?>;
	$(document).ready(function() {
		$('#btnCtes').click(function(event) {
			$('#actividad').load(rz+'admin/descargas/dwlCtes.php');
		});
		$('#btnChk').click(function(event) {
			$('#actividad').load(rz+'admin/descargas/dwlChk.php');
		});
		$('#btnCalls').click(function(event) {
			$('#actividad').load(rz+'admin/descargas/dwlCalls.php');
		});
		$('#btnJuntas').click(function(event) {
			$('#actividad').load(rz+'admin/descargas/dwlJuntas.php');
		});
		$('#btnImport').click(function(event) {
			$('#actividad').load(rz+'admin/descargas/importCtes.php');
		});
	});
</script>

	<h3 style="text-align:center; margin:20px;">Descargas</h3>
	<div class="row justify-content-md-center" style="margin-top: 15px;">
	
		<div class="col-2"><span class="btn btn-sm btn-shop" id="btnCtes">Clientes por proyecto</span></div>
		<div class="col-2"><span class="btn btn-sm btn-shop" id="btnChk">Cuestionarios por proyecto y etapa</span></div>
		<div class="col-2"><span class="btn btn-sm btn-shop" id="btnCalls">Registro de llamadas por proyecto</span></div>
		<div class="col-2"><span class="btn btn-sm btn-shop" id="btnJuntas">Juntas comunitarias por proyecto</span></div>
	</div>

	<h3 style="text-align:center; margin:20px;">Importación</h3>
	<div class="row justify-content-md-center" style="margin-top: 15px;">
		<div class="col-2"><span class="btn btn-sm btn-shop" id="btnImport">Importación de clientes con CSV</span></div>
	</div>

<hr> 
<div id="actividad"></div>
