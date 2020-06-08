<?php

session_start();
if(!function_exists('raiz')){
	include '../../lib/j/j.func.php';
}

	$news = $db->query("SELECT * FROM News WHERE id = $_POST[newsId] ")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($_POST);
	// print2($news);
?>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo $news['name']; ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<div class="newsHeader1" style="font-size: 1.2em;font-weight: bold;text-transform: uppercase;">
		<?php echo $news['header']; ?>
	</div>
	<div class="newsContent1" style="margin-top: 10px; text-align: justify;">
		<?php echo $news['news']; ?>
	</div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop"><?php echo TR('ok'); ?></span>
	</div>
</div>
