<?php
	session_start();
	$root = $_SESSION['CM']['raiz'];
	include_once $root.'lib/j/j.func.php';

	checaAccesoQuest();

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM TargetsElems WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
	}


	// print2($_POST);

?>
<script type="text/javascript">
	$(document).ready(function() {

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		$('#tipoReembolso').trigger('change');

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.targetsId = $_POST[targetId];";
					echo "dat.usersTargetsId = $_POST[usersTargetsId];";
				}
			?>

			if(allOk){
				var targetId = <?php echo $_POST['targetId']; ?>;
				var usersTargetsId = <?php echo $_POST['usersTargetsId']; ?>;
				var rj = jsonF('questionnaires/targets/json/json.php',{datos:dat,acc:acc,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#divTrgt_'+targetId+'_'+usersTargetsId)
					.find('.targetTable')
					.load(rz+'questionnaires/targets/targetTable.php',{targetId:targetId});
				}
			}

		});

	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('target'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
