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

<!-- <div class="nuevo grisBkg"><?php echo TR('structure'); ?></div> -->

<?php
	$_POST['type'] = 'structure';
	$_POST['elemId'] = $_POST['targetsId'];
	$_POST['elemName'] = 'aaa';
	


?>
<?php include_once raiz().'admin/structures/index.php'; ?>

