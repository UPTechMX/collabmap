<?php
	session_start();
	include_once '../../lib/j/j.func.php';

	checaAcceso(50); // checaAcceso analysis;
	$projects = $db->query("SELECT * FROM Projects") -> fetchAll(PDO::FETCH_ASSOC);
	$pcs = $db->query("SELECT pc.id, pc.name as pcName, p.name as pName
		FROM PublicConsultations pc
		LEFT JOIN Projects p ON p.id = pc.projectsId
	") -> fetchAll(PDO::FETCH_ASSOC);
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

		$('#envTrgt').click(function(event) {
			var trgtChk = $('#trgtChk').val();
			if(trgtChk != ''){
				$('#chChkTrgtForm').submit();
			}
		});

		$('#chPcSel').change(function(event) {
			var pcId = $(this).val();
			console.log(pcId);
			$('#pcId').val(pcId);
			
		});


		$('#envPC').click(function(event) {
			var pcId = $('#pcId').val();
			if(pcId != ''){
				$('#chChkPCForm').submit();
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
	<ul class="nav nav-tabs" id="chChkTab" role="tablist">
	  <li class="nav-item">
	    <a class="nav-link active" id="targets-tab" data-toggle="tab" 
	    	href="#targets" role="tab" aria-controls="targets" aria-selected="true"><?php echo TR('targets'); ?></a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link " id="pc-tab" data-toggle="tab" 
	    	href="#pc" role="tab" aria-controls="pc" aria-selected="false"><?php echo TR('publicCons'); ?></a>
	  </li>
	</ul>
	<div class="tab-content" id="chChkTabContent">
	  <div class="tab-pane fade show active" id="targets" role="tabpanel" aria-labelledby="targets-tab">
	  	<div style="margin-top: 10px;">

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
	  		<form id="chChkTrgtForm" method="get" >
	  			<input type="hidden" name="trgtChk" id="trgtChk" value="">
	  			<input type="hidden" name="acc" id="acc" value="trgt">
	  		</form>
	  		<div class="modal-footer">
	  			<div style="text-align: right;">
	  				<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
	  				<span id="envTrgt" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	  			</div>
	  		</div>

	  	</div>
	  </div>
	  <div class="tab-pane fade " id="pc" role="tabpanel" aria-labelledby="pc-tab">
	  	<div style="margin-top: 10px;">			  		

	  		
	  		<form id="nEmp">
	  			<table class="table" border="0">
	  				<tr>
	  					<td><?php echo TR('publicCons'); ?></td>
	  					<td>
	  						<select class="form-control" id="chPcSel">
	  							<option value="">- - - <?php echo TR('publicCon'); ?> - - -</option>
	  							<?php foreach ($pcs as $pc){
	  								$name = empty($pc['pName'])?$pc['pcName']:"$pc[pName] / $pc[pcName]";
	  							?>
	  								<option value="<?php echo $pc['id']; ?>"><?php echo $name; ?></option>
	  							<?php } ?>
	  						</select>
	  					</td>
	  					<td></td>
	  				</tr>
	  			</table>		
	  		</form>
	  		<form id="chChkPCForm" method="get" >
	  			<input type="hidden" name="pcId" id="pcId" value="">
	  			<input type="hidden" name="acc" id="acc" value="pc">
	  		</form>
	  		<div class="modal-footer">
	  			<div style="text-align: right;">
	  				<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
	  				<span id="envPC" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	  			</div>
	  		</div>

	  		
		</div>
	  </div>

</div>
