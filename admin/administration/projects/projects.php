<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Projects

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#targetAdd').click(function(event) {
			popUp('admin/administration/projects/projectsAdd.php',{});
		});
	});
</script>

<div class="nuevo"><?php echo TR('projects'); ?></div>
<div style="text-align: right;margin-bottom: 10px;">
	<span class="btn btn-shop" id="targetAdd"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('project'); ?></span>
</div>
<div id="projectsList"><?php include_once 'projectsList.php'; ?></div>