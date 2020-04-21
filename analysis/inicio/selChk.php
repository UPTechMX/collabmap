<script type="text/javascript">
	$(document).ready(function() {
		$('#selChk').click(function(event) {
			popUp('analysis/inicio/chChk.php',{},function(){},{});
		});
	});
</script>

<div style="text-align: center;">
	<div style="height: 200px;width: 300px;border: solid 1px #DDD; margin-left: auto; margin-top: 10px;
		margin-right: auto;border-radius: 10px;padding-top: 80px;background-color:whitesmoke; " class="manita" id="selChk">
		<?php echo TR('selChk'); ?>
	</div>
</div>