<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

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
	// print2($chks);
	$usrId = empty($_SESSION['CM']['consultations']['usrId'])?0:$_SESSION['CM']['consultations']['usrId'];
	// echo "USRID: $usrId";
	
	// print2($_SESSION);
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.imgFondo').css({
			// backgroundColor:'red',
			backgroundImage:'url('+rz+'img/fondoIcono.png)',
			backgroundSize:'100%',
			backgroundRepeat:'no-repeat',
		});


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

			var params = chUrl({},'','',false);
			// console.log(params);
			$('#content').load(rz+'consultations/layout/content.php',params);

		});

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
			var consultationId = <?php echo $_REQUEST['consultationId']; ?>;
			popUp('consultations/consultation/complaintsFU.php',{consultationId:consultationId});
		});

	});
</script>
<div style="color:#2a6bd5;">
	<div class="prjName" style="font-size: 1.5em;">
		<?php echo $consInf['pName']; ?>
	</div>
	<div class="consultationName" style="font-size: 2em;font-weight: bold;text-align: left;">
		<?php echo $consInf['name']; ?>
	</div>
	<div class="" style="text-align: left;color: grey;">
		<?php echo $consInf['description']; ?>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-4">
			<div class="actionDiv">
				<div style="position: relative;">
					<div class="icono" style="width:100%;position:absolute;top:0px;">
						<div style="width:140px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div class="colorizaSurveys">
								<img file="../img/pendon.svg"  />
							</div>
						</div>
					</div>
					<div class="icono" style="margin-top: 20px;width:100%;position:absolute;top:-74px;">
						<div style="width:100px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div style="height: 120px;" class="imgFondo" style="background-repeat: no-repeat;">
								<div style="width: 100%;height: 100%;">
									<div style="text-align: center;padding-top: 15px;" class="iconDiv">
										<img file="surveys.png" class="icoAcc" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="margin-top: 30%;" class="actionName">
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
			<div class="actionDiv"  style="color: #947ab8;border-top: solid 3px #947ab8;">
				<div style="position: relative;">
					<div class="icono" style="width:100%;position:absolute;top:0px;">
						<div style="width:140px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div class="colorizaComplains">
								<img file="../img/pendon.svg"  />
							</div>
						</div>
					</div>
					<div class="icono" style="margin-top: 20px;width:100%;position:absolute;top:-74px;">
						<div style="width:100px;margin-left: auto;margin-right: auto;" class="iconContainer">
							<div style="height: 120px;" class="imgFondo" style="background-repeat: no-repeat;">
								<div style="width: 100%;height: 100%;">
									<div style="text-align: center;padding-top: 15px;" class="iconDiv">
										<img file="complaints.png" class="icoAcc" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="margin-top: 30%;" class="actionName">
					<?php echo TR('acccomplaints'); ?>
				</div>
				<div style="text-align: right;padding-right: 10px;margin-top:10px;font-size: 1em;">
					<span class="manita" style="text-transform: uppercase;" id="complaintsFU">
						<!-- <?php echo TR("complaintsFU"); ?> -->
						<i class="glyphicon glyphicon-check"></i>	
					</span>
				</div>
				<div><?php include 'complaintsStructure.php'; ?></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="actionDiv"></div>
		</div>
	</div>
</div>