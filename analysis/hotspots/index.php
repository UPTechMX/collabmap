<?php

checaAcceso(5); // checaAcceso analysis;
// print2()
if(!is_numeric($_GET['trgtId'])){
	exit();
}

$trgt = $db->query("SELECT * FROM Targets WHERE id = $_REQUEST[trgtId]")->fetchAll(PDO::FETCH_ASSOC)[0];
$prj =  $db->query("SELECT * FROM Projects WHERE id = $trgt[projectsId]")->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($prj);
$KMLS = $db->query("SELECT * FROM KML WHERE projectsId = $trgt[projectsId]")->fetchAll(PDO::FETCH_ASSOC);

$chks = $db->query("
	SELECT tc.id, c.id as cId, c.nombre as cName 
	FROM TargetsChecklist tc
	LEFT JOIN Checklist c ON c.id = tc.checklistId
	WHERE targetsId = $_REQUEST[trgtId]
")->fetchAll(PDO::FETCH_ASSOC);

$dims = $db->query("SELECT * FROM Dimensiones 
	WHERE elemId = $_REQUEST[trgtId] AND type = 'structure' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);
$displayStruct = count($dims) == 1? 'display:none;':'';

// print2($displayStruct);
?>

<script type="text/javascript">

	var chks = <?php echo atj($chks); ?>;
	var attrs = [];
	var map;
	var layerHeatSM;
	var layerGeoms;
	var kml_vectorgrid;
	$(document).ready(function() {
		$('#KMLSel').change(function(event) {
			var kmlId = $(this).val();
			attrs = [];
			if(kmlId != ''){
				var rj = jsonF('analysis/hotspots/json/json.php',{acc:1,elemId:kmlId,'find':"attr"});
				attrs = $.parseJSON(rj);
				$('#attrDiv, #divAdd').show();
			}else{
				$('#attrDiv, #divAdd').hide();
			}
			$('#divAttrs').empty();
			optsSel(attrs,$('#attrRow .attrSel'),false,'- - - <?php echo TR("selAttr"); ?> - - -',false);
			$('#attrRow .attrSel').val('').trigger('change');
		});

		$('#KMLS').on('change', '.attrSel', function(event) {
			event.preventDefault();
			var attrId = $(this).val();
			var type = $(this).find('option:selected').attr('class');

			// console.log(attrId,type);

			$(this).closest('.attrRow').find('.valSel').hide();
			var r = [];
			if(attrId != ''){
				switch(type){
					case 'string':
						var rj = jsonF('analysis/hotspots/json/json.php',{acc:1,elemId:attrId,'find':"attrOpt"});
						var r = $.parseJSON(rj);
						$(this).closest('.attrRow').find('.attrValDiv').show();
						break;
					case 'float':
					case 'int':
						$(this).closest('.attrRow').find('.numMod').show();
						// $('#attrValDiv').show();
						break;

				}
			}
			// console.log(r);
			optsSel(r,$(this).closest('.attrRow').find('.attrValSel'),false,'- - - <?php echo TR("attrVal"); ?> - - -',false);
			$(this).closest('.attrRow').find('.attrValSel').val('');
		});

		$('#generateHS').click(function(event) {

			var kmlId = $('#KMLSel').val();
			var chkIdspatial = $('#selChkHSspatial').val();
			var tcIdspatial = $('#selChkHSspatial option:selected').attr('tcId');
			var spatialQ = $('#HSSpQuestionSel').val();
			var spatialQType = $('#HSSpQuestionSel option:selected').attr('class');

			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelHS'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			
			kmlId = kmlId == ''?-1:kmlId;
			if(kmlId != '' && spatialQ){
				$('#tipoMapaHSCont').show();
				$('.tipoPDiv').show();
				$('#mapHS').remove();
				$('#mapContHS').append('<div id="mapHS"style=" height: 500px;margin-top: 10px;" class="map"></div>');

				var north = $('#KMLSel option:selected').attr('north');
				var south = $('#KMLSel option:selected').attr('south');
				var east = $('#KMLSel option:selected').attr('east');
				var west = $('#KMLSel option:selected').attr('west');

				var attrs = [];
				$.each($('.attrRow'), function(index, val) {
					 var tmp = {};
					 tmp.id=$(this).find('.attrSel').val(),
					 tmp.type=$(this).find('.attrSel option:selected').attr('class'),
					 tmp.numVal=$(this).find('.KMLValue').val(),
					 tmp.inequality=$(this).find('.KMLInequality').val(),
					 tmp.optVal=$(this).find('.attrValSel').val(),
					 tmp.logic=$(this).find('.logic').val() == undefined?1:$(this).find('.logic').val(),
					 attrs.push(tmp);
					 // console.log(tmp);
				});

				var questionsChk = [];
				$.each($('.rowPreg'), function(index, val) {
					 var tmp = {}
					 tmp.chkId = $(this).find('.selChkHSanalysis').val();
					 tmp.tcId = $(this).find('.selChkHSanalysis option:selected').attr('tcId');
					 tmp.questionId = $(this).find('.HSNumQuestionSel').val();
					 tmp.qType = $(this).find('.HSNumQuestionSel option:selected').attr('class');
					 tmp.inequality = $(this).find('.HSInequality').val();
					 tmp.value = $(this).find('.HSValue').val();
					 tmp.answer = $(this).find('.HSAnswer').val();
					 tmp.multType = $('#HSMultType').val();
					 tmp.numType = $('#HSNumType').val();
					 questionsChk.push(tmp);
					 // console.log(tmp);

				});


				setTimeout(function(){
					mapHS = initMapHS({
						kmlId:kmlId,
						n:north,
						s:south,
						e:east,
						w:west,
						attrs:attrs,
						chkIdspatial:chkIdspatial,
						spatialQ:spatialQ,
						spatialQType:spatialQType,
						trgtId:<?php echo $_REQUEST['trgtId'] ?>,
						tcIdspatial:tcIdspatial,
						padre:padre,
						nivelMax:nivelMax,
						questionsChk:questionsChk
					});
					var tipoMapaSM = $('input[name="tipoMapaHS"]:checked').val();
					switch(tipoMapaSM){
						case 'P':
							drawPolygons();
							break;
						case 'C':
							drawHeatmap();
							break;
						case 'M':
							drawPoints();
							break;
					}
					if(kmlId == -1){
						if(spatialQType == 'op'){
							drawHeatmap();
						}else if(spatialQType == 'spatial'){
							drawPoints();
						}
						// $('#tipoMapaHSCont').hide();
						$('.tipoPDiv').hide();
					}

				},300);
			}
		});

		$('#addAttr').click(function(event) {
			var html = jsonF('analysis/hotspots/attrRow.php',{});
			var row = $.parseHTML(html);

			// console.log(row);
			$('#divAttrs').append(row);
			// console.log(attrs);
			optsSel(attrs,$(row).find('.attrSel'),false,'- - - <?php echo TR("selAttr"); ?> - - -',false);
		});

		$('#KMLS').on('click', '.delAttr', function(event) {
			event.preventDefault();
			$(this).closest('.attrNew').remove();
		});

		$('#HSStructureFilter .dimSelHS').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('#HSStructureFilter').find('.dimSelHS').length;
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
				var elemSel = $(this).closest('#HSStructureFilter').find('#dimSelHS_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

		$('#selChkHSspatial').change(function(event) {
			var chkId = $(this).val();
			var r = [];
			if(chkId != ''){
				var rj = jsonF('analysis/hotspots/json/json.php',{acc:1,elemId:chkId,'find':"spatialQ"});
				// console.log(rj);
				r = $.parseJSON(rj);
			}

			optsSel(r,$('#HSSpQuestionSel'),false,'- - - <?php echo TR("spatialQuestion"); ?> - - -',false);
		});

		$('#chks').on('change', '.selChkHSanalysis', function(event) {
			var chkId = $(this).val();
			var r = [];
			if(chkId != ''){
				var rj = jsonF('analysis/hotspots/json/json.php',{acc:1,elemId:chkId,'find':"numericQuestions"});
				// console.log(rj);
				r = $.parseJSON(rj);

				r.push({nom:"<?php echo TR('ansNumber'); ?>",val:"ansNum",clase:'num'});
				// console.log(r);
			}

			optsSel(r,$(this).closest('.rowPreg').find('.HSNumQuestionSel'),false,'<?php echo TR("numericQuestion"); ?>',false);

			$(this).closest('.rowPreg').find('.HSNumQuestionSel').val('').trigger('change');

		});

		$('#chks').on('change', '.HSNumQuestionSel', function(event) {
			event.preventDefault();
			//      (this).closest('.rowPreg').find
			var pId = $(this).val();
			$(this).closest('.rowPreg').find('.qMods').hide();
			if(pId != ''){
				var type = $(this).closest('.rowPreg').find('.HSNumQuestionSel option[value="'+pId+'"]').attr('class');
				var index = $(this).closest('.rowPreg').find('.HSNumQuestionSel option[value="'+pId+'"]').attr('index');
				// console.log(pId,type,index);
				if(type == 'num'){
					$(this).closest('.rowPreg').find('.numModCHK').show();
				}else if(type == 'mult'){
					// var preg = pregsNumMult[index];
					// console.log(preg);
					var rj = jsonF('analysis/hotspots/json/json.php',{acc:1,elemId:pId,'find':"answers"});
					var r = $.parseJSON(rj);

					$(this).closest('.rowPreg').find('.multMod').show();
					$(this).closest('.rowPreg').find('.HSAnswer').empty();

					optsSel(r,$(this).closest('.rowPreg').find('.HSAnswer'),false,'- - - <?php echo TR("answer"); ?> - - -',false);
				}
			}
		});

		$('#chks').on('click', '.delRowPreg', function(event) {
			event.preventDefault();
			$(this).closest('.rowPreg').remove();

		});

		$('#HSInequality').change(function(event) {
			var val = $(this).val();
			if(val == 'range'){
				$('.numModInq').hide();
				$('#divPregs').empty();
			}else{
				$('.numModInq').show();
			}
		});

		$('#HSNumQuestionSel').change(function(event) {
			var qId = $(this).val();
			if(qId != ''){
				$('#addQuestion').show();
			}else{
				$('#addQuestion').hide();
				$('#divQuestions').empty();
			}
			$('#HSInequality').trigger('change');
			$('#HSNumType').trigger('change');
		});

		$('#HSInequality').change(function(event) {
			var iq = $(this).val();
			var qId =$('#HSNumQuestionSel').val();
			if(iq != 'range' && qId != ''){
				$('#addQuestion').show();
			}else{
				$('#addQuestion').hide();
				$('#divQuestions').empty();
			}
		});

		$('#HSNumType').change(function(event) {
			var iq = $(this).val();
			// console.log(iq);
			var qId =$('#HSNumQuestionSel').val();
			if((iq != '3' && iq != '4' ) && qId != ''){
				$('#addQuestion').show();
			}else{
				$('#addQuestion').hide();
				$('#divQuestions').empty();
			}
		});


		$("#addQuestion").click(function(event) {
			var html = jsonF('analysis/hotspots/questionRow.php',{});
			var row = $.parseHTML(html);

			// console.log(row);
			$('#divQuestions').append(row);

			// console.log(chks);
			for(var i = 0; i<chks.length;i++){
				var chk = chks[i];
				var o = '<option value="'+chk.cId+'" tcId="'+chk.tcId+'">'+chk.cName+'</option>'
				$(row).find('.selChkHSanalysis').append(o);
			}
			// console.log(attrs);
			// optsSel(attrs,$(row).find('.attrSel'),false,'- - - <?php echo TR("selAttr"); ?> - - -',false);

		});

		$("input[name='tipoMapaHS']").change(function(event) {
			var tipo = $('input[name="tipoMapaHS"]:checked').val();
			// console.log(tipo);
			switch(tipo){
				case 'P':
					drawPolygons();
					break;
				case 'C':
					drawHeatmap();
					break;
				case 'M':
					drawPoints();
					break;
			}
		});





	});
</script>

<?php include 'spatialHSjs.php'; ?>

<div style="margin-top: 10px;">
	<div class="nuevo"><?php echo TR('hotspots'); ?></div>

	<div style="margin-top:10px;background-color: whitesmoke;padding: 10px;border-radius: 5px;">

		<!-- CHEKLIST -->
		<div id="chks">
			<div class="row">
				<div class="col-6">
					<h5><?php echo TR('selChk'); ?></h5>
					<select class="form-control" id="selChkHSspatial">
						<option value=""><?php echo TR('selChk'); ?></option>
						<?php foreach ($chks as $c){ ?>
							<option value="<?php echo $c['cId']; ?>" tcId="<?php echo $c['id']; ?>">
								<?php echo $c['cName']; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="col-6">
					<h5><?php echo TR('spatialQuestion'); ?></h5>
					<select class="form-control" id="HSSpQuestionSel">
						<option value="">- - - <?php echo TR('spatialQuestion'); ?> - - -</option>
					</select>
				</div>
			</div>
			<div class="row rowPreg" style="margin-top: 10px;">
				<div class="col-1" style="text-align: center;">
					<h5>&nbsp;</h5>
					<span class="btn btn-shop" id="addQuestion" style="display: none;">
						<i class="glyphicon glyphicon-plus"></i>
					</span>
				</div>
				<div class="col-3">
					<h5><?php echo TR('selChk'); ?></h5>
					<select class="form-control selChkHSanalysis">
						<option value=""><?php echo TR('selChk'); ?></option>
						<?php foreach ($chks as $c){ ?>
							<option value="<?php echo $c['cId']; ?>" tcId="<?php echo $c['id']; ?>">
								<?php echo $c['cName']; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="col-2">
					<h5><?php echo TR('numericQuestion'); ?></h5>
					<select class="form-control HSNumQuestionSel" id="HSNumQuestionSel">
						<option value=""><?php echo TR('numericQuestion'); ?></option>
					</select>
				</div>
				<div class="col-2 qMods numModCHK" style="display:none;">
					<h5><?php echo TR('inequality'); ?></h5>
					<select class="form-control HSInequality" id="HSInequality">
						<option value=">"><?php echo TR('greater-than'); ?></option>
						<option value=">="><?php echo TR('greater-or-equal'); ?></option>
						<option value="="><?php echo TR('equal'); ?></option>
						<option value="<="><?php echo TR('less-or-equal'); ?></option>
						<option value="<"><?php echo TR('less-than'); ?></option>
						<option value="range"><?php echo TR('range'); ?></option>
					</select>
				</div>
				<div class="col-2 qMods numModCHK numModInq" style="display:none;">
					<h5><?php echo TR('value'); ?></h5>
					<input type="text" class="form-control HSValue" value="10"/>
				</div>
				<div class="col-2 qMods numModCHK numModInq" style="display:none;">
					<h5><?php echo TR('analysisType'); ?></h5>
					<select class="form-control HSNumType" id="HSNumType">
						<option value="1"><?php echo TR('average'); ?></option>
						<option value="2"><?php echo TR('ansNumber'); ?></option>
						<option value="3">Promedio de todas las respuestas (Traducir)</option>
						<option value="4">Suma de todas las respuestas (Traducir)</option>
					</select>
				</div>
				<div class="col-3 qMods multMod" style="display:none;">
					<h5><?php echo TR('answers'); ?></h5>
					<select class="form-control HSAnswer"></select>
				</div>
				<div class="col-3 qMods multMod" style="display:none;">
					<h5><?php echo TR('analysisType'); ?></h5>
					<select class="form-control HSMultType" id="HSMultType">
						<option value="1"><?php echo TR('average'); ?></option>
						<option value="2"><?php echo TR('ansNumber'); ?></option>
					</select>
				</div>
			</div>
			<div id="divQuestions"></div>

		</div>
		<!-- STRUCTURE FILTERS -->
		<div style="background-color: whitesmoke;padding: 10px;border-radius: 5px;<?php echo $displayStruct; ?>" id="HSStructureFilter">
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
						<select class="form-control dimSelHS" id="dimSelHS_<?php echo "$d[nivel]"; ?>">
							<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
							<?php foreach ($dimsElems as $de){ ?>
								<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
							<?php } ?>
						</select>
					</div>
				<?php } ?>
			</div>
		</div>

		
		<!-- KMLS -->
		<div id="KMLS">
			<div class="row">
				<div class='col-3'>
					<h5 style="font-weight: bold;"><?php echo TR('selPolygonMap'); ?></h5>
					<select class="form-control" style="margin-top: 10px;" id="KMLSel">
						<option value="">- - - <?php echo TR('selPolygonMap'); ?> - - -</option>
						<?php foreach ($KMLS as $kml){ ?>
							<option value="<?php echo $kml['id']; ?>" 
								north="<?php echo $kml['north'] ?>"
								south="<?php echo $kml['south'] ?>"
								east="<?php echo $kml['east'] ?>"
								west="<?php echo $kml['west'] ?>"
							>
								<?php echo $kml['name']; ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="row attrRow" style="margin-top: 10px;" id="attrRow">
				<div class="col-1" style="display: none; text-align: center;" id="divAdd">
					<h5>&nbsp;</h5>
					<span class="btn btn-shop" id="addAttr">
						<i class="glyphicon glyphicon-plus"></i>
					</span>
				</div>
				<div class="col-1"></div>
				<div class="col-3" style="display: none;" id="attrDiv">
					<h5 style="font-weight: bold;"><?php echo TR('selAttr'); ?></h5>
					<select class="form-control attrSel" style="margin-top: 10px;"></select>
				</div>
				<div class="col-3 valSel attrValDiv" style="display: none;">
					<h5 style="font-weight: bold;"><?php echo TR('attrVal'); ?></h5>
					<select class="form-control attrValSel" style="margin-top: 10px;"></select>
				</div>
				<div class="col-3 valSel numMod" style="display:none;">
					<h5><?php echo TR('inequality'); ?></h5>
					<select class="form-control KMLInequality">
						<option value=">"><?php echo TR('greater-than'); ?></option>
						<option value=">="><?php echo TR('greater-or-equal'); ?></option>
						<option value="="><?php echo TR('equal'); ?></option>
						<option value="<="><?php echo TR('less-or-equal'); ?></option>
						<option value="<"><?php echo TR('less-than'); ?></option>
					</select>
				</div>
				<div class="col-3 valSel numMod" style="display:none;">
					<h5><?php echo TR('value'); ?></h5>
					<input type="text" class="form-control KMLValue" value="10"/>
				</div>
			</div>
			<div id="divAttrs"></div>
		</div>

		<div id="divBtn" style="margin-top: 10px;text-align: right;">
			<span class="btn btn-shop" id="generateHS"><?php echo TR('generate'); ?></span>
		</div>
	</div>

	<div id="infoContent">
		<div id="mapContHS">
			<div style="margin-top: 10px;display:none;" id="tipoMapaHSCont">
				
				<input type="radio" name="tipoMapaHS" id="tipoP" value="P" checked="checked" class="tipoPDiv">&nbsp;
				<label for="tipoP" class="tipoPDiv">
					<?php echo TR('polygons'); ?>
				</label>&nbsp;&nbsp;&nbsp;
				
				<input type="radio" name="tipoMapaHS" id="tipoC" value="C">&nbsp;
				<label for="tipoC">
					<?php echo TR('heat'); ?>
				</label>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="tipoMapaHS" id="tipoM" value="M">&nbsp;
				<label for="tipoM">
					<?php echo TR('points'); ?>
				</label>
			</div>

		</div>

	</div>

</div>

