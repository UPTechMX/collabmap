<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Consultations

?>

<div style="margin-top: 10px;margin-bottom: 10px;">
	<div class="row">
		<div class="col-12"><?php include_once 'consultations.php'; ?></div>
		<div class="col-12" id="consultationsInfo"></div>
	</div>
</div>
<div id="structures" style="margin-top: 10ox;"></div>