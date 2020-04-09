<?php

if (!function_exists('raiz')) {
	include_once '../lib/j/j.func.php';
}

checaAcceso(10);
$municipios = $db->query("SELECT m.estadosId, m.id as val, m.nombre as nom, 'clase' as clase 
	FROM Municipios m ORDER BY m.nombre")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

$proyectos = $db->query("SELECT id as val, nombre as nom, 'clase' as clase 
	FROM Proyectos 
	WHERE (inactivo != 1 OR inactivo IS NULL) ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

$estados = $db->query("SELECT  e.id as val, e.nombre as nom, 'clase' as clase 
	FROM Estados e ORDER BY e.nombre")->fetchAll(PDO::FETCH_ASSOC);

?>


<script type="text/javascript">
	var estados = <?php echo atj($estados); ?>;
	var municipios = <?php echo atj($municipios); ?>;
	var proyectos = <?php echo atj($proyectos); ?>;
	// console.log(proyectos);
	$(document).ready(function() {

		// Once a barcode had been read successfully, stop quagga and 
		// close the modal after a second to let the user notice where 
		// the barcode had actually been found.
		leido = 0;
		Quagga.onDetected(function(result){
			if (result.codeResult.code){
				if(leido == 0){
					leido = 1;
					// $('#nombre').val(result.codeResult.code);
					$('#livestream_scanner').modal('toggle');
					var paramsBusq = {};
					paramsBusq.nombre = result.codeResult.code;
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
					$('#clientesLista').load(rz+'campo/visitas/clientesLista.php',{paramsBusq:paramsBusqMod})

					// console.log('aaaaa');
					// playSound();
					// Quagga.stop();
					// setTimeout(function(){ $('#livestream_scanner').modal('hide'); }, 1000);
					// setTimeout(function(){ console.log('aa'); }, 1000);
					// $('#busqCte').trigger('click');
					setTimeout(function(){leido = 0;},2000)
				}
			}
		});



		paramsBusq = {};
		$('#buscCliente').click(function(event) {
			popUp('campo/visitas/buscaCte.php',{},function(){},{})
		});
		$('#addCte').click(function(event) {
			// console.log('aa');;
			popUp('general/clientes/clientesAdd.php',{ext:1},function(){},{})
			
		});
	});
</script>


<div style="margin:10px;" class="row">
	<div class="col-4">
		<span class="btn btn-sm btn-shop" id="addCte">Agregar cliente</span>
	</div>
	<div class="col-8" style="text-align: right;">
		<span class="btn btn-sm btn-shop" data-toggle="modal" data-target="#livestream_scanner"> 
			<i class="glyphicon glyphicon-barcode"></i>
		</span>
		<span id="buscCliente" class="btn btn-sm btn-shop">
			<i class="glyphicon glyphicon-search"></i>
		</span>
	</div>
</div>
<div id="clientesLista"></div>