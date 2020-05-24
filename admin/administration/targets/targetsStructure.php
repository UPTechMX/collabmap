<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Targets

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#importStr').click(function(event) {
			var targetsId = <?php echo $_POST['targetsId']; ?>;
			popUp('admin/administration/targets/structure/import.php',{targetsId:targetsId})
		});
		$("#dwlExample").click(function(event) {
			$('#exampleFile').submit();
		});
	});
</script>

<div class="nuevo"><?php echo TR('structure'); ?></div>
<span id="importStr" class="btn btn-shop"><?php echo TR('importStr'); ?></span>
<span id="dwlExample" class="btn btn-shop">
	<i class="glyphicon glyphicon-download-alt"></i>&nbsp;
	<?php echo TR('example'); ?>		
</span>
<form id="exampleFile" action="examples/structure.php" method="post" target="_blank" >
	<input type="hidden" name="elemId" value="<?php echo $_POST['targetsId']; ?>">
	<input type="hidden" name="type" value="structure">
</form>
<div><?php include 'structure/dimensiones.php'; ?></div>