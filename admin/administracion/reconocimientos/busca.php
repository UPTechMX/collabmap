<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);
?>


<script type="text/javascript">
	$(document).ready(function() {

		// console.log(municipios);
		optsSel(proyectos,$('#fBusq #proyectosId'),false,"- Proyecto -")
		optsSel(estados,$('#fBusq #estadosId'),false,"- Estado -")

		direcciones(estados,municipios,'fBusq');

		for(var i in paramsBusq){
			if(paramsBusq[i]){
				console.log(i,paramsBusq[i]);
				$('#fBusq #'+i).val(paramsBusq[i]).trigger('change');
			}
		}

		if(paramsBusq['codigoPostal'] != ''){
			setTimeout(function(){

				$('#fBusq #codigoPostal').trigger('blur');
			},100)
		}
		if(paramsBusq['coloniaSel']){

			setTimeout(function(){
				$('#fBusq #coloniaSel').val(paramsBusq['coloniaSel']).trigger('change');
			},100)
		}



		$('#busqCte').click(function(event) {
			// paramsBusq = $("#fBusq").serializeObject();
			paramsBusq = $("#fBusq").serializeObject();
			paramsBusq.estadosId = $('#fBusq #estadosId').val();
			paramsBusq.municipiosId = $('#fBusq #municipiosId').val();
			paramsBusq.coloniaSel = $('#fBusq #coloniaSel').val();
			paramsBusq.colonia = $('#fBusq #colonia').val();

			console.log(paramsBusq);
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
			$('#reconocimientosList').load(rz+'admin/administracion/reconocimientos/reconocimientosList.php',{paramsBusq:paramsBusqMod})
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
				<td colspan="2">Nombre</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="text" name="nombre" id="nombre" class="form-control" >
				</td>
			</tr>
			<tr>
				<td width="50%">Estado</td>
				<td width="50%">Municipio</td>
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
				<td id="tdColonia">
					<select id="coloniaSel" class="form-control " style="display: none;"></select>
					<input type="text"  name="colonia" value="<?php echo $datC['colonia']; ?>" id="colonia" class="form-control" >
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
