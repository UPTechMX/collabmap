<script type="text/javascript">
	$(document).ready(function() {
		$('#dwl_<?php echo $_POST['pId']; ?>').click(function(event) {
			/* Act on the event */
			$('<form>')
			.attr({
				id: 'formAb_<?php echo $_POST['pId']; ?>',
				action: rz+'analysis/checklist/dwlAb.php',
				target:'_blank',
				method:'post'
			})
			.html(
				'<input type="text" name="trgtChk" value="<?php echo $_POST['trgtChk']; ?>"\>'+
				'<input type="text" name="pId" value="<?php echo $_POST['pId']; ?>"\>'+
				'<input type="text" name="nivelMax" value="<?php echo $_POST['nivelMax']; ?>"\>'+
				'<input type="text" name="padre" value="<?php echo $_POST['padre']; ?>"\>'
			)
			.appendTo(document.body)
			.submit()
			.remove();
		});

	});
</script>

<div style="margin: 20px;" id="divAnswer_<?php echo $_POST['pId']; ?>">
	<span class="btn btn-shop btn-lg" id="dwl_<?php echo $_POST['pId']; ?>">
		<i class="glyphicon glyphicon-download-alt"></i>
		<?php echo TR('dwlAns'); ?>		
	</span>
</div>




