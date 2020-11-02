<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Targets

?>

<div style="margin-top: 10px;margin-bottom: 10px;">
	<div class="row">
		<div class="col-12"><?php include_once 'targets.php'; ?></div>
		<div class="col-12" id="targetsInfo"></div>
	</div>
</div>
<div id="targetsStructure" style="margin-top: 10ox;"></div>