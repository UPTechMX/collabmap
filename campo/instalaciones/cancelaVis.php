<?php

include_once '../../lib/j/j.func.php';
session_start();
// print2($_SESSION);
?>

<script type="text/javascript">
	$(document).ready(function() {
		var contador = 20;
		$('#comentariosTx').keyup(function(event) {
			var comsTx = $("#comentariosTx").val();
			var count = comsTx.trim().length;
			// console.log(count);
			var contador = 20 - count;
			if(contador <= 0){
				$('#env').show();
				$('#contador').hide();
			}else{
				$('#env').hide();
				$('#contador').show();
				$('#contador').text(contador);
			}

		});

		$('#comentarios').change(function(event) {
			var coms = $(this).val();
			console.log(coms);
			if(coms != ''){
				$('#env').show();
			}else{
				$('#env').hide();
			}
			
			if(coms == -1){
				$('.comentariosTx').show();
				$('#env').hide();
			}else{
				$('.comentariosTx').hide();
			}
		});

		$('#env').click(function(event) {
			var vId = <?php echo $_POST['vId']; ?>;
			var coms = $("#comentarios").val();

			if(coms == '-1'){
				coms = $('#comentariosTx').val();
			}
			var count = coms.trim().length;
			// console.log(count);
			// consol
			if(coms != '' && coms.length >= 20){
				var rj = jsonF('campo/visitas/json/json.php',{acc:2,vId:vId,coms:coms});
				var r = $.parseJSON(rj);;
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#tabAsignadas').trigger('click');
				}
			}
			// console.log(rj);
		});
	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Cancelar visita</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	Cancelarás la visita y quedará abierta para ser tomada nuevamente por un shopper.
	Para poder continuar, deberás explicar los motivos de la cancelación.
	<br/><br/>
	<select class="form-control" id="comentarios" name="comentarios">
		<option value=''>- - -Selecciona una razón de cancelación- - -</option>
		<!-- <option value="Inconformidad de pago">Inconformidad de pago</option> -->
		<option value="Temas personales">Temas personales</option>
		<option value="Lejanía de la visita">Lejanía de la visita</option>
		<option value="No me interesó la dinámica">No me interesó la dinámica</option>
		<option value="Falta de tiempo">Falta de tiempo</option>
		<!-- <option value="Tiempos de pago">Tiempos de pago</option> -->
		<option value="-1">Otros</option>
	</select>
	<div style="text-align: right;">
		<span id="contador" style="font-size: smaller; display:none;" class="comentariosTx" >20</span>
	</div>
	<textarea id="comentariosTx" class="form-control comentariosTx" style="display: none;"></textarea>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Salir</span>
		<span id="env" class="btn btn-sm btn-cancel" style="display: none;">Cancelar visita</span>
	</div>
</div>
