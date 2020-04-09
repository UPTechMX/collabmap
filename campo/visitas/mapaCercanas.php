<?php  
session_start();
if(!function_exists('raiz')){
	include_once '../../lib/j/j.func.php';
}
// print2($_SESSION);
include_once raiz().'lib/php/campo.php';
$uId = $_SESSION['CM']['admin']['usrId'];
// print2($uId);
// echo "usuario : $uId<br/>";
$usuario = new campo($uId);


// $visitas = $usuario -> getVisHoySinUsuario(30, 19.4, -99.5);
// $estatus = $db->query("SELECT  * FROM Estatus ORDER BY id")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
// $visitasFut = $usuario -> visFut('visitas confirmadas futuras',33,37);

// $capGral = $shopper->getCaps(true);

// print2($visitas);
// print2($visitasAnt);

switch($etapaCampo){
	case "visitas":
	$var1 = "visitas";
	$txtBusqueda = "visitas cercanas";
	$txtBoton = "esta visita";
	break;
	case "seguimiento":
	$var1 = "seguimientoCampo";
	$txtBusqueda = "seguimientos cercanos";
	$txtBoton = "este seguimiento";
	break;
	case "instalaciones":
	$var1 = "instalaciones";
	$txtBusqueda = "instalaciones cercanas";
	$txtBoton = "esta instalación";
	break;
}

?>


<script type="text/javascript">

	var mapa, startPos;
	$(function() {


		$( "#slider" ).slider({
			value:2,
			min: 0,
			max: 10,
			step: 1,
			slide: function( event, ui ) {
				
			}
		});




		$('#cercanas-tab').click(function(){

			setTimeout(function() {
				mapa = new Mapa(null, "mapaDiv", "panelDiv");
				<?php if($etapaCampo == "instalaciones") { ?>
					mapa.bubbleButton(true, "Ver datos");
				<?php }else{ ?>
					mapa.bubbleButton(true, "Proceder a <?php echo $txtBoton;?>");
				<?php } ?>
				mapa.changeActiveMarker(true);
				mapa.setFuncionClick(funcionClick);
				mapa.creaMapa();			
			}, 400);

		});


		// mapa.setManyCoordsAsGroup("Cercanas", visitas, estatus[k][0].icono, estatus[k][0].icono);



		$('#btnBuscarCercano').click(function(ev){

			ev.preventDefault();

			if (!navigator.geolocation) {
				alert('Tu navegador no soporta servicios de geolocalización.');
				return;
			}
			var geoOptions = {
				maximumAge: 5 * 60 * 1000,
				timeout: 10 * 1000
                // enableHighAccuracy: true
            }

            var geoError = function(error) {
             	console.log('Error occurred. Error code: ' + error.code, error);	
             	// error.code can be: 	//   0: unknown error 	//   1: permission denied 	//   2: position unavailable (error response from location provider) 	//   3: timed out
             	switch(error.code){
             		case 1:
             		alertar("Esta funcionalidad requiere que permitas al navegador acceder a tu ubicación.", function(){}, {});
             		break;
             		case 2:
             		alertar("Tu ubicación no está disponible, favor de verificar su accesibilidad.", function(){}, {});
             		break;
             	}
            };

            var geoSuccess = function(position) {
            	startPos = position;

            	console.log("POSITION ", startPos)
            	
            	$('#ubicGeo').show();

            	// statId = $('#estatusSel').val();
            	// if(statId)
            	// 	mapa.markerMasCercano(startPos.coords.latitude, startPos.coords.longitude, {grupo: "Estatus_"+statId, alerta:true});
            	// else
            	// 	mapa.markerMasCercano(startPos.coords.latitude, startPos.coords.longitude, {alerta: true} );
            	
            	mapa.addUserPosition(startPos.coords.latitude, startPos.coords.longitude);

            	try{

            		var distMax = $('#distMaxima').val();
            		if(!isNumeric(distMax)){
            			$('#distMaxima').val(5);
            			distMax = 5;
            		}

            		var dat = {distMax: distMax,  lat: startPos.coords.latitude, lng : startPos.coords.longitude};
            		var rj = jsonF('campo/json/cercanos.php',{datos:dat, tipoV:"<?php echo $etapaCampo; ?>"});

            		// console.log("cercanos", rj);

            		var visitas = $.parseJSON(rj);

            		console.log("cercanos", visitas);

            		if(visitas.length < 1){
            			alertar("Por ahora no hay visitas por realizar ingresadas en el sistema a una distancia menor de "+distMax+" kilómetros de tu posición actual")
            		}

            		mapa.removeAll(); 

            		<?php
            		if($etapaCampo != "instalaciones") {
            			?>
            			mapa.setManyCoordsAsGroup("Cercanas", visitas, "blue", "red");		
            			<?php
            		}else{
            			?>
            			listas=[];
            			faltanCompromisos=[];
            			reparaciones =[];

            			for(vis in visitas){
            				if(visitas[vis].estatus == 55)
            					reparaciones.push(visitas[vis]);
            				else{
            					if(visitas[vis].avComp < 1)
            						faltanCompromisos.push(visitas[vis]);
            					else
            						listas.push(visitas[vis]);
            				}
            			}
            			console.log("alterados", listas, faltanCompromisos);            			
            			mapa.setManyCoordsAsGroup("listas", listas, "blue", "blue");		
            			mapa.setManyCoordsAsGroup("faltanCompromisos", faltanCompromisos, "red", "red");		
            			mapa.setManyCoordsAsGroup("reparaciones", reparaciones, "purple", "purple");		
            			<?php
            		}
            		?>
            		mapa.dibuja();

            	}catch(e){
            		console.log('error de parseo', e);
            		console.log(rj);
            	}


            };

            navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);	

         

        });


		<?php
		if($etapaCampo != "instalaciones"){
			?>
			funcionClick = function(){

				console.log(mapa.activeMarker);

				var etapa = '<?php echo $var1; ?>';

				vId = mapa.activeMarker.visita;
				cId = mapa.activeMarker.valor;

    			// console.log(vId.length,typeof vId, cId);
    			if(vId == null){
    				// console.log('cId');
    				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:etapa,acc:1});
    				// console.log(rj);
    				var r = $.parseJSON(rj);

    				if(r.ok == 1){
    					vId = r.nId;
    				}
    			}
    			// console.log(vId);

    			// popUpCuest('admin/proyectos/respCuest.php',{vId:vId},function(){})
    			setTimeout(function(){
    				$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
    			},100);

    		};
    		<?php 
    	}else{
    		?>
    		funcionClick = function(){

				console.log(mapa.activeMarker);

				var etapa = '<?php echo $var1; ?>';

				vId = mapa.activeMarker.visita;
				cId = mapa.activeMarker.valor;
				listo = mapa.activeMarker.listp;


    			// console.log(vId.length,typeof vId, cId);
    			if(listo) {
    				if(vId == null){
    				    // console.log('cId');
    				    var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:etapa,acc:1});
    				    // console.log(rj);
    				    var r = $.parseJSON(rj);

    				    if(r.ok == 1){
    				    	vId = r.nId;
    				    }
    				}
    				setTimeout(function(){
    					$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
    				},100);
    			}else{

    				$('.trPDIT').load(rz+'campo/instalaciones/clienteFila.php',{cId:cId} ,function(){}).attr("id", "asdf_"+cId);
    			}
    		};

    		<?php
    	}
    	?>



		$('#plusDInfoTabla').on('click', '.verAvComp', function(event) {



			var cteId = $(this).closest('tr').attr('id').split('_')[1];
			var vvId = this.id.split('_')[1];

			// console.log(vvId,cteId);

			popUp('admin/proyectos/verAvComp.php',{cteId:cteId,vId:vvId});

		});



		$('#plusDInfoTabla').on('click', '.instalar', function(event) {
			// console.log('aa');
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId, cId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'instalacion',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}
			console.log(vId);

			// popUpCuest('admin/proyectos/respCuest.php',{vId:vId},function(){})
			setTimeout(function(){
				$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			},100);

		});

		$('#plusDInfoTabla').on('click', '.verUbic', function(event) {

			var lat = $(this).attr('lat');
			var lng = $(this).attr('lng');
			// console.log(vvId,cteId);

			popUp('lib/j/php/verUbic.php',{lat:lat,lng:lng});

		});

		$('#plusDInfoTabla').on('click', '.histRot', function(event) {
			event.preventDefault();
			var cteId = this.id.split('_')[1];
			console.log(cteId);
			popUp('admin/proyectos/cHist.php',{cteId:cteId},function(){},{});
		});

		$('#plusDInfoTabla').on('click', '.impCuest', function(event) {
			event.preventDefault();
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId,cId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'impacto',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}

			$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			
		});
		
		$('#plusDInfoTabla').on('click', '.evCuest', function(event) {
			event.preventDefault();
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'evaluacionInt',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}

			$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			
		});

		$('#plusDInfoTabla').on('click', '.reparar', function(event) {
			event.preventDefault();
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'reparacion',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}

			$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			
		});






    });



</script>

<br>
<h3>Buscar <?php echo $txtBusqueda; ?>.</h3>

<div class="row">
	<div class="col-md-12 " style="">
		 Distancia máxima en km:
		<input type=number id="distMaxima" class="form-control" value=2 style="width:150px; margin-left:20px" />
		<br>
		<div class="btn btn-shop" id="btnBuscarCercano" value=5 >Buscar</div><br><br>
	</div>
</div>

<div id="mapaDiv" style="height:65vh; background-color:lightGray"></div>
<div id="plusDInfo"></div>
<table class="table" id="plusDInfoTabla"><tr class="trPDIT"></tr></table>