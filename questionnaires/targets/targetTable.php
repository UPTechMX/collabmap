<?php
	session_start();
	$root = $_SESSION['CM']['raiz'];
	$usrId = $_SESSION['CM']['questionnaires']['usrId'];

	include_once $root.'lib/j/j.func.php';

	checaAccesoQuest();

	// print2($_POST);

	$usrId = $_SESSION['CM']['questionnaires']['usrId'];
	
	if(empty($targetsChecklist) && !empty($_POST['targetId']) ){
		$sql = "SELECT t.id, t.name as tName, t.id as tId, tc.checklistId, c.nombre as cNom, 
			p.name as pName, tc.frequency, f.code, ut.id as utId, c.id as cId
			FROM UsersTargets ut 
			LEFT JOIN Targets t ON t.id = ut.targetsId
			LEFT JOIN Projects p ON p.id = t.projectsId
			LEFT JOIN TargetsChecklist tc ON tc.targetsId = ut.targetsId
			LEFT JOIN Frequencies f ON f.id = tc.frequency
			LEFT JOIN Checklist c ON c.id = tc.checklistId
			WHERE ut.usersId = $usrId AND c.id IS NOT NULL AND ut.targetsId = $_POST[targetId]
			ORDER BY p.name, t.name, tc.frequency
		";
		$targetsChecklist = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP)[$_POST['targetId']];
		// echo "AAAAA<br/>";
		// print2($targetsChecklist);
		// echo "AAAAA<br/>";
	}

	// echo $sql."<br/>";
	// print($targetsChecklist);
	$utId = $targetsChecklist[0]['utId'];

	$TargetsElems = $db->query("SELECT te.*, de.nombre as deName, de.id as deId
		FROM TargetsElems te 
		LEFT JOIN DimensionesElem de ON de.id = te.dimensionesElemId
		WHERE te.usersTargetsId = $utId ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
	// print2($_SESSION);

	// print2($TargetsElems);
?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#tableUT_<?php echo $utId; ?> .action').click(function(event) {
			var ids = $(this).closest('td').attr('id').split('_');
			var teId = ids[1]
			var cId = ids[2]
			
			var rj = jsonF('questionnaires/targets/json/json.php',{acc:4,cId:cId,teId:teId });
			// console.log(rj);

			var r = $.parseJSON(rj);
			// console.log(r);

			switch(r.acc){
				case 'newVisita':
					var checklistId = cId;
					var targetsElemId = teId;
					var targetId = $(this).closest('.divTrgt').attr('id').split('_')[1];
					// console.log(targetId);
					var rj = jsonF('questionnaires/targets/json/json.php',{acc:3,checklistId:checklistId,targetsElemId:targetsElemId});
					var r = $.parseJSON(rj);

					if(r.ok == 1){
						var vId = r.nId;
						popUpCuest('questionnaires/checklist/answer.php',{vId:r.vId},function(){})
						setTimeout(function(){
							$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
						},500);

						$(this)
						.closest('.targetTable')
						.load(rz+'questionnaires/targets/targetTable.php',{targetId:targetId});
					}

					break;
				case 'contVisita':
					var vId = r.vId;
					popUpCuest('questionnaires/checklist/answer.php',{vId:vId},function(){})
					setTimeout(function(){
						$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
					},500);

					break;
				case 'seeResults':
					var vId = this.id.split('_')[1];
					popUpCuest('questionnaires/checklist/seeAnswers.php',{vId:vId,div:1},function(){})
					break;
				default:
					break;
			}

			// popUpCuest('questionnaires/checklist/seeAnswers.php',{vId:vId,div:1},function(){})
			// setTimeout(function(){
			// 	$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
			// },500);
		});



	});
</script>


<table class="table" id="tableUT_<?php echo $utId; ?>">
	<thead>
		<tr>
			<!-- <th><?php echo TR('identifier'); ?></th> -->
			<th><?php echo TR('name'); ?></th>
			<?php foreach ($targetsChecklist as $tc){ ?>
			<th style="text-align: center;"><?php echo TR($tc['code']); ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($TargetsElems as $te){ ?>

			<tr id="trDimElem_<?php echo $te['deId']; ?>">
				<!-- <td>
					<?php echo $te['deId']; ?>
				</td> -->
				<td>
					<?php echo $te['deName']; ?>
				</td>
				<?php 
				foreach ($targetsChecklist as $tc){ 
					$vis = $db->query("SELECT v.*, f.code as fCode
						FROM Visitas v
						LEFT JOIN TargetsElems te ON te.id = v.elemId
						LEFT JOIN TargetsChecklist tc ON tc.targetsId = te.targetsId AND tc.checklistId = v.checklistId
						LEFT JOIN Frequencies f ON f.id = tc.frequency
						WHERE v.type = 'trgt' AND v.elemId = $te[id] AND v.checklistId = $tc[cId]
						ORDER BY v.timestamp DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC)[0];

				?>
					<td id='tdTrgtChk_<?php echo "$te[id]_$tc[cId]" ?>' style="text-align: center;" >
						
						<?php if (empty($vis)){ ?>
							<span class="newVisita btn btn-sm btn-shop" >
								<?php echo TR('answerSurvey'); ?>
							</span>							
						<?php }elseif(empty($vis['finalizada'])){ ?>
							<span class="contVisita btn btn-sm btn-shop"   id="idVis_<?php echo $vis['id']; ?>">
								<?php echo TR('continue'); ?>
							</span>							
						<?php 
						}elseif($vis['finalizada'] == 1){
							$visDate = date('Y-m-d', strtotime($vis['finishDate']));
							$today = date('Y-m-d');

							switch ($vis['fCode']) {
								case "daily":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 day'));
									break;
								case "weekly":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 week'));
									break;
								case "2weeks":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +2 week'));
									break;
								case "3weeks":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +3 week'));
									break;
								case "monthly":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 month'));
									break;
								case "2months":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +2 month'));
									break;
								case "3months":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +3 month'));
									break;
								case "4months":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +4 month'));
									break;
								case "6months":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +6 month'));
									break;
								case "yearly":
									$nextDate = date('Y-m-d', strtotime($vis['finishDate'] . ' +1 year'));
									break;
								default:
									# code...
									break;
							}
						?>
							<?php if ($vis['fCode'] == 'oneTime'){ ?>
								<span style="font-size: x-small;" >
									<?php echo TR('sended').": ".$visDate; ?>
								</span><br/>
								<span class="seeResults btn btn-sm btn-shop"   id="idVis_<?php echo $vis['id']; ?>">
									<?php echo TR('seeResults'); ?>
								</span>							
							<?php }elseif($today >= $nextDate){ ?>
								<span class="newVisita btn btn-sm btn-shop" >
									<?php echo TR('answerSurvey'); ?>
								</span>							
							<?php }elseif($today < $nextDate){ ?>
								<span style="font-size: x-small;">
									<?php echo TR('sended').": ".$visDate; ?>
								</span><br/>
								<span class="seeResults btn btn-sm btn-shop"   id="idVis_<?php echo $vis['id']; ?>">
									<?php echo TR('seeResults'); ?>
								</span>							
							<?php } ?>
						<?php } ?>
					</td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>
