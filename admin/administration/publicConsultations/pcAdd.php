<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Public consultations
	$p = $_POST;
	// print2($_POST);
	$projects = $db->query("SELECT * FROM Projects ORDER BY name") -> fetchAll(PDO::FETCH_ASSOC);
	$checklist = $db->query("SELECT * FROM Checklist ORDER BY nombre") -> fetchAll(PDO::FETCH_ASSOC);
	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM PublicConsultations WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
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

		$('#code').blur(function(event) {
			var dat = $('#nEmp').serializeObject();

			var rj = jsonF('admin/administration/publicConsultations/json/buscCode.php',{code:dat.code,pcId:'<?php echo $_POST['eleId']; ?>'});
			var r = $.parseJSON(rj);

			if(r.cuenta != 0){
				alertar('<?php echo TR('codeExist'); ?>',function(e){},{});
				// $('#code').focus();
				// $('#code').val( $('#code').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

		});


		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#env').click(function(event) {
			console.log('asas');
			var dat = $('#nEmp').serializeObject();
			dat.emailReq = $('#emailReq').is(':checked')?1:0;
			if($('#trMult').is(":visible")){
				dat.oneAns = $('#oneAns').is(':checked')?1:0;
			}else{
				dat.oneAns = 0;
			}
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>

			var ccj = jsonF('admin/administration/publicConsultations/json/buscCode.php',{code:dat.code,pcId:'<?php echo $_POST['eleId']; ?>'});
			var cc = $.parseJSON(ccj);

			if(cc.cuenta != 0){
				alertar('<?php echo TR('codeExist'); ?>',function(e){},{});
				allOk = false;
			}


			// console.log(dat,allOk);
			if(allOk){
				var rj = jsonF('admin/administration/publicConsultations/json/json.php',{datos:dat,acc:acc,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#pcList').load(rz+'admin/administration/publicConsultations/pcList.php',{ajax:1});
				}
			}

		});

		$('#emailReq').change(function(event) {
			if($(this).is(':checked')){
				$('#trMult').show();
			}else{
				$('#trMult').hide();
			}
		});

		$(".txArea").jqte({
			source:true,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});

		$('#emailReq').trigger('change');


	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('publicCon'); ?>
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
					<select name="projectsId" class="form-control">
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
				<td><?php echo TR('survey'); ?></td>
				<td>
					<select name="checklistId" class="form-control oblig">
						<option value="">- - - - <?php echo TR('survey'); ?> - - -</option>
						<?php foreach ($checklist as $c){ ?>
							<option value="<?php echo $c['id'] ?>" <?php echo $c['id'] == $datC['checklistId']?'selected':''; ?>>
								<?php echo $c['nombre']; ?>
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
				<td><input type="text" value="<?php echo $datC['code']; ?>" name="code" id="code" class="form-control oblig"></td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('emailReq'); ?></td>
				<td><input type="checkbox" id="emailReq" <?php echo $datC['emailReq'] == 1?'checked':''; ?>></td>
				<td></td>
			</tr>
			<tr id="trMult" style="display:none;">
				<td><?php echo TR('oneAns'); ?></td>
				<td><input type="checkbox" id="oneAns" <?php echo $datC['oneAns'] == 1?'checked':''; ?>></td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('description'); ?></td>
				<td>
					<textarea name="description" id="description" class="form-control oblig txArea"><?php echo $datC['description']; ?></textarea>
				</td>
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
