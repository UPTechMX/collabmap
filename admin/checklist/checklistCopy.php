<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	// $clientes = $db-> query("SELECT * FROM Clientes")->fetchAll(PDO::FETCH_ASSOC);
	$etapas = $db->query("SELECT * FROM Etapas ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#etapa').change(function(event) {
			var eleId = $(this).val();
			if(eleId != ""){				
				var chksj = jsonF('admin/checklist/json/json.php',{opt:4,acc:3,eleId:eleId});
				var chks = $.parseJSON(chksj);
				optsSel(chks,$('#checklistsSel'),false,"");
			}else{
				$('#checklistsSel').empty()
				var o = new Option("- - - - - - - -","");
				$('#checklistsSel').append(o);
			}
			$('#checklistsSel').trigger('change');
		});
		$('#checklistsSel').change(function(event) {
			var checklistId = $(this).val();
			if(checklistId != ""){
				$('#edtChklist').show();
			}else{
				$('#edtChklist').hide();
			}
		});



		$('#env').click(function(event) {
			var checklistId = $('#checklistsSel').val();

			conf('Se copiará el checklist a esta etapa.',{checklistId:checklistId},function(e){
				var rj = jsonF('admin/checklist/json/checklistCopyScript.php',{checklistId:e.checklistId})
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
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
			Copiar checklist
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
				<td>Etapa</td>
				<td>
					<select id="etapa" name="etapa" class="form-control oblig">
						<option value="">- - - Etapa - - -</option>
						<?php foreach ($etapas as $e){ ?>
							<option value="<?php echo $e['nomInt']; ?>"><?php echo $e['nombre']; ?></option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Checklist</td>
				<td>
					<select class="form-control" id="checklistsSel">
						<option value="">- - - - - - - -</option>
					</select>
				</td>
				<td></td>
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Copiar</span>
	</div>
</div>
