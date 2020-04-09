<?php  

	session_start();
	include_once '../../lib/j/j.func.php';
	include_once '../../lib/php/calcCache.php';
	// print2($_SESSION);

	$_POST['proyectoId'] = 2;
	// print2($_POST);
	// $marcas = getMarcas($elem,$_POST['proyectoId']);
	// print2($marcas);
	$bloques = getBloques($_POST['proyectoId'],$elem);
	// print2($bloques);
	$proyectos = $db->query("SELECT * FROM Proyectos")->fetchAll(PDO::FETCH_ASSOC);
	// print2($proyectos);
?>

<script type="text/javascript">
	$(document).ready(function() {
		var proyectoId = <?php echo $_POST['proyectoId']; ?>;
		// var bloques = <?php echo atj($bloques); ?>;
		// optsSel(bloques,$('#bSel'),false,'- - - Selecciona un bloque - - -')
		// console.log(bloques);
		var wId = <?php echo $_POST['wId']; ?>;
		if(typeof(wData[wId].tipo) == 'undefined'){
			wData[wId].tipo = 1;
			wData[wId].grafica = 'line';
			wData[wId].promedio = 0;
			wData[wId].analisis = 1;
			wData[wId].bId = '';
			wData[wId].bloque = '';
			wData[wId].aId = '';
			wData[wId].area = '';
			wData[wId].pId = '';
			wData[wId].pregunta = '';
			wData[wId].nombre = 'Sin datos';
			// wData[wId].nombre = 'Total';

		}

		$('#tSel').change(function(event) {
			var tipo = $(this).val();
			// console.log(tipo);
			$('.trT').hide();
			$('.selT').removeClass('oblig');
			var grSel = wData[wId].grafica == 'apiladas'?'line':wData[wId].grafica;
			var prSel = wData[wId].grafica == 'apiladas'?0:wData[wId].promedio;
			$('#gSel').val(grSel);
			$('#promSel').val(prSel);
			$('#trGrafica').show();
			$('#trProm').show();
			switch(tipo){
				case '2':
					$('.bloque').show();
					$('#bSel').addClass('oblig');
					break;
				case '3':
					$('.area').show();
					$('#aSel').addClass('oblig');
					break;
				case '4':
					$('.pregunta').show();
					$('#pSel').addClass('oblig');
					$('#trGrafica').hide();
					$('#trProm').hide();
					$('#promSel').val(0);

					break;
			}
			// $('.delT').val();
		});

		// console.log(wData[wId]);

		$('#prySel').change(function(event) {
			var pryId = $(this).val();
			if(pryId != ''){
				cuestj = jsonF('reportes/comparativo/json/getAreaPreg.php',{busq:'checklist',proyectoId:pryId})
				// console.log(cuestj);
				var cuest = $.parseJSON(cuestj);
			}else{
				var cuest = [];
			}
			optsSel(cuest,$('#cSel'),false,'- - - Selecciona un cuestionario - - -');
			$('#cSel').trigger('change');
		});

		$('#cSel').change(function(event) {
			var cId = $(this).val();
			if (cId != '') {
				bloquesj = jsonF('reportes/comparativo/json/getAreaPreg.php',{busq:'bloques',cuestionarioId:cId})
				// console.log(bloquesj);
				var bloques = $.parseJSON(bloquesj);
			}else{
				var bloques = [];
			}
			optsSel(bloques,$('#bSel'),false,'- - - Selecciona un bloque - - -');
			$('#bSel').trigger('change');

		});

		$('#bSel').change(function(event) {
			// var bId = $(this).val();
			var bloque = $(this).find(':selected').attr('class');
			// console.log('bloque',bloque)
			if(typeof bloque != 'undefined'){			
				var areasj = jsonF('reportes/comparativo/json/getAreaPreg.php',{busq:'areas',bloque:bloque,proyectoId:proyectoId});
				console.log(areasj);
				var areas = $.parseJSON(areasj);
			}else{
				var areas = [];
			}
			optsSel(areas,$('#aSel'),false,'- - - Selecciona un área - - -');
			$('#aSel').trigger('change');
		});
		$('#aSel').change(function(event) {
			// var bId = $(this).val();
			var area = $(this).find(':selected').attr('class');
			if (typeof area != 'undefined') {			
				var preguntasj = jsonF('reportes/comparativo/json/getAreaPreg.php',{busq:'preguntas',area:area,proyectoId:proyectoId});
				// console.log(preguntasj);
				var preguntas = $.parseJSON(preguntasj);
			}else{
				var preguntas = [];
			}
			optsSel(preguntas,$('#pSel'),false,'- - - Selecciona una pregunta - - -');
		});

		$('#tSel').val(wData[wId].tipo);
		$('#tSel').trigger('change');
		
		// console.log(wData[wId].bId);
		$('#gSel').val(wData[wId].grafica);
		$('#promSel').val(wData[wId].promedio);
		$('#anSel').val(wData[wId].analisis);

		$('#prySel').val(wData[wId].pryId != 0?wData[wId].pryId:"");
		$('#prySel').trigger('change');
		
		$('#cSel').val(wData[wId].cId != 0?wData[wId].cId:"");
		$('#cSel').trigger('change');
		
		$('#bSel').val(wData[wId].bId != 0?wData[wId].bId:"");
		$('#bSel').trigger('change');
		
		$('#aSel').val(wData[wId].aId != 0?wData[wId].aId:"");
		$('#aSel').trigger('change');

		$('#pSel').val(wData[wId].pId != 0?wData[wId].pId:"");

		$('#modal').css({width:''});
		$('#env').click(function(event) {

			var oblig = camposObligatorios('#nEmp');

			if(oblig){				
				var wId = <?php echo $_POST['wId']; ?>;
				wData[wId] = {};
				// wData[wId].tipo = $('#tSel').val();
				wData[wId].tipo = 4;
				// wData[wId].grafica = $('#gSel').val();
				wData[wId].grafica = 'apiladas';
				// switch(wData[wId].tipo){
				// 	case '1':
				// 	case '2':
				// 	case '3':
				// 		wData[wId].grafica = $('#gSel').val();
				// 		break;
				// 	case '4':
				// 		wData[wId].grafica = 'apiladas';
				// 		break;
				// 	default:
				// 		wData[wId].grafica = $('#gSel').val();
				// 		break;
				// }
				// wData[wId].promedio = $('#promSel').val();



				wData[wId].promedio = 0;
				wData[wId].analisis = $('#anSel').val();
				wData[wId].pryId = $('#prySel').val();
				wData[wId].cId = $('#cSel').val();
				wData[wId].etapa = $('#cSel :selected').attr('class');
				wData[wId].bId = $('#bSel').val();
				wData[wId].bloque = $('#bSel :selected').attr('class');
				wData[wId].aId = $('#aSel').val();
				wData[wId].area = $('#aSel :selected').attr('class');
				wData[wId].pId = $('#pSel').val();
				wData[wId].pregunta = $('#pSel :selected').attr('class');
				// var bNom = $('#bSel').val() != "" && $('#tSel').val() == 2?' - '+$('#bSel :selected').text():"";
				// var aNom = $('#aSel').val() != "" && $('#tSel').val() == 3?' - '+$('#aSel :selected').text():"";
				// var pNom = $('#pSel').val() != "" && $('#tSel').val() == 4?' - '+$('#pSel :selected').text():"";
				var cNom = $('#cSel').val() != ""?$('#cSel :selected').text():"";
				var bNom = $('#bSel').val() != ""?' - '+$('#bSel :selected').text():"";
				var aNom = $('#aSel').val() != ""?' - '+$('#aSel :selected').text():"";
				var pNom = $('#pSel').val() != ""?' - '+$('#pSel :selected').text():"";
				var anNom = $('#anSel').val() == "1"?"": ' ( '+$('#anSel :selected').text()+' ) ';
				// wData[wId].nombre = $('#tSel :selected').text()+bNom+aNom+pNom+anNom;
				wData[wId].nombre = cNom+bNom+aNom+pNom+anNom;

				// console.log(wData[wId].grafica);
				// console.log(wData[wId].tipo);
				$('#popUp').modal('toggle');
			}
		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Configuración del análisis</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<!-- <tr>
				<td>Tipo:</td>
				<td>
					<select class="form-control" id="tSel">
						<option value="1">Total</option>
						<option value="2">Bloque</option>
						<option value="3">Área</option>
						<option value="4">Pregunta</option>
					</select>
				</td>
			</tr> -->
			<!-- <tr id="trGrafica">
				<td>Gráfica:</td>
				<td>
					<select class="form-control" id="gSel">
						<option value="line">Lineas</option>
						<option value="column">Barras</option>
					</select>
				</td>
			</tr> -->
			<!-- <tr id="trProm">
				<td>Promedio de datos:</td>
				<td>
					<select class="form-control" id="promSel">
						<option value="0">No</option>
						<option value="1">Sí</option>
					</select>
				</td>
			</tr> -->
			<tr>
				<td>Análisis por:</td>
				<td>
					<select class="form-control" id="anSel">
						<option value="1">Ninguno</option>
						<option value="2">Proyecto</option>
						<option value="4">Estado</option>
						<option value="5">Municipios</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Proyecto:</td>
				<td>
					<select id="prySel" class="form-control">
						<option value="">- - - Selecciona un proyecto - - -</option>
						<?php foreach ($proyectos as $p){ ?>
							<option value="<?php echo $p['id']; ?>"><?php echo $p['nombre']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Cuestionario:</td>
				<td>
					<select id="cSel" class="form-control">
						<option value="">- - - Selecciona un cuestionario - - -</option>
					</select>
				</td>
			</tr>
			<tr style=";" class="trT bloque area pregunta ">
				<td>Bloque:</td>
				<td>
					<select class="form-control selT" id="bSel">
						<option value="">- - - Selecciona un bloque - - -</option>
					</select>
				</td>
			</tr>
			<tr style=";" class="trT pregunta area">
				<td>Área:</td>
				<td>
					<select class="form-control selT" id="aSel">
					</select>
				</td>
			</tr>
			<tr style=";" class="trT pregunta">
				<td>Pregunta:</td>
				<td>
					<select class="form-control selT oblig" id="pSel">
					</select>
				</td>
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
