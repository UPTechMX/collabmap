<?php  

	session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(5); // checaAcceso analysis;

	// exit();

	// print2($_POST);

	if( !is_numeric($_POST['nivelMax']) || !is_numeric($_POST['padre']) 
		|| !is_numeric($_POST['targetsId']) || !is_numeric($_POST['chkId']) ){
		exit('ERROR');
	}
	// print2($_POST);
	
	// print2($_SERVER);
	$url = "http://".$_SERVER['SERVER_NAME'];
	$url .= $_SERVER['PHP_SELF'];

	$url = str_replace('getURL.php', 'getCSV.php', $url);

	$url .= "?";
	foreach ($_POST as $k => $v) {
		$url .= $k."=".$v."&";
	}

	$usrId = $_SESSION['CM']['admin']['usrId'];
	$hash = encriptaUsr("CM_$usrId");
	$hash = str_replace('$2y$10$', '', $hash);

	$url .= "u=$usrId&h=$hash";

?>



<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo 'CSV URL'; ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>

	<div style="width: 100%;overflow-x: auto;">
		<?= $url; ?>
	</div>

</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop"><?php echo TR('close'); ?></span>
	</div>
</div>
