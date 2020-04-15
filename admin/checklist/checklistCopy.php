<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	$checklist = $db->query("SELECT * FROM Checklist ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
	// $clientes = $db-> query("SELECT * FROM Clientes")->fetchAll(PDO::FETCH_ASSOC);
	// $etapas = $db->query("SELECT * FROM Etapas ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {


		$('#env').click(function(event) {
			var checklistId = $('#checklistsSel').val();

			conf('<?php echo TR('copySurveyMessage'); ?>.',{checklistId:checklistId},function(e){
				var rj = jsonF('admin/checklist/json/checklistCopyScript.php',{checklistId:e.checklistId})
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					var chkName = $('#checklistsSel option:selected').text()+'_Copy';
					var o = new Option(chkName,r.nId);
					$('#chkSel').append(o);
					$('#chkSel').val(r.nId).trigger('change');
					$('#popUp').modal('toggle');

					// $('#checklistList').load(rz+'admin/proyectos/checklist/checklistList.php',{ajax:1});
				}
			});
		});

	});
</script>
<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4>
			<?php echo TR('copySurvey'); ?>
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
				<td><?php echo TR('survey'); ?></td>
				<td>
					<select class="form-control" id="checklistsSel">
						<option value="">- - - - - - - -</option>
						<?php foreach ($checklist as $c){ ?>
							<option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
							
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('copy'); ?></span>
	</div>
</div>
