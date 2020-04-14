<?php  

	if($_POST['ajax'] == 1){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist
	$resps = $db->query("SELECT * FROM Respuestas 
		WHERE preguntasId = $_POST[pregId] AND (elim != 1  OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {

		$( "#respuestasList_<?php echo $_POST['pregId']; ?> .respsSort " ).sortable({
			scrollSpeed: 5,
			update: function( event, ui ) {
				var pregId = $(this).closest('.respuestas').attr('id').split('_')[1];
				// console.log(pregId);
				$('#respuestas_'+pregId+' #saveRespsOrden').show();
			}
		})
		$( "#respuestasList_<?php echo $_POST['pregId']; ?> .respsSort" ).disableSelection();

		$( "#respuestasList_<?php echo $_POST['pregId']; ?> .delResp" ).click(function(event) {
			var respId = this.id.split('_')[1];
			var pregId = <?php echo $_POST['pregId']; ?>;
			conf('¿Está seguro que desea elilminar la respuesta?',{respId:respId,pregId:pregId},function(e){
				var rj = jsonF('admin/checklist/json/json.php',{datos:{id:respId,elim:1},acc:2,opt:8,chkId:checklistId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#respuestasList_'+pregId).load(rz+'admin/checklist/respuestasList.php',
						{ajax:1,pregId:pregId});
				}

			});

			// console.log(respId,pregId)
		});
		$( "#respuestasList_<?php echo $_POST['pregId']; ?> .edtResp" ).click(function(event) {
			var respId = this.id.split('_')[1];
			var pregId = <?php echo $_POST['pregId']; ?>;
			popUp('admin/checklist/respuestasAdd.php',{respId:respId,pregId:pregId},function(e){},{});

			// console.log(respId,pregId)
		});

		// $('#respuestasList_<?php echo $_POST['pregId']; ?> .condResp').click(function(event) {
		// 	var respId = this.id.split('_')[1];
		// 	popUp('admin/checklist/condiciones.php',{eleId:respId,aplicacion:'resp'},function(e){},{});
		// });

		$('#respuestasList_'+<?php echo $_POST['pregId']; ?>+' #addSiNo').click(function(event) {
			var pregId = $(this).closest('.respuestas').attr('id').split('_')[1];
			// console.log(pregId);
			var rj = jsonF('admin/checklist/json/addSiNo.php',{pregId:pregId,opt:1});
			console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#respuestasList_'+pregId).load(rz+'admin/checklist/respuestasList.php',
					{ajax:1,pregId:pregId});

			}
			// popUp('admin/checklist/respuestasAdd.php',{pregId:pregId},function(e){},{});

		});

		$('#respuestasList_'+<?php echo $_POST['pregId']; ?>+' #add1Al10').click(function(event) {
			var pregId = $(this).closest('.respuestas').attr('id').split('_')[1];
			// console.log(pregId);
			var rj = jsonF('admin/checklist/json/addSiNo.php',{pregId:pregId,opt:2});
			console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#respuestasList_'+pregId).load(rz+'admin/checklist/respuestasList.php',
					{ajax:1,pregId:pregId});

			}
			// popUp('admin/checklist/respuestasAdd.php',{pregId:pregId},function(e){},{});

		});

		$('#respuestasList_'+<?php echo $_POST['pregId']; ?>+' #addRComp').click(function(event) {
			var pregId = $(this).closest('.respuestas').attr('id').split('_')[1];
			// console.log(pregId);
			var rj = jsonF('admin/checklist/json/addSiNo.php',{pregId:pregId,opt:3});
			console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#respuestasList_'+pregId).load(rz+'admin/checklist/respuestasList.php',
					{ajax:1,pregId:pregId});

			}
			// popUp('admin/checklist/respuestasAdd.php',{pregId:pregId},function(e){},{});

		});

		$('#respuestasList_'+<?php echo $_POST['pregId']; ?>+' #addRBM').click(function(event) {
			var pregId = $(this).closest('.respuestas').attr('id').split('_')[1];
			// console.log(pregId);
			var rj = jsonF('admin/checklist/json/addSiNo.php',{pregId:pregId,opt:4});
			console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#respuestasList_'+pregId).load(rz+'admin/checklist/respuestasList.php',
					{ajax:1,pregId:pregId});

			}
			// popUp('admin/checklist/respuestasAdd.php',{pregId:pregId},function(e){},{});

		});




	});
</script>

<?php if (count($resps) == 0){ ?>
	<span class="btn btn-shop btn-sm" id="addSiNo" style="margin-top:5px;">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('add'); ?> <?php echo TR('yes')."/".TR('no')."/".TR('idk') ?>
	</span>
	<span class="btn btn-shop btn-sm" id="add1Al10" style="margin-top:5px;">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('add'); ?> <?php echo TR('scale'); ?> 0 al 10
	</span>
	<!-- <span class="btn btn-shop btn-sm" id="addRComp" style="margin-top:5px;">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('add'); ?> respuestas compromisos
	</span> -->
	<!-- <span class="btn btn-shop btn-sm" id="addRBM" style="margin-top:5px;">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('add'); ?> respuestas bueno/malo
	</span> -->
<?php }else{?>
	<ul class="list-group respsSort" style="margin-top: 10px;" id="respsSort">
		<?php foreach ($resps as $r): ?>
			<li class="list-group-item respEle arrastra" id="respEle_<?php echo $r['id'];?>">
				<div class="row">
					<div class="col-sm-5 col-md-5 col-lg-5 "><?php echo $r['respuesta']; ?></div>
					<div class="col-sm-3 col-md-3 col-lg-3 ">Valor: <?php echo $r['valor']; ?></div>
					<div class="col-sm-4 col-md-4 col-lg-4 ">
						<i class="glyphicon glyphicon-pencil manita edtResp" id="edtResp_<?php echo $r['id'];?>"></i>&nbsp;
						<!-- <i class="glyphicon glyphicon-question-sign manita condResp" id="condResp_<?php echo $r['id'];?>"></i>&nbsp; -->
						<i class="glyphicon glyphicon-trash manita rojo delResp" id="delResp_<?php echo $r['id'];?>"></i>
					</div>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
<?php } ?>

