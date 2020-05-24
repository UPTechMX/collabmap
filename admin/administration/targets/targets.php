<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Targets

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#targetAdd').click(function(event) {
			popUp('admin/administration/targets/targetsAdd.php',{});
		});
	});
</script>

<div class="nuevo"><?php echo TR('targets'); ?></div>
<div style="text-align: right;margin-bottom: 10px;">
	<span class="btn btn-shop" id="targetAdd"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('target'); ?></span>
</div>
<div id="targetsList" style="max-height: 500px;overflow-y: auto;"><?php include_once 'targetsList.php'; ?></div>