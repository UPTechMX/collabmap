<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Public consultations

?>

<div style="margin-top: 10px">
	<div class="row">
		<div class="col-12"><?php include_once 'publicConsultations.php'; ?></div>
		<div class="col-12" id="pcInfo"></div>
	</div>
</div>
