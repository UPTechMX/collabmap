<?php  

if (!function_exists('raiz')) {
	include_once '../../../../lib/j/j.func.php';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#addDim").click(function(event) {
			var targetsId = <?php echo $_POST['targetsId']; ?>;
			popUp('admin/administration/targets/structure/dimensionesAdd.php',{targetsId:targetsId},function(e){},{});

		});
		// $("#cpDim").click(function(event) {
		// 	var targetsId = <?php echo $_POST['targetsId']; ?>;
		// 	popUp('admin/administration/targets/structure/dimensionesCopy.php',{targetsId:targetsId,targetsId},function(e){},{});
		// });
	});
</script>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-3">
		<div class="nuevo">
			<?php echo TR('level'); ?>
		</div>
		<span class="btn btn-shop btn-sm" id="addDim">
			<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('addLevel'); ?> 
		</span>
		<div id="dimensionesList" style="margin-top: 10px;"><?php include_once 'dimensionesList.php'; ?></div>
	</div>

	<div class="col-md-9" >
		<div class="nuevo">
			<?php echo TR('elements'); ?>
		</div>
		<div class="" id="dimensionesArbol"><?php include_once 'dimensionesArbol.php'; ?></div>
	</div>
</div>