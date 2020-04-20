<?php
	session_start();
	$root = $_SESSION['CM']['raiz'];
	include_once $root.'lib/j/j.func.php';

	checaAccesoQuest();

	$usrId = $_SESSION['CM']['questionnaires']['usrId'];
	// print2($_SESSION);

	$targets = $db->query("SELECT t.id, t.name as tName, t.id as tId, tc.checklistId, c.nombre as cNom, 
		p.name as pName, tc.frequency, f.code, ut.id as utId, c.id as cId, t.addStructure
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
			var targetsId = $(this).closest('.divTrgt').attr('id').split('_')[1];
			var usersTargetsId = $(this).closest('.divTrgt').attr('id').split('_')[2];
			var sels = $(this).closest('.divTrgt').find('.dimSel');
			var dimensionesElemId = $(sels[sels.length - 1]).val();
			var dat = {targetsId:targetsId,usersTargetsId:usersTargetsId,dimensionesElemId:dimensionesElemId};

			var existe = $('#trDimElem_'+dimensionesElemId).length;
			if(existe > 0){
				alertar('<?php echo TR('TrgtExist'); ?>');
			}else{			
				if(dimensionesElemId != ''){
					var rj = jsonF('questionnaires/targets/json/json.php',{datos:dat,acc:5});
					// console.log(rj);
					var r = $.parseJSON(rj);
					// console.log(r);
					if(r.ok == 1){
						$('#divTrgt_'+targetsId+'_'+usersTargetsId)
						.find('.targetTable')
						.load(rz+'questionnaires/targets/targetTable.php',{targetId:targetsId});
					}else 
					if(r.ok == 2){
						alertar('<?php echo TR('TrgtExist'); ?>');
					}
				}
			}

		});

		$('.addToList').click(function(event) {
			var targetId = $(this).closest('.divTrgt').attr('id').split('_')[1];
			var usersTargetsId = $(this).closest('.divTrgt').attr('id').split('_')[2];
			popUp('questionnaires/targets/targetAdd.php',{targetId:targetId,usersTargetsId:usersTargetsId});
		});

		$('.dimSel').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('.divTrgt').find('.dimSel').length;
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles){
				var r = []
				if(dimElemId != ''){
					var rj = jsonF('questionnaires/targets/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
				}
				var nextNivel = parseInt(dimNivel)+1;
				var elemSel = $(this).closest('.divTrgt').find('#dimSel_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

	});
</script>

<?php 
foreach ($targets as $targetsChecklist){ 
	$tId = $targetsChecklist[0]['tId'];
	$addStructure = $targetsChecklist[0]['addStructure'] == 1;
	$dims = $db->query("SELECT * FROM Dimensiones WHERE elemId = $tId AND type = 'structure'")->fetchAll(PDO::FETCH_ASSOC);
?>
	<div style="margin-top: 10px;" class="divTrgt" id="divTrgt_<?php echo $targetsChecklist[0]['tId']."_".$targetsChecklist[0]['utId']; ?>">
		<div class="nuevo"><?php echo $targetsChecklist[0]['tName']; ?></div>
		<div class='row'>
			<?php 
			foreach ($dims as $k => $d){ 
				if($k == 0){
					$dimsElems = $db->query("SELECT * FROM DimensionesElem 
						WHERE dimensionesId = $d[id]
						ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
				}else{
					$dimsElems = array();
				}
			?>
				<div class="col-3">
					<select class="form-control dimSel" id="dimSel_<?php echo "$d[nivel]"; ?>">
						<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
						<?php foreach ($dimsElems as $de){ ?>
							<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
		</div>
		<div style="margin-top: 10px;margin-bottom:10px;">
			<span class="btn btn-shop addTrgt">
				<?php echo TR('useSelected'); ?>
			</span>
			<?php if ($addStructure){ ?>
				<span class="btn btn-shop addToList">
					<i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('addToList'); ?>
				</span>
			<?php } ?>
		</div>
		<div class="targetTable"><?php include 'targetTable.php'; ?></div>
	</div>
<?php } ?>

