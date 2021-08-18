<?php
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso externalUsers

	$projects = $db->query("SELECT p.* FROM Projects p ORDER BY p.name")->fetchAll(PDO::FETCH_ASSOC);
	$targets = $db->query("SELECT t.projectsId as pId, t.name as nom, t.id as val, 'clase' as clase
		FROM Targets t ORDER BY t.name")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	
	// print2($targets);
?>

<script type="text/javascript">
	$(document).ready(function() {
		var targets = <?php echo atj($targets); ?>;

		$('#prjSel').change(function(event) {
			prjId = $(this).val();
			if(prjId != ''){
				optsSel(targets[prjId],$('#targSel'),false,'- - - - <?php echo TR("target"); ?> - - - -',false);
			}else{
				optsSel([],$('#targSel'),false,'- - - - <?php echo TR("target"); ?> - - - -',false);
			}
		});

		$("#addUsrTrg").click(function(event) {
			var targetsId = $('#targSel').val();
			var usersId = <?php echo $_POST['usrId']; ?>;
			var dat = {};
			dat.usersId = usersId;
			dat.targetsId = targetsId;
			if(targetsId != ''){

				if($('#trUsrTrg_'+targetsId).length == 0){
					var rj = jsonF('admin/externalUsers/json/json.php',{opt:2,acc:1,datos:dat});
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('#userTargetsList').load(rz+'admin/externalUsers/userTargetsList.php',{usrId:usersId});
					}
				}else{
					alertar('<?php echo TR('targetAlert'); ?>');
				}
			}
		});
	});
</script>

<div style="margin-top: 10px;">
	<div class="row">
		<div class="col-6">
			<select id="prjSel" class="form-control">
				<option value="">- - - - <?php echo TR('project'); ?> - - - -</option>
				<?php foreach ($projects as $p){ ?>
					<option value="<?php echo $p['id'] ?>"><?php echo $p['name']; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-6">
			<select class="form-control" id="targSel">
				<option value="">- - - - <?php echo TR('target'); ?> - - - -</option>
			</select>
		</div>
	</div>
	<div style="text-align: right;margin-top: 10px;">
		<span class="btn btn-shop" id="addUsrTrg"><?php echo TR('add'); ?></span>
	</div>
</div>

<div style="margin-top: 10px;" id="userTargetsList"><?php include_once 'userTargetsList.php'; ?></div>