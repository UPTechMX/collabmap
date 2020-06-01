<?php
	checaAccesoConsult();
	$projects = $db->query("SELECT * FROM Projects")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#prjSel').change(function(event) {
			// console.log('aaa');
			var elemId = $(this).val();
			// console.log(elemId);
			var r;
			if(elemId != ''){
				var rj = jsonF('consultations/profile/json/json.php',{acc:1,find:'audiences',elemId:elemId});
				r = $.parseJSON(rj);
			}else{
				r = {}
			}
			optsSel(r,$('#audSel'),false,'- - - <?php echo TR('audiences');?> - - -', false);
			$('#audSel').val('');
			$('#audSel').trigger('change');
		});

		$('#audSel').change(function(event) {
			var audId = $(this).val();

			if(audId != ''){
				$('#audStruc').load(rz+'consultations/profile/audStruct.php',{
					elemId:audId,
					type:'audiences',
					consultationsId:1
				})
			}else{
				$('#audStruc').empty();
			}
		});


	});
</script>

<div class="nuevo"><?php echo TR('audiences'); ?></div>
<table class="table">
	<tr>
		<td><?php echo TR('project') ?></td>
		<td>
			<select class="form-control" id="prjSel">
				<option value="">- - -<?php echo TR('project'); ?>- - -</option>
				<?php 
				foreach ($projects as $p){ 
					$count = $db->query("SELECT COUNT(*) 
						FROM Audiences 
						WHERE  projectsId = $p[id]")->fetchAll(PDO::FETCH_NUM)[0][0];
					if($count == 0){
						continue;
					}
				?>
					<option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php echo TR('audiences') ?></td>
		<td>
			<select class="form-control" id="audSel">
				<option value="">- - -<?php echo TR('audiences'); ?>- - -</option>
			</select>
		</td>
	</tr>
</table>
<div id="audStruc"></div>
<div id="audiencesList" style="margin-top: 10px;"><?php include 'audiencesList.php'; ?></div>
