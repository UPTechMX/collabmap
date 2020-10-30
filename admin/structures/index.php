<?php

if (!function_exists('raiz')) {
	include_once '../../lib/j/j.func.php';
}
// print2($_POST);
checaAcceso(60);// checaAcceso Structures

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#importStr').click(function(event) {
			var elemId = <?php echo $_POST['elemId']; ?>;
			var type = "<?php echo $_POST['type']; ?>";
			var elemName = "<?php echo $_POST['elemName']; ?>";
			popUp('admin/structures/import.php',{elemId:elemId,type:type,elemName:elemName});
		});
		$("#dwlExample").click(function(event) {
			$('#exampleFile').submit();
		});
	});
</script>

<div class="nuevo grisBkg"><?php echo TR('structure')." ($_POST[elemName])"; ?></div>
<span id="importStr" class="btn btn-shop"><?php echo TR('importStr'); ?></span>
<span id="dwlExample" class="btn btn-shop">
	<i class="glyphicon glyphicon-download-alt"></i>&nbsp;
	<?php echo TR('example'); ?>		
</span>
<form id="exampleFile" action="examples/structure.php" method="post" target="_blank" >
	<input type="hidden" name="elemId" value="<?php echo $_POST['elemId']; ?>">
	<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
</form>
<div><?php include 'dimensiones.php'; ?></div>