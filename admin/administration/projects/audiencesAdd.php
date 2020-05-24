<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Projects
	// print2($_POST);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Audiences WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
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
					echo "dat.projectsId = $_POST[prjId]";
				}
			?>


			if(allOk){
				var rj = jsonF('admin/administration/projects/json/json.php',{datos:dat,acc:acc,opt:2});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					var prjId = <?php echo $_POST['prjId']; ?>;
					$('#popUp').modal('toggle');
					$('#audiencesList').load(rz+'admin/administration/projects/audiencesList.php',{ajax:1,prjId:prjId,},function(){
						setTimeout(function(){
							if(acc == 2){
								var eleId = "<?php echo $_POST['eleId']; ?>";
								$('#audTr_'+eleId).find('.audStruc').trigger('click');
							}else{
								$('#audTr_'+r.nId).find('.audStruc').trigger('click');
							}
						},100);
					});
					// $('#structures').empty();
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
			<?php echo TR('audience'); ?>
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
			<!-- <tr>
				<td><?php echo TR('description'); ?></td>
				<td>
					<textarea name="description" id="description" class="form-control oblig txArea"><?php echo $datC['description']; ?></textarea>
				</td>
				<td></td>
			</tr> -->
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
