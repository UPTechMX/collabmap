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

<div class="row justify-content-between" style="margin: 20px 0px;">
	<div class="col-4" style="font-weight: bold;text-align: left;">
		<?= TR('consultations'); ?>
	</div>
	<div class="col-4" style="text-align: right;">


		<span class="btn btn-shop" id="consultationAdd"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('new'); ?></span>
		
	</div>
</div>

<div class="nuevo titleL2Bkg"><?= TR('consultationsLIst'); ?></div>
<div id="consultationsList" style="max-height: 500px;overflow-y: auto;"><?php include_once 'consultationsList.php'; ?></div>