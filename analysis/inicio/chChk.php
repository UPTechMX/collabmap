<?php
	session_start();
	include_once '../../lib/j/j.func.php';

	checaAcceso(50); // checaAcceso analysis;
	$projects = $db->query("SELECT * FROM Projects") -> fetchAll(PDO::FETCH_ASSOC);
	// print2($projects);
?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#chPrjSel').change(function(event) {
			var prjId = $(this).val();
			var r = [];
			if(prjId != ''){
				var rj = jsonF('analysis/inicio/json/findElems.php',{option:'targets',prjId:prjId});
				r = $.parseJSON(rj);
				console.log(r);
			}

			optsSel(r,$('#chTrgtSel'),false,'- - - <?php echo TR('target'); ?> - - -',false);
			$('#chTrgtSel').val('');
			$('#chTrgtSel').trigger('change');


		});

		$('#chTrgtSel').change(function(event) {
			var trgtId = $(this).val();
			var r = [];
			if(trgtId != ''){
				var rj = jsonF('analysis/inicio/json/findElems.php',{option:'checklist',trgtId:trgtId});
				r = $.parseJSON(rj);
			}

			optsSel(r,$('#chChkSel'),false,'- - - <?php echo TR('survey'); ?> - - -',false);
			$('#chChkSel').val('');
			$('#chChkSel').trigger('change');
		});

		$('#chChkSel').change(function(event) {
			var trgtChk = $(this).val();
			console.log(trgtChk);
			$('#trgtChk').val(trgtChk);
			
		});


		$('#env').click(function(event) {
			var trgtChk = $('#trgtChk').val();
			if(trgtChk != ''){
				$('#chChkForm').submit();
			}
		});
	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('chChk'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR('project'); ?></td>
				<td>
					<select class="form-control" id="chPrjSel">
						<option value="">- - - <?php echo TR('projects'); ?> - - -</option>
						<?php foreach ($projects as $p){ ?>
							<option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('target') ?></td>
				<td>
					<select class="form-control" id="chTrgtSel">
						<option value="">- - - <?php echo TR('target') ?> - - -</option>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('survey') ?></td>
				<td>
					<select class="form-control" id="chChkSel">
						<option value="">- - - <?php echo TR('survey') ?> - - -</option>
					</select>
				</td>
				<td></td>
			</tr>
		</table>		
	</form>
</div>
<form id="chChkForm" method="get" >
	<input type="hidden" name="trgtChk" id="trgtChk" value="">
</form>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
