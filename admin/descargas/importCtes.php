<?php  

	include_once '../../lib/j/j.func.php';

	
	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {

		var archConc = '';
		$('#env').click(function(event) {

			if(archConc != '' || archDir != ''){
				loading();
			}

			var ok = true;
			setTimeout(function () {
				if(archConc != ''){
					var rj = jsonF('lib/scripts/cargaClientes.php',{
						archivo:archConc
					});
					$('#baseCarga').show();
					// console.log(rj);
					var r = $.parseJSON(rj);
					$('#infoCarga').html(r.resp);
					if(r.ok == 1){						
					}else{
						ok = false
					}
				}

				if(ok){
					removeLoading();
					alerta('success','El archivo fue importado satisfactoriamente');
				}else{
					removeLoading();
					alerta('danger','Hubo un error al importar el archivo : Err:'+e.err);
				}

			}, 100);


		});

		$filename = "importIU_<?= strtotime("now") ?>_filename_";
		subArch($('#archivoConcentrado'),13,$filename,'csv',false,function(a){
			archConc = a.prefijo+a.nombreArchivo;
		})


	});
</script>
<div style="text-align: center;margin-bottom: 15px;">
	<h2>Importación de clientes</h2>
</div>

<div  style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td style="text-align: right;">Descargar plantilla:</td>
				<td>
					<a href="../archivos/IU-Plantillas-Clientes.csv" target="_blank"> <span id="descPlantClientes" class="btn btn-sm btn-shop"><i class="glyphicon glyphicon-download-alt"></i></span></a>
				</td>
				<td style="text-align: right;">Cargar archivo de clientes:</td>
				<td>
					<div id="archivoConcentrado">...</div>
				</td>
				<td></td>
			</tr>
		</table>		
	</form>
</div>
<div>
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Ejecutar importación</span>
	</div>
</div>
	<div id="baseCarga" display="none">
		<div id="infoCarga"></div>
	</div>