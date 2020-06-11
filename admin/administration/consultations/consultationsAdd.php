<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Consultations
	$p = $_POST;
	// print2($_POST);
	$projects = $db->query("SELECT * FROM Projects") -> fetchAll(PDO::FETCH_ASSOC);
	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Consultations WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
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
				var rj = jsonF('admin/administration/consultations/json/json.php',{datos:dat,acc:acc,opt:1});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#consultationsList').load(rz+'admin/administration/consultations/consultationsList.php',{ajax:1});
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

		$( ".calendar" ).datepicker({ changeYear: true });
		$( ".calendar" ).datepicker("option", "dateFormat", 'yy-mm-dd');
		// $( "#initDate finishDate" ).datepicker({ changeYear: true });
		// $( "#initDate finishDate" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );

		$('#initDate').val('<?php echo $datC['initDate']; ?>');
		$('#finishDate').val('<?php echo $datC['finishDate']; ?>');

		$("#chIcon").click(function(event) {
			popUpMapa('admin/administration/consultations/chIcon.php')
		});


	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('consultation'); ?>
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
				<td><?php echo TR('initDate'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['initDate']; ?>" name="initDate" id="initDate" 
						class="form-control calendar oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('finishDate'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['finishDate']; ?>" name="finishDate" id="finishDate" 
						class="form-control calendar oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('icon'); ?></td>
				<td>
					<div class="row">
						<div class="col-6" id="divIco" style="text-align: center;">
							<i class="fas <?php echo $datC['icon'] ;?> fa-2x"></i>
						</div>
						<div class="col-6">
							<i class="glyphicon glyphicon-pencil manita" id="chIcon"></i>
							<input type="hidden" name="icon"  id="icon" value="<?php echo $datC['icon']; ?>">
						</div>
					</div>
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
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
