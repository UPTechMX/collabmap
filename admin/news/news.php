<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso news

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#newsAdd').click(function(event) {
			popUp('admin/news/newsAdd.php',{});
		});
	});
</script>

<div class="nuevo"><?php echo TR('news'); ?></div>
<div style="text-align: right;margin-bottom: 10px;">
	<span class="btn btn-shop" id="newsAdd"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('news'); ?></span>
</div>
<div id="newsList" style="max-height: 500px;overflow-y: auto;"><?php include_once 'newsList.php'; ?></div>