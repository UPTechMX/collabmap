<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Targets

	// print2($_POST);
	$frequencies = $db->query("SELECT * FROM Frequencies ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT tc.*, c.nombre as cName
			FROM TargetsChecklist tc
			LEFT JOIN Checklist c ON c.id = tc.checklistId
			WHERE tc.id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
	}

	// print2($datC);

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}
			?>
			targetId = <?php echo $datC['targetsId']; ?>;


			if(allOk){
				var rj = jsonF('admin/administration/targets/json/json.php',{datos:dat,acc:acc,opt:2});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#surveysList').load(rz+'admin/administration/targets/surveysList.php',{targetId:targetId});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('frequency'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR('survey'); ?></td>
				<td><?php echo $datC['cName']; ?></td>
			</tr>
			<tr>
				<td><?php echo TR('frequency'); ?></td>
				<td>
					<select name="frequency" class="form-control oblig">
						<option value="">- - - - <?php echo TR('frequency'); ?> - - -</option>
						<?php foreach ($frequencies as $f){ ?>
							<option value="<?php echo $f['id'] ?>" <?php echo $datC['frequency'] == $f['id']?'selected':''; ?>>
								<?php echo TR($f['code']); ?>
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
