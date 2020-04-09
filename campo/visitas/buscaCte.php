<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(10);

?>


<script type="text/javascript">
	$(document).ready(function() {

		// console.log(municipios);
		optsSel(proyectos,$('#fBusq #proyectosId'),false,"- Proyecto -")
		optsSel(estados,$('#fBusq #estadosId'),false,"- Estado -")

		$('#fBusq #estadosId').change(function(event) {
			var edoId = $(this).val();
			try{
				var r = municipios[edoId];
				optsSel(r,$('#fBusq #municipiosId'),false,'- Municipio -');
				$('#fBusq #municipiosId').trigger('change');
			}catch(e){
				console.log('error de parseo');
				console.log(rj)
			}
		});

		for(var i in paramsBusq){
			$('#fBusq #'+i).val(paramsBusq[i]).trigger('change');
		}
		// $('#fBusq #estadosId').trigger('change');

		$('#busqCte').click(function(event) {
			paramsBusq = $("#fBusq").serializeObject();
			// console.log(paramsBusq);
			var paramsBusqMod = {};
			for(var i in paramsBusq){
				if(paramsBusq[i]!=''){				
					if(i == 'nombre' || i == 'colonia'){
						paramsBusqMod[i] = '%'+paramsBusq[i]+'%';
					}else{
						paramsBusqMod[i] = paramsBusq[i];
					}
				}
			}


			$('#popUp').modal('toggle');
			$('#clientesLista').load(rz+'campo/visitas/clientesLista.php',{paramsBusq:paramsBusqMod})
		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			Buscar cliente
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="fBusq">
		<table class="table" border="0">
			<tr>
				<td width="50%">Proyecto</td>
				<td width="50%">Nombre / token</td>
			</tr>
			<tr>
				<td>
					<select id="proyectosId" name="proyectosId" class="form-control"></select>
				</td>
				<td>
					<input type="text" name="nombre" id="nombre" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Estado</td>
				<td>Municipio</td>
			</tr>
			<tr>
				<td>
					<select id="estadosId" name="estadosId" class="form-control"></select>
				</td>
				<td>
					<select id="municipiosId" name="municipiosId" class="form-control">
						<option value="">- Municipio -</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>CP</td>
				<td>Colonia</td>
			</tr>
			<tr>
				<td>
					<input type="text" name="codigoPostal" id="codigoPostal" class="form-control" >
				</td>
				<td>
					<input type="text" name="colonia" id="colonia" class="form-control" >
				</td>
			</tr>

		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="busqCte" class="btn btn-sm btn-shop">
			<i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
		</span>
	</div>
</div>
