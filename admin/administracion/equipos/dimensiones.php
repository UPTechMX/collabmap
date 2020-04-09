<?php  

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#addDim").click(function(event) {
			var areasId = <?php echo $_POST['areasId']; ?>;
			popUp('admin/administracion/equipos/dimensionesAdd.php',{areasId:areasId},function(e){},{});

		});
		// $("#cpDim").click(function(event) {
		// 	var areasId = <?php echo $_POST['areasId']; ?>;
		// 	popUp('admin/administracion/equipos/dimensionesCopy.php',{areasId:areasId,areasId},function(e){},{});
		// });
	});
</script>
<br/>

<div class="row">
	<div class="col-md-3">
		<div class="nuevo">
			Categorías
		</div>
		<span class="btn btn-shop btn-sm" id="addDim">
			<i class="glyphicon glyphicon-plus">&nbsp;</i>Agregar categoría 
		</span>
<!-- 		<span class="btn btn-shop btn-sm" id="cpDim">
			<i class="glyphicon glyphicon-copy">&nbsp;</i>Copiar 
		</span>
 -->		<div id="dimensionesList"><?php include_once 'dimensionesList.php'; ?></div>

	</div>

	<div class="col-md-9" >
		<div class="nuevo">
			Elementos
		</div>
		<div class="" id="dimensionesArbol"><?php include_once 'dimensionesArbol.php'; ?></div>
	</div>
</div>