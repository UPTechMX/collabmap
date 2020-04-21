<?php

checaAcceso(50); // checaAcceso analysis;
// print2()
if(!is_numeric($_GET['trgtChk'])){
	exit();
}
$targetChecklist = $db->query("SELECT * FROM TargetsChecklist WHERE id = $_GET[trgtChk]")->fetchAll(PDO::FETCH_ASSOC)[0];
if(empty($targetChecklist)){
	exit('EXIT');
}

// print2($_GET);

$checklistId = $targetChecklist['checklistId'];

// print2($targetChecklist);
$findEst = $db->query("SELECT * FROM ChecklistEst WHERE checklistId = $checklistId")->fetchAll(PDO::FETCH_ASSOC);
if(empty($findEst)){
	// echo "AQUI";
	$estExt = estructuraEXT($checklistId);
	$prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $checklistId, estructura = ?");
	$prep -> execute(array(atj($estExt)));
	$estj = atj($estExt);
}else{
	// echo "ACA";
	$estj = $findEst[0]['estructura'];
}

$est = json_decode($estj,true);

$pregsSp = array();
$pregsNumMult = array();
foreach ($est['bloques'] as $b) {
	foreach ($b['areas'] as $a) {
		foreach ($a['preguntas'] as $p) {
			if($p['tipo'] == 'sub'){
				foreach ($p['subpregs'] as $sp) {
					if($sp['tipo'] == 'op'){
						$pregsSp[] = $sp;
					}		
					if($sp['tipo'] == 'num' || $sp['tipo'] == 'mult'){
						$pregsNumMult[] = $sp;
					}		
				}
			}
			if($p['tipo'] == 'op'){
				$pregsSp[] = $p;
			}
			if($p['tipo'] == 'num' || $p['tipo'] == 'mult'){
				$pregsNumMult[] = $p;
			}		

		}
	}
}



$target = $db->query("SELECT t.*, tc.frequency 
	FROM TargetsChecklist tc 
	LEFT JOIN Targets t ON t.id = tc.targetsId
	WHERE tc.id = $_GET[trgtChk]
")->fetchAll(PDO::FETCH_ASSOC)[0];


$dims = $db->query("SELECT * FROM Dimensiones 
	WHERE elemId = $target[id] AND type = 'structure' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);


include_once 'spatialSMJS.php';

?>

<script type="text/javascript">
	var mapSM;
	var layerPointsSM;
	var layerHeatSM;
	$(document).ready(function() {
		var pregsNumMult = <?php echo atj($pregsNumMult); ?>;
		// console.log(pregsNumMult);
		$('#SMStructureFilter .dimSelSM').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('#SMStructureFilter').find('.dimSelSM').length;
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles){
				// console.log('aa');
				var r = []
				if(dimElemId != ''){
					var rj = jsonF('analysis/socialMon/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
				}
				var nextNivel = parseInt(dimNivel)+1;
				var elemSel = $(this).closest('#SMStructureFilter').find('#dimSelSM_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

		// SMSpQuestionSel
		// SMNumQuestionSel

		$('#SMNumQuestionSel').change(function(event) {
			var pId = $(this).val();
			$('.qMods').hide();
			if(pId != ''){
				var type = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('tipo');
				var index = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('index');

				if(type == 'num'){
					$('.numMod').show();
				}else if(type == 'mult'){
					var preg = pregsNumMult[index];
					// console.log(preg);
					$('.multMod').show();
					$('#SMAnswer').empty();
					var o = new Option('- - - <?php echo TR('answer'); ?> - - -','');
					// $('#SMAnswer').append(o);
					for(var i in preg.respuestas){
						var resp = preg.respuestas[i][0];
						// console.log(i,resp);
						var o = new Option(resp['respuesta'],resp.id);
						$('#SMAnswer').append(o);
					}
				}
			}
		});

		$('#generateSM').click(function(event) {
			var pId = $('#SMNumQuestionSel').val();
			if(pId != ''){
				var type = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('tipo');
				var index = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('index');
				var ans = $('#SMAnswer').val();
				var value = $('#SMValue').val();
				var inequality = $('#SMInequality').val();
				var spatialQuestion = $('#SMSpQuestionSel').val();
				var ok = false;

				switch(type){
					case 'mult':
						ok = ans != '';
						break
					case 'num':
						ok = inequality != '' && value != '' && !isNaN(value);
						break;
					default:
						ok = false;
						break;
				}

				var nivelMax = 0;
				var padre = 0;
				$.each($('.dimSelSM'), function(index, val) {
					var nivel = this.id.split('_')[1];
					if($(this).val() != '' && nivel > nivelMax){
						nivelMax = parseInt(nivel);
						padre = $(this).val();
					}
				});
				var trgtChk = <?php echo $_GET['trgtChk']; ?>;

				if(ok){				
					var rj = jsonF('analysis/socialMon/json/getAnswersMap.php',{
						type:type,
						index:index,
						ans:ans,
						value:value,
						inequality:inequality,
						nivelMax:nivelMax,
						padre:padre,
						pId:pId,
						spatialQuestion:spatialQuestion,
						trgtChk:trgtChk
					})
					// console.log(rj);
					try{

						var r = $.parseJSON(rj);
						// console.log(r);
						$('#tipoMapaSMCont').show();
						$('#mapSM').remove();
						$('#mapContSM').append('<div id="mapSM"style=" height: 500px;margin-top: 10px;" class="map"></div>');
						mapSM = initMapMS(spatialQuestion, r);
					}catch(e){
						console.log('Error de parso');
						console.log(rj);
					}

					var tipoMapaSM = $('input[name="tipoMapaSM"]:checked').val();
					switch(tipoMapaSM){
						case 'P':
							pintaPuntos();
							break;
						case 'C':
							pintaCalor();
							break;
					}

				}

			}
			$('#genChartSM').trigger('click');

		});

		$('#genChartSM').click(function(event) {
			// console.log('click');
			var pId = $('#SMNumQuestionSel').val();
			if(pId != ''){
				var type = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('tipo');
				var index = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('index');
				var ans = $('#SMAnswer').val();
				var value = $('#SMValue').val();
				var inequality = $('#SMInequality').val();
				var spatialQuestion = $('#SMSpQuestionSel').val();
				var numAnt = $('#numAntSM').val();
				var ok = false;

				switch(type){
					case 'mult':
						ok = ans != '';
						break
					case 'num':
						ok = inequality != '' && value != '' && !isNaN(value);
						break;
					default:
						ok = false;
						break;
				}
				

				var nivelMax = 0;
				var padre = 0;
				$.each($('.dimSelSM'), function(index, val) {
					var nivel = this.id.split('_')[1];
					if($(this).val() != '' && nivel > nivelMax){
						nivelMax = parseInt(nivel);
						padre = $(this).val();
					}
				});
				var trgtChk = <?php echo $_GET['trgtChk']; ?>;

				if(ok){
					var rj = jsonF('analysis/socialMon/json/getAnswersChart.php',{
						type:type,
						index:index,
						ans:ans,
						value:value,
						inequality:inequality,
						nivelMax:nivelMax,
						padre:padre,
						pId:pId,
						spatialQuestion:spatialQuestion,
						trgtChk:trgtChk,
						numAnt:numAnt
					})
					// console.log(rj);
					try{
						var r = $.parseJSON(rj);
						var series = [];
						var datas = {};
						for(var i = r.dates.length-1; i>0 ;i--){
							if(i > 0){
								var tmp = {};
								tmp.name = r.dates[i]+' / '+r.dates[i-1];
								tmp.finalDate = r.dates[i-1];
								tmp.initDate = r.dates[i];
								tmp.data = [];
								series.push(tmp);
							}
						}


						if(type == 'mult'){

							var type = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('tipo');
							var index = $('#SMNumQuestionSel option[value="'+pId+'"]').attr('index');
							var preg = pregsNumMult[index];
							var catsY = [];
							var catsYIndex = {};
							catsY.push(0);
							console.log(preg.respuestas);
							var k = 1;
							for(var i in preg.respuestas){
								var resp = preg.respuestas[i][0];
								
								catsY.push(resp.respuesta);
								catsYIndex[resp.respuesta] = k++;
							}
						}
						// console.log('catsY',catsY);
						
						var cats = [];
						var k = 0;
						var catsIndex = {};
						for(var i = 0; i<r.answers.length; i++){
							var resp = r.answers[i];
							// console.log(resp);
							if(!cats.includes(resp['deName'])){
								cats.push(resp['deName']);
								catsIndex[cats[i]] = k++;
							}
							

							var finishDate = resp['finishDate'].split(' ')[0];
							for(var j = 0; j<series.length;j++){
								var initDate = series[j].initDate;
								var finalDate = series[j].finalDate;

								if(initDate < finishDate && finalDate >= finishDate){
									// console.log(finishDate,series[j]['name']);
									var tmpS = {};
										tmpS.x = catsIndex[resp['deName']];
									if(type == 'num'){
										tmpS.y = parseInt(resp.rvDatValue);
									}else if(type == 'mult'){
										console.log('catsYIndex[resp.respName]',resp.respName,catsYIndex[resp.respName]);
										tmpS.y = catsYIndex[resp.respName];
										tmpS.name = resp.respName;
									}
									// console.log('DATA: ',series[j]['data']);
									series[j]['data'].push(tmpS);
								}

							}
						}

						var seriesFinal = [];
						for(i = 0;i<series.length;i++){
							if(series[i].data.length == 0){
								continue;
							}
							seriesFinal.push(series[i]);
						}

						// console.log('series',series);
						// console.log('cats',cats);
						// console.log('catsIndex',catsIndex);
						console.log(r.answers);
					}catch(e){
						// console.log('Error de parso');
						// console.log(e);
						// console.log(rj);
					}


					if(type == 'num'){
						barChartSMnum($('#chartSM'), seriesFinal, cats,'');
					}else if(type == 'mult'){
						barChartSMmult($('#chartSM'), seriesFinal, cats,'',catsY);
					}

				}

			}

		});


		$("input[name='tipoMapaSM']").change(function(event) {
			var tipo = $('input[name="tipoMapaSM"]:checked').val();
			// console.log(tipo);
			switch(tipo){
				case 'P':
					pintaPuntos();
					break;
				case 'C':
					pintaCalor();
					break;
			}
		});

		soloNumeros($('#numAntSM'));


	});
</script>

<div id="socialMon">
	<div style="background-color: whitesmoke;padding: 10px;border-radius: 5px;" id="SMStructureFilter">
		<h4><?php echo TR('structureFilter'); ?></h4>
		<div class='row'>
			<?php 
			foreach ($dims as $k => $d){
				if($k > count($dims)-2){
					break;
				}
				if($k == 0){
					$dimsElems = $db->query("SELECT * FROM DimensionesElem 
						WHERE dimensionesId = $d[id]
						ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
				}else{
					$dimsElems = array();
				}
			?>
				<div class="col-3">
					<select class="form-control dimSelSM" id="dimSelSM_<?php echo "$d[nivel]"; ?>">
						<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
						<?php foreach ($dimsElems as $de){ ?>
							<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
		</div>
	</div>
	<div style="background-color: whitesmoke;padding: 10px;border-radius: 5px;margin-top: 10px;" id="questionsFilter">
		<h4><?php echo TR('questionFilter'); ?></h4>
		<div class='row' style="margin-top: 10px;">
			<div class="col-3">
				<h5><?php echo TR('spatialQuestion'); ?></h5>
				<select class="form-control" id="SMSpQuestionSel">
					<?php foreach ($pregsSp as $sp){ ?>
						<option value="<?php echo $sp['id']; ?>"><?php echo $sp['pregunta']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-3">
				<h5><?php echo TR('numericQuestion'); ?></h5>
				<select class="form-control" id="SMNumQuestionSel">
					<option value="">- - - <?php echo TR('question'); ?> - - -</option>
					<?php foreach ($pregsNumMult as $index => $nq){ ?>
						<option value="<?php echo $nq['id']; ?>" tipo="<?php echo $nq['tipo']; ?>" index="<?php echo $index; ?>" >
							<?php echo $nq['pregunta']; ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="col-3 qMods numMod" style="display:none;">
				<h5><?php echo TR('inequality'); ?></h5>
				<select class="form-control" id="SMInequality">
					<option value=">"><?php echo TR('greater-than'); ?></option>
					<option value=">="><?php echo TR('greater-or-equal'); ?></option>
					<option value="="><?php echo TR('equal'); ?></option>
					<option value="<="><?php echo TR('less-or-equal'); ?></option>
					<option value="<"><?php echo TR('less-than'); ?></option>
				</select>
			</div>
			<div class="col-3 qMods numMod" style="display:none;">
				<h5><?php echo TR('value'); ?></h5>
				<input type="text" id="SMValue" class="form-control" value="10"/>
			</div>
			<div class="col-3 qMods multMod" style="display:none;">
				<h5><?php echo TR('answers'); ?></h5>
				<select class="form-control" id="SMAnswer"></select>
			</div>

		</div>
		<div style="text-align: left;margin-top: 10px;">
			<span class="btn btn-shop" id="generateSM"><?php echo TR('generate'); ?></span>
		</div>
	</div>

	<div id="mapContSM">
		<div style="margin-top: 10px;display:none;" id="tipoMapaSMCont">
			<input type="radio" name="tipoMapaSM" id="tipoP" value="P" checked="checked" >&nbsp;<label for="tipoP">
				<?php echo TR('markers'); ?>
			</label>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="tipoMapaSM" id="tipoC" value="C">&nbsp;<label for="tipoC">
				<?php echo TR('heat'); ?>
			</label>
		</div>

	</div>
	<div id="chartContSM" style="margin-top: 10px;">
		<div style="background-color: whitesmoke;padding: 10px;border-radius: 5px;" id="SMStructureFilter">
			<h4><?php echo TR('chart'); ?></h4>
			<div class='row'>
				<div class="col-4" style="text-align: right;">
					<h5>
						<?php echo TR('numAnt'); ?>
					</h5>
				</div>
				<div class="col-1">
					<input type="text" class="form-control" id="numAntSM" value="5">

				</div>
				<div class="col-4" style="display: none;">
					<span id="genChartSM" class="btn btn-shop" ><?php echo TR('genChart'); ?></span>
				</div>
			</div>
		</div>
		<div id="chartSM"></div>
	</div>

</div>
