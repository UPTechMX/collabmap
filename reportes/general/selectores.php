
<?php

if($_POST['ajax'] == 1){
	@session_start();
	include_once '../../lib/j/j.func.php';
}else{
	// print2($_SESSION);
	$_POST['proyectoId'] = $_SESSION['pub']['priv'][0]['proyectosId'];
}
// print2($_GET);
// print2($_POST);

$Act = $_REQUEST['Act'];
$usrId = $_SESSION['pub']['usrId'];
$mId = isset($_POST['mId'])?$_POST['mId']:0;

if(count($_SESSION['pub']['priv']) == 1){

}else{

}

foreach ($_SESSION['pub']['priv'] as $k => $p) {
	if($p['proyectosId'] == $_POST['proyectoId'])
		break;
}
$priv = $_SESSION['pub']['priv'][$k]; 
// print2($priv);
$dimElemId = $priv['dimensionesElemId'];
$nivel = is_numeric($priv['nivel'])?$priv['nivel']:0;
$cId = $priv['cId'];
$pryId = $priv['proyectosId'];

$datDimElem = $db-> query("SELECT * FROM DimensionesElem 
	WHERE id = $dimElemId")->fetch(PDO::FETCH_ASSOC);
$nomDimElem = $datDimElem['nombre'];

$dims = $db->query("SELECT * FROM Dimensiones WHERE clientesId = $cId ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

if($nivel != 0){
	$padres = array();
	buscaPadres($dimElemId,$padres);
	// print2($padres);
}else{
	$padres = array();
}

?>

<script type="text/javascript">
	// tiempoGral = 0;
	$(document).ready(function() {

		

		var repeticiones = $('#repSel').SumoSelect({
			placeholder: 'Selecciona los periodos',
		});
		$('.cambiaDim').change(function(event) {
			$('#dwlGral').hide();
		});
		$('.selDim').change(function(event) {
			var nivel = parseInt(this.id.split('_')[1])+1;
			var valor = $(this).val();

			$.each($('[id^=selDim_]'), function(index, val) {
				 var num = this.id.split('_')[1];
				 if(num >= nivel){
				 	$(this).empty();
				 	var o = new Option('- - - - - - - - - -',"");
				 	$(this).append(o);
				 }
			});

			if(valor != ''){
				var dimsj = jsonF('reportes/general/json/getDims.php',{nivel:nivel,elem:valor});
				console.log(dimsj);
				var dims = $.parseJSON(dimsj);
				for(var i in dims){
					var o = new Option(dims[i].nombrePub,dims[i].id);
					$('#selDim_'+nivel).append(o);
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
				$('#selDim_'+j).empty();
				var o = new Option(padres[i-1].nombrePub,padres[i-1].eId);
				$('#selDim_'+j).append(o);
				$('#selDim_'+j).addClass('desactivado');
				$('#selDim_'+j).prop('disabled', true);

				if(i == 1){
					$('#selDim_'+j).trigger('change');
				}
				j++;

			}
		}

		$('#calcular').click(function(event) {
			tiempoGral = 0;

			$('#dwlGral').show();
			var nivel = 0;
			var elem = 0;
			$.each($('[id^=selDim_]'), function(index, val) {
				if (nivel < parseInt(this.id.split('_')[1])+1 && $(this).val() != '') {
					nivel = parseInt(this.id.split('_')[1])+1;
					elem = $(this).val();
					// console.log('entro',nivel,elem);
				}
			});

			var reps = $('#repSel').val();

			var proyectoId = <?php echo $_POST['proyectoId']; ?>;

			var mId = <?php echo $mId; ?>;
			$.each($('.dNPS'), function(index, val) {
				 npsId = this.id.split('_')[1];
				 $(this).load(rz+'reportes/general/NPS.php',{
				 	npsId: npsId, reps: reps,elem:elem,proyectoId:proyectoId,mId:mId} ,function(){
				 		tiempoGral += parseFloat(tiempo);
				 	// console.log(tiempo);
				 	// console.log(tiempoGral);
				 });
			});

			$('#pregImp').load(rz+'reportes/general/pregImp.php',{
				reps:reps,elem:elem,proyectoId:proyectoId,mId:mId} ,function(){
					tiempoGral += parseFloat(tiempo);
				// console.log(tiempo);
				// console.log(tiempoGral);
			});



			// console.log('asas');
			var totj = jsonF('reportes/general/json/getDatos.php',{nivel:nivel,elem:elem,reps:reps,proyectoId:proyectoId,mId:mId});
			
			// var pba = jsonF('reportes/pba.php',{nivel:nivel,elem:elem,reps:reps,proyectoId:proyectoId,mId:mId});
			// console.log(pba);


			var tot = $.parseJSON(totj);
			parseaObjeto(tot);
			// console.log(tot);
			tiempoGral += parseFloat(tot.tiempo);

			var califTot = !isNaN(parseFloat(tot.tot).toFixed(2))?parseFloat(tot.tot).toFixed(2)+'%':'- -';
			$('#califTot').text(califTot);

			$('#totMarcas').empty();
			for(var m in tot.marcas){
				var marca = tot.marcas[m];
				var div = '<div id="dMarca_'+marca.id+'" class="col-md-3" >'+
					'<div style="text-align:center;">'+
					'<h3 id="hMarca_'+marca.id+'" class="manita hMarca" style="color:#004785;">'+marca.nombre+'</h3>'+
					'</div>'+
					'<div id="graf_'+marca.id+'" style="height:300px"></div>'+
				'</div>';
				$('#totMarcas').append(div);
				var totM = parseFloat(parseFloat(marca.total).toFixed(2))
				grafVel($('#graf_'+marca.id),totM);
			}

			$('#totMarcas').on('click', '.hMarca', function(event) {
				event.preventDefault();
				var mId = this.id.split('_')[1];
				// console.log(mId);
				popUp('reportes/general/iframe.php',{mId:mId,pryId:proyectoId},function(e){},{});
			});

			var cats = [];
			var tmp = {name:'Total',y:parseFloat(califTot),cuenta:tot.cuenta,drilldown:'Total',color:'#004785'}
			cats.push(tmp);
			for(var c in tot.dims){
				// console.log(tot.dims[c]);
				var total = parseFloat(tot.dims[c]['total']).toFixed(2);
				var tmp = {'name':tot.dims[c].nombre,'y':parseFloat(total),'drilldown':tot.dims[c].nombre,cuenta:tot.dims[c].cuenta}
				if(!isNaN(tmp.y)){
					cats.push(tmp);
				}
			}
			// console.log(cats);
			barrasDD($('#gBarrasSubs'),cats,tot.drilldown);

			setTimeout(function () {
				console.log('Tiempo de calculos del reporte general = '+tiempoGral+' Segundos');
			}, 5000);


		});


		$('#calcular').trigger('click');

		$('#dwlGral').click(function(event) {
			// var quotes = document.getElementById('contTodo');

			html2canvas($('#divGral'), {
			    onrendered: function(canvas) {

			    	var imgData = canvas.toDataURL('image/png');
			    	var pdf=new jsPDF("p", "mm", "legal");
			    	var width = pdf.internal.pageSize.width;    
			    	var height = pdf.internal.pageSize.height;
			    	pdf.addImage(imgData, 'JPEG', 10, 5,width-20,height-20);
			    	var nomArch = 'General';
			    	pdf.save(nomArch);



			    }
			});

		});



	});
</script>

<div class="row">
	<?php
		$i = 0;
		$numDim = count($dims);
		// foreach ($dims as $d){
		for($i = 0;$i<$numDim;$i++){
			if($i == 0){
				$elems = $db->query("SELECT * FROM DimensionesElem 
					WHERE dimensionesId = ".$dims[$i]['id']." ORDER BY nombrePub")->fetchAll(PDO::FETCH_ASSOC);
			}
	?>
			<div class="col-md-3">
				<div class="nuevo"><?php echo $dims[$i]['nombre'] ?></div>
				<select class="cambiaDim form-control <?php echo $i == ($numDim-1)?"":'selDim' ?>" id="selDim_<?php echo $i;?>">
					<option value="">- - - - - - - -</option>
					<?php 
						if ($i == 0 && $nivel == 0){ 
							foreach ($elems as $e){
								echo "<option value='$e[id]'>$e[nombrePub]</option>";
							} 
						} 
					?>
				</select>
			</div>
	<?php } ?>
	<div class="col-md-3" >
		<div class="nuevo">Periodo</div>
		<select id="repSel" class="cambiaDim" style="margin-bottom: 5px;height: 30px;" multiple="multiple" height="10px">
			<!-- <option disabled="disabled" value="">- - - - - - - -</option> -->
			<?php 
				$reps = $db->query("SELECT * FROM Repeticiones WHERE proyectosId = $pryId AND elim IS NULL 
					ORDER BY fechaIni DESC")->fetchAll(PDO::FETCH_ASSOC);
				foreach ($reps as $r){ ?>
				<option value="<?php echo $r['id']; ?>" selected><?php echo "$r[nombre]"; ?></option>
			<?php } ?>
		</select>
		<!-- <span class="btn btn-shop btn-sm" id="addRep"><i class="glyphicon glyphicon-plus"></i>&nbsp;Agregar</span> -->
		<div style="text-align: right;">
			<span class="btn btn-sm btn-shop" id="calcular">Calcular</span>
			<span class="btn btn-sm btn-shop" id="dwlGral" style="display: none;">Descargar</span>
		</div>

	</div>
</div>
