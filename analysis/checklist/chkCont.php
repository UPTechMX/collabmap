<?php
	// print2($_REQUEST);
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso analysis;
	include_once raiz().'lib/php/checklist.php';


	$targetChecklist = $db->query("SELECT * FROM TargetsChecklist WHERE id = $_REQUEST[trgtChk]")->fetchAll(PDO::FETCH_ASSOC)[0];
	if(empty($targetChecklist)){
		exit('EXIT');
	}



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

	$nivelMax = isset($_POST['nivelMax'])?$_POST['nivelMax']:0;
	$padre = isset($_POST['padre'])?$_POST['padre']:0;

?>


<script type="text/javascript">
	var pregsDesp = {};
	$(document).ready(function() {
		$('.bloqueAnalysis').click(function(event) {
			var divAreas = $($(this).closest('.divBlock').find('.divAreas')[0])
			divAreas.toggle();
			if($(divAreas).is(":visible")){
				$(this).find('.chevron').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
			}else{
				$(this).find('.chevron').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
			}
		});

		$('.areaAnalysis').click(function(event) {
			var divPreguntas = $($(this).closest('.divArea').find('.divPreguntas')[0]);

			divPreguntas.toggle();
			if($(divPreguntas).is(":visible")){
				$(this).find('.chevron').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
			}else{
				$(this).find('.chevron').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
			}
		});

		$('.preguntaAnalysis').click(function(event) {
			var datoPreg = $($(this).closest('.divPregunta').find('.datoPreg')[0])
			datoPreg.toggle();
			var pId = this.id.split('_')[1];
			var trgtChk = <?php echo $_REQUEST['trgtChk']; ?>;
			if($(datoPreg).is(":visible")){
				if(typeof pregsDesp[pId] == 'undefined'){
					// console.log('carga');
					$(datoPreg).load(rz+'analysis/checklist/pregunta.php',{
						trgtChk:trgtChk,
						pId:pId,
						nivelMax:<?php echo $nivelMax; ?>,
						padre:<?php echo $padre; ?>

					});
					pregsDesp[pId] = 1;
				}
				$(this).find('.chevron').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
			}else{
				$(this).find('.chevron').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
			}
		});

		$('.subpreguntaAnalysis').click(function(event) {
			var divSubpreguntasCont = $($(this).closest('.divSubpregunta').find('.divSubpreguntasCont')[0])
			divSubpreguntasCont.toggle();
			
			if($(divSubpreguntasCont).is(":visible")){
				$(this).find('.chevron').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
			}else{
				$(this).find('.chevron').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
			}
		});

		$('#dwlAns').click(function(event) {
		
			var checklistId = <?php echo $checklistId; ?>;
			var nivelMax = <?php echo $nivelMax; ?>;
			var padre = <?php echo $padre; ?>;
			var targetsId = <?php echo $targetChecklist['targetsId']; ?>;

			$('<form>')
			.attr({
				id: 'descPrueba',
				action: rz+'analysis/checklist/json/getDatosXLS.php',
				target:'_blank',
				method:'post'
			})
			.html(
				'<input type="text" name="chkId" value="'+checklistId+'"\>'+
				'<input type="text" name="nivelMax" value="'+nivelMax+'"\>'+
				'<input type="text" name="padre" value="'+padre+'"\>'+
				'<input type="text" name="targetsId" value="'+targetsId+'"\>'
			)
			.appendTo(document.body)
			.submit()
			.remove();


		});

	});
</script>

<div style="text-align: right;margin-top: 10px;">
	<span class="btn btn-shop" id="dwlAns"><?php echo TR('dwlAns'); ?></span>
</div>
<div>
	<?php foreach ($est['bloques'] as $b){ ?>
		<div class="divBlock" style="margin-top: 10px;">
			<div class="bloqueAnalysis manita" >
				<?php echo TR('block'); ?>: <?php echo $b['nombre']; ?>&nbsp;
				<i class="glyphicon glyphicon-chevron-down chevron"></i>
			</div>
			<div class="divAreas" style="padding-left: 50px;margin-top: 10px;">
				<?php foreach ($b['areas'] as $a){ ?>
					<div class="divArea" style="margin-top: 10px;">
						<div class="areaAnalysis manita" >
							<?php echo TR('area'); ?>: <?php echo $a['nombre']; ?>&nbsp;
							<i class="glyphicon glyphicon-chevron-down chevron"></i>
						</div>
						<div class="divPreguntas" style="padding-left: 50px;margin-top: 10px;">
							<?php
							foreach ($a['preguntas'] as $p){ 
								$preg = $p;
							?>
								<?php if ($p['tipo'] != 'sub'){ ?>
									<div class="divPregunta" style="margin-top: 10px;">
										<div class="preguntaAnalysis manita" id="pregId_<?php echo $preg['id']; ?>">
											<?php echo $preg['pregunta']; ?>
											<i class="glyphicon glyphicon-chevron-right chevron"></i>
										</div>
										<div class="datoPreg" style="display: none;"></div>
									</div>
								<?php }else{ ?>
									<div class="divSubpregunta manita" style="margin-top: 10px;">
										<div class="subpreguntaAnalysis">
											<?php echo $preg['pregunta']; ?>
											<i class="glyphicon glyphicon-chevron-right chevron"></i>
										</div>
										<div class="divSubpreguntasCont" style="display: none;padding-left: 50px;">
											<?php 
											foreach ($p['subpregs'] as $sp){ 
												$preg = $sp;
											?>
												<div class="divPregunta" style="margin-top: 10px;">
													<div class="preguntaAnalysis manita" id="pregId_<?php echo $preg['id']; ?>">
														<?php echo $preg['pregunta']; ?>
														<i class="glyphicon glyphicon-chevron-right chevron"></i>
													</div>
													<div class="datoPreg" style="display: none;"></div>
												</div>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
