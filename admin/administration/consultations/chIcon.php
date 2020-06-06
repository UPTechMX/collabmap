<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Consultations

	$ij = file_get_contents('json/fa.json');
	$icons = json_decode($ij,true);
	// print2($ij);
	// echo $icons;

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.iconSelDiv').click(function(event) {
			console.log('aaa');
			$('.iconSelDiv').css({border:'none'});
			$(this).css({border:'solid 1px'});
			var icon = $(this).find('i').attr('class').split(' ')[1];
			$('#icon').val(icon);

			$('#divIco').empty();
			$('#divIco').append('<i class="fas '+icon+' fa-2x"></i>')

			$('#popUpMapa').modal('toggle');
			// $('#icoTmp').val(icon);
		});

		// $('#updIcon').click(function(event) {
		// });

	});
</script>
<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('consultation'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<div style="padding: 20px;">
		<div class="row">	
			<?php foreach ($icons as $i){ ?>
				<div class="col-1 iconSelDiv" style="font-size: 1.3em; padding: 3px;">
					<div style="text-align: center;">
						<i class="fas fa-<?php echo $i ;?>"></i>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<input type="hidden" id="icoTmp">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<!-- <span id="updIcon" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span> -->
	</div>
</div>
