<?php
	session_start();
	$root = $_SESSION['CM']['raiz'];
	include_once $root.'lib/j/j.func.php';

	checaAccesoQuest();

	$usrId = $_SESSION['CM']['questionnaires']['usrId'];
	// print2($_SESSION);

	$targets = $db->query("SELECT t.id, t.name as tName, t.id as tId, tc.checklistId, c.nombre as cNom, 
		p.name as pName, tc.frequency, f.code, ut.id as utId, c.id as cId
		FROM UsersTargets ut 
		LEFT JOIN Targets t ON t.id = ut.targetsId
		LEFT JOIN Projects p ON p.id = t.projectsId
		LEFT JOIN TargetsChecklist tc ON tc.targetsId = ut.targetsId
		LEFT JOIN Frequencies f ON f.id = tc.frequency
		LEFT JOIN Checklist c ON c.id = tc.checklistId
		WHERE ut.usersId = $usrId AND c.id IS NOT NULL
		ORDER BY p.name, t.name, tc.frequency
	")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);


	// print2($targets);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.addTrgt').click(function(event) {
			var targetId = $(this).closest('.divTrgt').attr('id').split('_')[1];
			var usersTargetsId = $(this).closest('.divTrgt').attr('id').split('_')[2];
			console.log(targetId);
			popUp('questionnaires/targets/targetAdd.php',{targetId:targetId,usersTargetsId:usersTargetsId});
			// $('#divTrgt_'+targetId+'_'+usersTargetsId).find('.targetTable').load(rz+'questionnaires/targets/targetTable.php',{targetId:targetId});
		});
	});
</script>

<?php foreach ($targets as $targetsChecklist){ ?>
	<div style="margin-top: 10px;" class="divTrgt" id="divTrgt_<?php echo $targetsChecklist[0]['tId']."_".$targetsChecklist[0]['utId']; ?>">
		<div class="nuevo"><?php echo $targetsChecklist[0]['tName']; ?></div>
		<div style="margin-top: 10px;margin-bottom:10px;text-align: right;">
			<span class="btn btn-shop addTrgt">
				<i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo $targetsChecklist[0]['tName']; ?>
			</span>
		</div>
		<div class="targetTable"><?php include 'targetTable.php'; ?></div>
	</div>
<?php } ?>

