
<?php

if($_POST['ajax'] == 1){
	@session_start();
	include_once '../../lib/j/j.func.php';
}else{
	// print2($_SESSION);
	$_POST['proyectoId'] = 2;
}
// print2($_GET);
// print2($_POST);

$Act = $_REQUEST['Act'];
$mId = isset($_POST['mId'])?$_POST['mId']:0;


// print2($priv);
$dimElemId = $priv['dimensionesElemId'];
$nivel = is_numeric($priv['nivel'])?$priv['nivel']:0;
$cId = $priv['cId'];
$pryId = $priv['proyectosId'];


$padres = array();

?>

<script type="text/javascript">
	$(document).ready(function() {

		var repeticiones = $('#repSelComp').SumoSelect({
			placeholder: 'Selecciona los periodos',
		});

		$('.selDimComp').change(function(event) {
			var nivel = parseInt(this.id.split('_')[1])+1;
			var valor = $(this).val();

			$.each($('[id^=selDimComp_]'), function(index, val) {
				 var num = this.id.split('_')[1];
				 if(num >= nivel){
				 	$(this).empty();
				 	var o = new Option('- - - - - - - - - -',"");
				 	$(this).append(o);
				 }
			});

			if(valor != ''){
				var dimsj = jsonF('reportes/general/json/getDims.php',{nivel:nivel,elem:valor});
				// console.log(dimsj);
				var dims = $.parseJSON(dimsj);
				for(var i in dims){
					var o = new Option(dims[i].nombrePub,dims[i].id);
					$('#selDimComp_'+nivel).append(o);
				}
			}
		});

		var nivel = <?php echo $nivel; ?>;
		// console.log('nivel',nivel);
		if(nivel > 0){
			var padres = <?php echo atj($padres); ?>;
			// console.log(padres);
			var j = 0;
			for(var i = padres.length;i>0;i--){
				// console.log(padres[i-1]);
				$('#selDimComp_'+j).empty();
				var o = new Option(padres[i-1].nombrePub,padres[i-1].eId);
				$('#selDimComp_'+j).append(o);
				$('#selDimComp_'+j).addClass('desactivado');
				$('#selDimComp_'+j).prop('disabled', true);

				if(i == 1){
					$('#selDimComp_'+j).trigger('change');
				}
				j++;

			}
		}


		$('#calcComp').click(function(event) {
			var nivel = 0;
			var elem = 0;
			$.each($('[id^=selDimComp_]'), function(index, val) {
				if (nivel < parseInt(this.id.split('_')[1])+1 && $(this).val() != '') {
					nivel = parseInt(this.id.split('_')[1])+1;
					elem = $(this).val();
					// console.log('entro',nivel,elem);
				}
			});

			var repComp = $('#repSelComp').val();
			// var repComp = [2];

			var proyectoId = <?php echo $_POST['proyectoId']; ?>;

			console.log(wData);
			var tiempoEjec = 0;
			for(var i in wData){
				// console.log(i);
				// $('#wid_'+i).find('.nombre').text('');
				var datj = jsonF('reportes/comparativo/json/getDatos.php',{wData:wData[i],nivel:nivel,elem:elem,reps:repComp});
				console.log(datj);


				switch(wData[i].grafica){
					case 'line':
					case 'column':
						var dat = $.parseJSON(datj);
						// return;
						parseaObjeto(dat);
						// console.log(dat);
						// console.log(wData[i].nombre);
						tiempoEjec += parseFloat(dat.tiempo);
						lineas($('#wid_'+i).find('.grafica'),dat.series,dat.cats,wData[i].promedio,wData[i].grafica,wData[i].nombre);
						// function lineas(ele,dat,cats,prom,tipo,nom){

						break;
					case 'apiladas':
						var dat = $.parseJSON(datj);
						parseaObjeto(dat);
						console.log(dat);
						tiempoEjec += parseFloat(dat.tiempo);
						apiladasComp($('#wid_'+i).find('.grafica'),dat.series,dat.cats,wData[i].promedio,'column',wData[i].nombre);
						
						break;
					default:
						break;
				}
				ajustaWidget(i);

			}

			// console.log(tiempoEjec,'--');
			console.log('Tiempo de calculos del reporte comparativo = '+tiempoEjec+' Segundos');


			// var totj = jsonF('reportes/general/json/getDatos.php',{nivel:nivel,elem:elem,reps:reps,proyectoId:proyectoId,mId:mId});

		});

		

		
	});
</script>
<div class="nuevo">Periodo</div>
<select id="repSelComp" class="" style="margin-bottom: 5px;width:100%;height: 30px;" multiple="multiple" height="10px">
	<!-- <option disabled="disabled" value="">- - - - - - - -</option> -->
	<?php 
		$reps = $db->query("SELECT * FROM Proyectos")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($reps as $r){ ?>
		<option value="<?php echo $r['id']; ?>" selected><?php echo "$r[nombre]"; ?></option>
	<?php } ?>
</select>

<div class="row">
		<div style="text-align: right;">
			<span class="btn btn-sm btn-shop" id="calcComp">Calcular</span>
		</div>
	</div>
</div>
