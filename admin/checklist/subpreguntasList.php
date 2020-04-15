<?php  

	if($_POST['ajax'] == 1){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist
	$subpregs = $db->query("SELECT p.*, t.siglas as tsiglas, t.nombre as nTipo FROM Preguntas p
		LEFT JOIN Tipos t ON t.id = p.tiposId
		WHERE subareasId = $_POST[pregId]  AND (elim != 1 OR elim IS NULL)
		ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	// print2($subpregs);
?>

<script type="text/javascript">
	$(document).ready(function() {

		$( "#subpreguntasList_<?php echo $_POST['pregId']; ?> .subpregsSort " ).sortable({
			handle: ".muevePreg",
			scrollSpeed: 5,
			update: function( event, ui ) {
				var pregId = $(this).closest('.subpreguntas').attr('id').split('_')[1];
				// console.log(pregId);
				$('#savePregsOrden').show();
			}
		})
		
		
		$("#subpreguntasList_<?php echo $_POST['pregId']; ?>"+' .verRespSub').click(function(event) {
			var pregId = this.id.split('_')[1];
			if($('#respuestas_'+pregId).is(':visible')){
				$(this).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
			}else{
				$(this).addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-right')
			}
				$('#respuestas_'+pregId).slideToggle();
		});

		$('#subpreguntasList_<?php echo $_POST['pregId']; ?> .edtSubpreg').click(function(event) {
			var subpregId = this.id.split('_')[1];
			var pregId = <?php echo $_POST['pregId']; ?>;
			popUp('admin/checklist/subpreguntasAdd.php',{pregId:pregId,subpregId:subpregId},function(e){},{});
		});

		$('#subpreguntasList_<?php echo $_POST['pregId']; ?> .delSubpreg').click(function(event) {
			console.log('11');
			var subpregId = this.id.split('_')[1];
			var pregId = <?php echo $_POST['pregId']; ?>;
				// console.log(pregId,areaId);
			conf('¿Está seguro que desea elilminar la pregunta?',{subpregId:subpregId,pregId:pregId},function(e){
				// console.log(pregId,areaId);
				var rj = jsonF('admin/checklist/json/json.php',{datos:{id:e.subpregId,elim:1},acc:2,opt:7,chkId:checklistId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#subpreguntasList_<?php echo $_POST["pregId"]; ?>').load(rz+'admin/checklist/subpreguntasList.php',
						{ajax:1,pregId:<?php echo $_POST['pregId']; ?>});
				}

			});

		});

		$('#subpreguntasList_<?php echo $_POST['pregId']; ?> .condSubpreg').click(function(event) {
			var pregId = this.id.split('_')[1];
			popUp('admin/checklist/condiciones.php',{eleId:pregId,aplicacion:'preg'},function(e){},{});
		});



	});
</script>


<ul class="list-group subpregsSort" style="margin-top: 10px;" id="subpregsSort">
	<?php foreach ($subpregs as $sp): ?>
		<li class="list-group-item pregEle" id="pregEle_<?php echo $sp['id'];?>">
			<div class="row">
			<?php
				switch ($sp['tsiglas']) {
					case 'mult':
			?>	
						<div class="col-sm-12 col-md-5 col-lg-5 nombre" id="nombre">
							<div class="muevePreg arrastra">
								<?php echo $sp['pregunta']; ?>
							</div>
							<hr/>
							<?php echo TR('type'); ?>: <strong><?php echo TR($sp['tsiglas']); ?></strong><br/>
							<?php echo TR('points'); ?>: <strong><?php echo $sp['puntos']; ?></strong><br/>
							<?php echo TR('toRecord'); ?>: <strong><?php echo $sp['influyeValor'] == 1?TR('yes'):TR('no'); ?></strong><br/>
							Id: <strong><?php echo $sp['identificador']; ?></strong><br/>
							<i class="glyphicon glyphicon-pencil manita edtSubpreg" id="edtSubpreg_<?php echo $sp['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-question-sign manita condSubpreg" id="condSubpreg_<?php echo $sp['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-trash manita rojo delSubpreg" id="delSubpreg_<?php echo $sp['id'];?>"></i>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-6">
							<div class="dResp" id="dResp_<?php echo $sp['id'];?>">
								<?php echo TR('answers') ?>: 
								<i class="glyphicon glyphicon-chevron-right manita verRespSub" id="verRespSub_<?php echo $sp['id'];?>"></i>
							</div>
							<div class="respuestas" id="respuestas_<?php echo $sp['id'];?>" style="">
								<?php 
									$_POST['pregId'] = $sp['id'];
									include 'respuestas.php'; 
								?>
							</div>
						</div>
			<?php
						break;
					case 'num':
			?>

						<div class="col-sm-12 col-md-12 col-lg-12 nombre" id="nombre">
							<div class="muevePreg arrastra">
								<?php echo $sp['pregunta']; ?>
							</div>
							<hr/>
							<?php echo TR('type') ?>: <strong><?php echo TR($sp['tsiglas']); ?></strong><br/>
							<?php echo TR('points') ?>: <strong><?php echo $sp['puntos']; ?></strong><br/>
							<?php echo TR('toRecord') ?>: <strong><?php echo $sp['influyeValor'] == 1?TR('yes'):TR('no'); ?></strong><br/>
							Id: <strong><?php echo $sp['identificador']; ?></strong><br/>
							<i class="glyphicon glyphicon-pencil manita edtSubpreg" id="edtSubpreg_<?php echo $sp['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-question-sign manita condSubpreg" id="condSubpreg_<?php echo $sp['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-trash manita rojo delSubpreg" id="delSubpreg_<?php echo $sp['id'];?>"></i>
						</div>

			<?php
						break;
					case 'ab':
			?>
						<div class="col-sm-12 col-md-12 col-lg-12 nombre" id="nombre">
							<div class="muevePreg arrastra">
								<?php echo $sp['pregunta']; ?>
							</div>

							<hr/>
							<?php echo TR('type') ?>: <strong><?php echo TR($sp['tsiglas']); ?></strong><br/>
							<?php echo TR('points') ?>: <strong><?php echo $sp['puntos']; ?></strong><br/>
							<?php echo TR('toRecord') ?>: <strong><?php echo $sp['influyeValor'] == 1?TR('yes'):TR('no'); ?></strong><br/>
							Id: <strong><?php echo $sp['identificador']; ?></strong><br/>
							<i class="glyphicon glyphicon-pencil manita edtPreg" id="edtPreg_<?php echo $sp['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-question-sign manita condPreg" id="condPreg_<?php echo $sp['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-trash manita rojo delPreg" id="delPreg_<?php echo $sp['id'];?>"></i>
						</div>
			<?php
						break;
					default:
						break;
				}
			?>
			</div>
		</li>
	<?php endforeach ?>
</ul>

