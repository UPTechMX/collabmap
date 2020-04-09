<?php

	include_once '../../lib/j/j.func.php';
	// print2($_POST);
	checaAcceso(50);
	$chk = $db -> query("SELECT * FROM Checklist WHERE id = $_POST[chkId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($chk);

?>



<script type="text/javascript">
	$(document).ready(function() {

		$('#env').click(function(event) {
			var chkId = <?php echo $chk['id']; ?>;
			var tipoProm = $('#tipoProm').val();
			var tipoAnalisis = $('#tipoAnalisis').val();
			
			var rj = jsonF('admin/checklist/json/json.php',
				{
					datos:{id:chkId,tipoProm:tipoProm,tipoAnalisis:tipoAnalisis},
					acc:2,
					opt:11,
					chkId:chkId
				});
			// console.log(rj);
			var r = $.parseJSON(rj);
			// console.log(r);
			if(r.ok == 1){
				$('#popUp').modal('toggle');
			}

		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Tipo de promedio del checklist</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td>Tipo del promedio</td>
				<td>
					<select class="form-control" id="tipoProm">
						<option value="1" <?php echo $chk['tipoProm'] == 1 ? 'selected':''; ?> >Por preguntas</option>
						<option value="2" <?php echo $chk['tipoProm'] == 2 ? 'selected':''; ?> >Suma de promedios de los bloques</option>
						<option value="3" <?php echo $chk['tipoProm'] == 3 ? 'selected':''; ?> >Promedio de la suma de promedios de los bloques</option>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Tipo de análisis en visita</td>
				<td>
					<select class="form-control" id="tipoAnalisis">
						<option value="2" <?php echo $chk['tipoAnalisis'] == 2 ? 'selected':''; ?> >Por bloques</option>
						<option value="1" <?php echo $chk['tipoAnalisis'] == 1 ? 'selected':''; ?> >Por áreas</option>
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
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>


