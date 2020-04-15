<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Projects
	$p = $_POST;
	// print2($_POST);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Projects WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
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
			dat.inactive = $('#inactive').is(':checked')?1:0;
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>


			if(allOk){
				var rj = jsonF('admin/administration/projects/json/json.php',{datos:dat,acc:acc,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#projectsList').load(rz+'admin/administration/projects/projectsList.php',{ajax:1});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('project'); ?>
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
				<td><input type="text" value="<?php echo $datC['description']; ?>" name="description" id="description" class="form-control oblig"></td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('inactive'); ?></td>
				<td><input type="checkbox" id="inactive" <?php echo $datC['inactive'] == 1?'checked':''; ?>></td>
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
