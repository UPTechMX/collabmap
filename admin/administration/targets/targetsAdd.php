<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Targets
	$p = $_POST;
	// print2($_POST);
	$projects = $db->query("SELECT * FROM Projects") -> fetchAll(PDO::FETCH_ASSOC);
	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Targets WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
	}
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
			dat.addStructure = $('#addStructure').is(':checked')?1:0;
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>
			var rj = jsonF('admin/administration/targets/json/chkCode.php',{code:dat.code});
			// console.log(rj);
			var r = $.parseJSON(rj);
			// r.cuenta = 2;
			if(r.cuenta != 0){
				alertar('<?php echo TR('repeatedCode'); ?>',function(e){},{});
				// $('#username').val( $('#username').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}


			if(allOk){
				var rj = jsonF('admin/administration/targets/json/json.php',{datos:dat,acc:acc,opt:1});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					if(dat.acc == 1){
						tId = r.nId;
					}else{
						tId = dat.id;
					}
					$('#popUp').modal('toggle');
					$('#targetsList').load(rz+'admin/administration/targets/targetsList.php',{ajax:1});
					$('#targetsInfo').load(rz+'admin/administration/targets/targetsInfo.php',{targetId:tId});
				}
			}

		});

		$(".txArea").jqte({
			source:true,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});


	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('target'); ?>
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
				<td><?php echo TR('project'); ?></td>
				<td>
					<select name="projectsId" class="form-control oblig">
						<option value="">- - - - <?php echo TR('project'); ?> - - -</option>
						<?php foreach ($projects as $p){ ?>
							<option value="<?php echo $p['id'] ?>" <?php echo $p['id'] == $datC['projectsId']?'selected':''; ?>>
								<?php echo $p['name']; ?>
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo TR('name'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['name']; ?>" name="name" id="name" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('code'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['code']; ?>" name="code" id="code" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('description'); ?></td>
				<td>
					<textarea name="description" id="description" class="form-control oblig txArea"><?php echo $datC['description']; ?></textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('addStructure'); ?></td>
				<td><input type="checkbox" id="addStructure" <?php echo $datC['addStructure'] == 1?'checked':''; ?>></td>
				<td></td>
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
