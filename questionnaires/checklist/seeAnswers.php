<?php
	session_start();
	$root = $_SESSION['CM']['raiz'];
	include_once $root.'lib/j/j.func.php';
	include_once raiz().'lib/php/checklist.php';
	include_once raiz().'lib/php/calcCuest.php';
	$chk = new Checklist($_POST['vId']);


	checaAccesoQuest();

?>
<script type="text/javascript">
	var from = 'questionnaires';
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title"><?php echo TR('survey'); ?></h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<div id="contentAnswers">
		<?php include_once $root.'checklist/visita.php'; ?>
	</div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('close'); ?></span>
	</div>
</div>
