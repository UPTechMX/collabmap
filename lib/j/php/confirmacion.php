<?php 

	include '../j.func.php';

?>

<div class="modal-header" style="text-align:center;">
	<h4><?php echo TR('confAction'); ?></h4>
</div>
<div class="modal-body">
	<?php echo $_POST['html']; ?>
</div>

<div class="modal-footer">
	<a class="btn btn-cancel" data-dismiss="modal" id="cPop" style="color:white;"><?php echo TR('cancel'); ?></a>
	<a class="btn btn-shop" id="envOkModal" style="color:white;"><?php echo TR('ok'); ?></a>
</div>
