<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	if(!is_numeric($_REQUEST['consultationId'])){
		exit();
	}

	checaAccesoConsult($_REQUEST['consultationId']);
	$today = date('Y-m-d');

	$stmt = $db->prepare("SELECT c.*, p.name as pName
		FROM Consultations c
		LEFT JOIN Projects p ON p.id = c.projectsId
		WHERE c.id = ?");

	$stmt -> execute(array($_REQUEST['consultationId']));

	$consInf = $stmt ->fetchAll(PDO::FETCH_ASSOC)[0];

	$chkPrep = $db->prepare("SELECT cc.*, c.nombre as cName, c.id as cId
		FROM ConsultationsChecklist cc
		LEFT JOIN Checklist c ON cc.checklistId = c.id
		WHERE cc.consultationsId = ?");
	$chkPrep -> execute(array($_REQUEST['consultationId']));
	$chks = $chkPrep ->fetchAll(PDO::FETCH_ASSOC);
	
	$documents = $db->query("SELECT * FROM Documents WHERE consultationsId = $_REQUEST[consultationId]")->fetchAll(PDO::FETCH_ASSOC);

	$usrId = empty($_SESSION['CM']['consultations']['usrId'])?0:$_SESSION['CM']['consultations']['usrId'];

	


?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.imgFondo').css({
			// backgroundColor:'red',
			backgroundImage:'url('+rz+'img/fondoIcono.png)',
			backgroundSize:'100%',
			backgroundRepeat:'no-repeat',
		});

		<?php if (!empty($usrId)){ ?>
			$('.actionChk').click(function(event) {
				var ids = $(this).closest('li').attr('id').split('_');
				var cId = ids[1]
				var consultationId = <?php echo $_REQUEST['consultationId']; ?>;
				var rj = jsonF('consultations/consultation/json/json.php',{acc:4,cId:cId,consultationId:consultationId});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);

				switch(r.acc){
					case 'newVisita':
						var checklistId = cId;
						// console.log(targetId);
						var rj = jsonF('consultations/consultation/json/json.php',{acc:3,checklistId:checklistId,consultationId:consultationId});
						// console.log(rj);
						var r = $.parseJSON(rj);

						if(r.ok == 1){
							var vId = r.nId;
							popUpCuest('consultations/checklist/answer.php',{vId:r.vId},function(){})
							setTimeout(function(){
								$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
							},500);

						}

						break;
					case 'contVisita':
						var vId = r.vId;
						popUpCuest('consultations/checklist/answer.php',{vId:vId},function(){})
						setTimeout(function(){
							$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
						},500);

						break;
					case 'seeResults':
						var vId = this.id.split('_')[1];
						popUpCuest('consultations/checklist/seeAnswers.php',{vId:vId,div:1},function(){})
						break;
					default:
						break;
				}

				var params = chUrl({},'','',false,false);
				// console.log(params);
				$('#content').load(rz+'consultations/layout/content.php',params);

			});
		<?php }else{ ?>
			$('.actionChk').click(function(event) {
				alerta('success','<?php echo TR("needLogin"); ?>');
			})
		<?php } ?>

		$.each($('img'), function(index, val) {
			var file = $(this).attr('file');
			if(file != undefined){
				$(this).attr({src:rz+'img/'+file})
			}
		});

		$.each($('.icoAcc'), function(index, val) {
			var w = $(this).closest('.iconContainer').width();
			w = w*.5;
			$(this).css({width:w+'px'});
		});

		$("#complaintsFU").click(function(event) {
			<?php if (!empty($usrId)){ ?>
				var consultationId = <?php echo $_REQUEST['consultationId']; ?>;
				popUp('consultations/consultation/complaintsFU.php',{consultationId:consultationId});
			<?php }else{ ?>
				alerta('success','<?php echo TR("needLogin"); ?>');
			<?php } ?>
		});

		$('.seeDoc').click(function(event) {
			var dId = $(this).closest('li').attr('id').split('_')[1];
			popUpCuest('consultations/consultation/document.php',{documentId:dId});
		});

		$.each($('.actionName'), function(index, val) {
			 var h = $(this).closest('.actionDiv').find('.iconContainer').height();
			 console.log(h);
		});

	});
</script>
<div style="color:#2a6bd5;">
	<!-- <div class="prjName" style="font-size: 1.5em;">
		<?php echo $consInf['pName']; ?>
	</div> -->
	<div class="consultationName azul" style="font-size: 2em;font-weight: bold;text-align: left;">
		<?php echo $consInf['name']; ?>
	</div>
	<div class="negro descriptionConsultation" style="text-align: left;text-align: justify;">
		<?php echo $consInf['description']; ?>
	</div>
	<div style="position: relative; margin-bottom: 30px;margin-top: 30px;">
		<hr>
		<div style="background-color: #CCC;width: 10px;height: 10px;border-radius: 50%;position: absolute;top:-4px;"></div>
		<div style="background-color: #CCC;width: 10px;height: 10px;border-radius: 50%;position: absolute;top:-4px;left: 20px;"></div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="actionDiv"  style="color: #999;border-top: solid 3px #004aad;">
				<div style="position: relative;">
					<!-- <div class="icono" style="width:100%;position:absolute;top:0px;">
						<div style="width:140px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div class="colorizaSurveys">
								<img file="../img/pendon.svg"  />
							</div>
						</div>
					</div> -->
					<div class="icono" style="margin-top: 20px;width:100%;position:absolute;top:-63px;">
						<div style="width:75px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div style="height: 75px;" class="imgFondo-" style="background-repeat: no-repeat;">
								<div class="titleL1Bkg" style="width: 100%;height: 100%;border-radius: 50%;">
									<div style="text-align: center;padding-top: 12px;" class="iconDiv colorIcono">
										<img file="surveys.png" class="icoAcc" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="margin-top: 100px;" class="actionName">
					<?php echo TR('accmapandsurveys'); ?>
				</div>
				<div style="margin-top: 15px;padding: 10px;">
					<ul class="list-group">
						<?php
						foreach ($chks as $c){
							// print2($c);
							if(!is_numeric($_REQUEST['consultationId'])){
								exit();
							}
							$sql = "SELECT v.*, f.code as fCode
								FROM Visitas v
								LEFT JOIN UsersConsultationsChecklist ucc ON v.elemId = ucc.id
								LEFT JOIN ConsultationsChecklist cc ON cc.id = ucc.consultationsChecklistId 
								LEFT JOIN Frequencies f ON f.id = cc.frequency
								WHERE v.type = 'cons' AND ucc.usersId = $usrId AND v.checklistId = $c[cId] 
									AND cc.consultationsId = $_REQUEST[consultationId]
								ORDER BY v.finishDate DESC LIMIT 1";

							// echo "$sql<br/>";
							$vis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
						?>
							<li class="list-group-item" id="chk_<?php echo $c['cId']; ?>">
								<div class="accSurvey">
									<div class="row">
										<div class="col-7">
											<?php echo $c['cName']; ?>
										</div>
										<div class="col-5" style="font-size: .8em;">
											<?php if (empty($vis)){ ?>
												<span class="newVisita manita actionChk" style="color:grey;">
													<?php echo TR('answerSurvey'); ?>
												</span>							
											<?php }elseif(empty($vis['finalizada'])){ ?>
												<span class="contVisita manita actionChk" style="color:grey;"  id="idVis_<?php echo $vis['id']; ?>">
													<?php echo TR('continue'); ?>
												</span>							
											<?php 
											}elseif($vis['finalizada'] == 1){ 
												$visDate = date('Y-m-d', strtotime($vis['finishDate']));
												$nextDate = getNextDate($vis['fCode'],$vis['finishDate']);
												// echo "NEXTDATE: $nextDate -- <br/>";
											?>
												<?php if($today >= $nextDate){ ?>
													<span class="newVisita manita actionChk" style="color:grey;">
														<?php echo TR('answerSurvey'); ?>
													</span>
												<?php }elseif($today < $nextDate){ ?>
													<span style="font-size: x-small;">
														<?php echo TR('sended').": ".$visDate; ?>
													</span><br/>
													<span class="seeResults manita actionChk" style="color:grey;"  id="idVis_<?php echo $vis['id']; ?>">
														<?php echo TR('seeResults'); ?>
													</span>							
												<?php } ?>

											<?php } ?>	
										</div>
									</div>
								</div>
							</li>
						<?php } ?>
						
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="actionDiv"  style="color: #999;border-top: solid 3px #e80000;">
				<div style="position: relative;">
					<!-- <div class="icono" style="width:100%;position:absolute;top:0px;">
						<div style="width:140px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div class="colorizaComplains">
								<img file="../img/pendon.svg"  />
							</div>
						</div>
					</div> -->
					<div class="icono" style="margin-top: 20px;width:100%;position:absolute;top:-63px;">
						<div style="width:75px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div style="height: 75px;" class="imgFondo- " style="background-repeat: no-repeat;">
								<div class= "titleL2Bkg" style="width: 100%;height: 100%;border-radius: 50%;">
									<div style="text-align: center;padding-top: 12px;" class="iconDiv colorIcono">
										<img file="complaints.png" class="icoAcc" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="margin-top: 100px;" class="actionName">
					<?php echo TR('acccomplaints'); ?>
				</div>
				<div style="text-align: right;padding-right: 10px;margin-top:10px;font-size: 1em;">
					<!-- <span class="manita" style="text-transform: uppercase;" id="complaintsFU">
						
						<i class="glyphicon glyphicon-check"></i>	
					</span> -->
				</div>
				<div><?php include 'complaintsStructure.php'; ?></div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="actionDiv"  style="color: #999;border-top: solid 3px #004aad;">
				<div style="position: relative;">
					<!-- <div class="icono" style="width:100%;position:absolute;top:0px;">
						<div style="width:140px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div class="colorizaDocuments">
								<img file="../img/pendon.svg"  />
							</div>
						</div>
					</div> -->
					<div class="icono" style="margin-top: 20px;width:100%;position:absolute;top:-63px;">
						<div style="width:75px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div style="height: 75px;" class="imgFondo-" style="background-repeat: no-repeat;">
								<div style="width: 100%;height: 100%;background-color: #004aad ;border-radius: 50%;">
									<div style="text-align: center;padding-top: 12px;" class="iconDiv colorIcono">
										<img file="documents.png" class="icoAcc" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="margin-top: 100px;" class="actionName">
					<?php echo TR('documents'); ?>
				</div>
				<div style="margin-top: 15px;padding: 10px;">
					<ul class="list-group">
						<?php
						foreach ($documents as $d){
						?>
							<li class="list-group-item" id="chk_<?php echo $d['id']; ?>">
								<div class="accDocument">
									<div class="row">
										<div class="col-12 manita seeDoc">
											<?php echo $d['name']; ?>
										</div>
									</div>
								</div>
							</li>
						<?php } ?>
						
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php if (!empty($consInf['poll'])){ ?>
		<script type="text/javascript">

			$(document).ready(function() {

				$('.calif').click(function(event) {
					<?php if (!empty($usrId)){ ?>
						// console.log('aaa');
						var value = this.id.split('_')[1];
						$('.calif').css({color:'#000'});
						var rj = jsonF('consultations/consultation/json/json.php',{acc:11,consultationId:<?php echo $consInf['id']; ?>,value:value});
						// console.log(rj);
						var r = $.parseJSON(rj);
						if(r.ok == 1){
							alerta('success','<?php echo TR("pollSend"); ?>');
							$(this).css({color:'#004aad'});
						}
					<?php }else{ ?>
						alerta('success','<?php echo TR("needLogin"); ?>');
					<?php } ?>

				});
			});
		</script>
		<div style="border-top: solid 1px #CCC;margin-top: 20px">&nbsp;</div>
		<!-- <div style="text-align: left;text-transform: uppercase;font-size: 1.5em;" class="prjName">
			<?php echo TR('quickvote'); ?>
		</div> -->
		<div class="row" style="border-bottom: solid 1px #CCC;">
			<div class="col-md-8" style="color:#000;text-align: justify;">
				<?php echo $consInf['poll']; ?>
			</div>
			<div style="margin: 0px 0px;" class="col-md-4">
				<div class="row justify-content-md-center">
					<div class="col-3" style="">
						<div class="calif" id="calif_0" style="text-align: center;color: #000;font-size: 3vw;">
							<i class="far fa-frown"></i>
						</div>
					</div>
					<div class="col-3" style="">
						<div class="calif" id="calif_5" style="text-align: center;color: #000;font-size: 3vw;">
							<i class="far fa-meh"></i>
						</div>
					</div>
					<div class="col-3" style="">
						<div class="calif" id="calif_10" style="text-align: center;color: #000;font-size: 3vw;">
							<i class="far fa-smile"></i>
						</div>
					</div>
				</div>
			</div>

		</div>
		
	<?php } ?>
</div>