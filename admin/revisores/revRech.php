<script type="text/javascript">
	$(document).ready(function() {
		soloNumeros($('#shopCalif'))
	});
</script>
<div class="modal-header" style="text-align:center;">
	<h4>Confirmar acción</h4>
</div>
<div class="modal-body">
	Con esta acción declaras que la información contenida
	en esta visita requiere ser revisada por un ejecutivo con el shopper</div>
	<table class="table" style="margin-top: 10px">
		<tr>
			<td width="30%">Calificación al shopper</td>
			<td></td>
			<td>
				<select id="shopCalif" class="form-control">
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
			</td>
		</tr>
	</table>
<div class="modal-footer">
	<a class="btn btn-cancel" data-dismiss="modal" id="cPop">Cancelar</a>
	<a class="btn btn-shop" id="envOkModal">Aceptar</a>
</div>
