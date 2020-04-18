<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Targets


?>
<div class="nuevo"><?php echo TR('dimensions'); ?></div>

<div><?php include 'structure/dimensiones.php'; ?></div>