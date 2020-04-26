<?php 

	// print_r($_COOKIE);
	include '../j.func.php';

?>

<div class="modal-header" style="text-align:center;">
	<h4><?php echo TR('alert'); ?></h4>
</div>
<div class="modal-body">
	<?php echo $_POST['html']; ?>
</div>

<div class="modal-footer">
	<a class="btn btn-shop" data-dismiss="modal" id="envOkModal" style="color:white;"><?php echo TR('ok'); ?></a>
</div>
