<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Public consultations

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#pcAdd').click(function(event) {
			popUp('admin/administration/publicConsultations/pcAdd.php',{});
		});
	});
</script>

<div class="nuevo"><?php echo TR('publicCons'); ?></div>
<div style="text-align: right;margin-bottom: 10px;">
	<span class="btn btn-shop" id="pcAdd"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('publicCon'); ?></span>
</div>
<div id="pcList"><?php include_once 'pcList.php'; ?></div>