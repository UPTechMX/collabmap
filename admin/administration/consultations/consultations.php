<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Consultations

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#consultationAdd').click(function(event) {
			popUp('admin/administration/consultations/consultationsAdd.php',{});
		});
	});
</script>

<div class="nuevo"><?php echo TR('consultations'); ?></div>
<div style="text-align: right;margin-bottom: 10px;">
	<span class="btn btn-shop" id="consultationAdd"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('consultation'); ?></span>
</div>
<div id="consultationsList" style="max-height: 500px;overflow-y: auto;"><?php include_once 'consultationsList.php'; ?></div>