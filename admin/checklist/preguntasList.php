<?php  

	if($_POST['ajax'] == 1){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Checklist

	// print2($_POST);
	$pregs = $db->query("SELECT p.*, t.siglas as tsiglas, t.nombre as nTipo FROM Preguntas p
		LEFT JOIN Tipos t ON t.id = p.tiposId
		WHERE areasId = $_POST[areaId] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
		ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	// print2($pregs);

?>

<script type="text/javascript">
	$(document).ready(function() {
		var mapInit = {};
		$( ".pregsSort" ).sortable({
			handle: ".muevePreg",
			scrollSpeed: 5,
			update: function( event, ui ) {
				$('#savePregsOrden').show();
			}
		});
		// $( ".pregsSort" ).disableSelection();

		// $('#pregsSort .edtPreg').click(function(event) {
		$('#pregsSort').on('click', '.edtPreg', function(event) {
			event.preventDefault();
			var pregId = this.id.split('_')[1];
			var areaId = <?php echo $_POST['areaId']; ?>;
			popUp('admin/checklist/preguntasAdd.php',{areaId:areaId,pregId:pregId},function(e){},{});
		});

		$('#pregsSort .condPreg').click(function(event) {
			var pregId = this.id.split('_')[1];
			popUp('admin/checklist/condiciones.php',{eleId:pregId,aplicacion:'preg'},function(e){},{});
		});

		$('#pregsSort .delPreg').click(function(event) {
			var pregId = this.id.split('_')[1];
			var areaId = <?php echo $_POST['areaId']; ?>;
				// console.log(pregId,areaId);
			conf('¿Está seguro que desea elilminar la pregunta?',{areaId:areaId,pregId:pregId},function(e){
				// console.log(pregId,areaId);
				var rj = jsonF('admin/checklist/json/json.php',{datos:{id:pregId,elim:1},acc:2,opt:7,chkId:checklistId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#preguntasList').load(rz+'admin/checklist/preguntasList.php',
						{ajax:1,areaId:areaId});
				}

			});

		});

		$('.verResp').click(function(event) {
			var pregId = this.id.split('_')[1];
			if($('#respuestas_'+pregId).is(':visible')){
				$(this).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
			}else{
				$(this).addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-right');
			}
				$('#respuestas_'+pregId).slideToggle();
		});

		$('.verMapa').click(function(event) {
			var pregId = this.id.split('_')[1];
			// console.log('aaa:',pregId);
			if($('#mapDiv_'+pregId).is(':visible')){
				$(this).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
			}else{
				$(this).addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-right');
				if(!mapInit[pregId]){
					mapInit[pregId] = true;
					setTimeout(function(){
						initMap(pregId);
					},500);
				}
			}
			$('#mapDiv_'+pregId).slideToggle();
		});

		$('.verSubpregs').click(function(event) {
			var pregId = this.id.split('_')[1];
			if($('#subpreguntas_'+pregId).is(':visible')){
				$(this).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
			}else{
				$(this).addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-right');
			}
			$('#subpreguntas_'+pregId).slideToggle();
		});


	});
</script>

<ul class="list-group pregsSort" style="margin-top: 10px;" id="pregsSort">
	<?php foreach ($pregs as $p): ?>
		<li class="list-group-item pregEle" id="pregEle_<?php echo $p['id'];?>">
			<div class="row">
			<?php
				switch ($p['tsiglas']) {
					case 'mult':
			?>
						<div class="col-sm-12 col-md-5 col-lg-5 nombr" id="nombre">
							<div class="muevePreg arrastra">
								<?php echo $p['pregunta']; ?>
							</div>
							<hr/>

							<?php echo TR('type'); ?>: <strong><?php echo TR($p['tsiglas']); ?></strong><br/>
							<?php echo TR('points'); ?>: <strong><?php echo $p['puntos']; ?></strong><br/>
							<?php echo TR('toRecord'); ?>: <strong><?php echo $p['influyeValor'] == 1?TR('yes'):TR('no'); ?></strong><br/>
							Id: <strong><?php echo $p['identificador']; ?></strong><br/>
							<i class="glyphicon glyphicon-pencil manita edtPreg" id="edtPreg_<?php echo $p['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-question-sign manita condPreg" id="condPreg_<?php echo $p['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-trash manita rojo delPreg" id="delPreg_<?php echo $p['id'];?>"></i>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-6">
							<div class="dResp" id="dResp_<?php echo $p['id'];?>">
								<?php echo TR('answers') ?>: 
								<i class="glyphicon glyphicon-chevron-right manita verResp" id="verResp_<?php echo $p['id'];?>"></i>
							</div>
							<div class="respuestas" id="respuestas_<?php echo $p['id'];?>" style="display: none;">
								<?php 
									$_POST['pregId'] = $p['id'];
									include 'respuestas.php'; 
								?>
							</div>
						</div>
			<?php
						break;
					case 'sub':
						$subpregs = $db->query("SELECT p.*, t.siglas as tsiglas, t.nombre as nTipo FROM Preguntas p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE areasId = $_POST[areaId] AND t.siglas != 'sub' AND subAreasId = $p[id]
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			?>
					<div class="col-sm-12 col-md-4 col-lg-4" id="nombre" 
					style="background-color: #E9E9E9;margin:-10px 0px;padding: 10px 15px;">
						<div class="muevePreg arrastra">
							<?php echo $p['pregunta']; ?>
						</div>
						<hr/>
						<?php echo TR('points'); ?>: <strong><?php echo $p['puntos']; ?></strong><br/>
						<?php echo TR('toRecord'); ?>: <strong><?php echo $p['influyeValor'] == 1?'Sí':'No'; ?></strong><br/>
						Id: <strong><?php echo $p['identificador']; ?></strong><br/>
						<i class="glyphicon glyphicon-pencil manita edtPreg" id="edtPreg_<?php echo $p['id'];?>"></i>&nbsp;
						<i class="glyphicon glyphicon-question-sign manita condPreg" id="condPreg_<?php echo $p['id'];?>"></i>&nbsp;
						<i class="glyphicon glyphicon-trash manita rojo delPreg" id="delPreg_<?php echo $p['id'];?>"></i>

					</div>
					<div class="col-sm-12 col-md-8 col-lg-8">
						<div class="dResp" id="dResp_<?php echo $p['id'];?>">
							<?php echo TR('questions'); ?>: 
							<i class="glyphicon glyphicon-chevron-right manita verSubpregs" id="verSubpregs_<?php echo $p['id'];?>"></i>
						</div>
						<div class="subpreguntas" id="subpreguntas_<?php echo $p['id'];?>" style="display:none;">
							<?php 
								$_POST['pregId'] = $p['id'];
								include 'subpreguntas.php'; 
							?>
						</div>
					</div>

			<?php
						break;
					case 'ab':
					case 'num':
			?>
						<div class="col-sm-12 col-md-12 col-lg-12" id="nombre">
							<div class="muevePreg arrastra">
								<?php echo $p['pregunta']; ?>
							</div>
							<hr/>
							<?php echo TR('type'); ?>: <strong><?php echo TR($p['tsiglas']); ?></strong><br/>
							<?php echo TR('points'); ?>: <strong><?php echo $p['puntos']; ?></strong><br/>
							<?php echo TR('toRecord'); ?>: <strong><?php echo $p['influyeValor'] == 1?TR('yes'):TR('no'); ?></strong><br/>
							Id: <strong><?php echo $p['identificador']; ?></strong><br/>
							<i class="glyphicon glyphicon-pencil manita edtPreg" id="edtPreg_<?php echo $p['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-question-sign manita condPreg" id="condPreg_<?php echo $p['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-trash manita rojo delPreg" id="delPreg_<?php echo $p['id'];?>"></i>
						</div>
			<?php
						break;
					case 'cm':
					case 'spatial':
					case 'op':
						$spatial = $p;

			?>			
						<div class="col-sm-12 col-md-5 col-lg-5 nombr" id="nombre">
							

							<div class="muevePreg arrastra">
								<?php echo $spatial['pregunta']; ?>
							</div>
							<hr/>

							<?php echo TR('type'); ?>: <strong><?php echo TR($spatial['tsiglas']); ?></strong><br/>
							<?php echo TR('points'); ?>: <strong><?php echo $spatial['puntos']; ?></strong><br/>
							<?php echo TR('toRecord'); ?>: <strong><?php echo $spatial['influyeValor'] == 1?TR('yes'):TR('no'); ?></strong><br/>
							Id: <strong><?php echo $spatial['identificador']; ?></strong><br/>
							<i class="glyphicon glyphicon-pencil manita edtPreg" id="edtPreg_<?php echo $spatial['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-question-sign manita condPreg" id="condPreg_<?php echo $spatial['id'];?>"></i>&nbsp;
							<i class="glyphicon glyphicon-trash manita rojo delPreg" id="delPreg_<?php echo $spatial['id'];?>"></i>
						</div>
						<div class="col-sm-12 col-md-7 col-lg-7" >
							<div class="dResp" id="dResp_<?php echo $spatial['id'];?>">
								<?php echo TR('map') ?>: 
								<i class="glyphicon glyphicon-chevron-right manita verMapa" id="verMapa_<?php echo $spatial['id'];?>"></i>
							</div>
							<div class="mapDiv" id="mapDiv_<?php echo $spatial['id'];?>" style="display:none;">
								<div id="map_<?php echo $spatial['id'];?>" style=" height: 500px;margin-top: 10px;" class="map"></div>
								<?php if ($spatial['tsiglas'] == 'spatial' || $spatial['tsiglas'] == 'op'){ break; }?>
								<?php include 'catsJS.php'; ?>
								<div style="margin-top: 10px;">
									<div class="nuevo"><?php echo TR('categories'); ?></div>
									<div class="row">
										<div class="col-6" style="text-align: right;">
											<input type="text" id="catNom_<?php echo $spatial['id'];?>" 
												class="form-control" placeholder="<?php echo TR('category'); ?>"><br/>
											<span id="btnCat_<?php echo $spatial['id'];?>" class="btn btn-shop" >
												<!-- <i class="glyphicon glyphicon-plus"></i> --> &nbsp;<?php echo TR('add'); ?>&nbsp;
												<i class="glyphicon glyphicon-arrow-right"></i>
											</span>
										</div>
										<div class="col-6" id="catList_<?php echo $spatial['id'];?>" >
											<?php include 'catList.php'; ?>
										</div>
									</div>
								</div>

							</div>
							
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
