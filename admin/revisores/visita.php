<?php  

	session_start();
	if($_SESSION['IU']['admin']['nivel'] <10){
		exit('No tienes acceso');
	}

	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';

	$vId = isset($_POST['vId'])?$_POST['vId']:14112;//4536;//6252;

	$chk = new Checklist($vId);
	$chkInf = $chk->getGeneral();

	
	$estructura = $chk -> getEstructura();
	$pregs = $chk->getResultados($vId);
	// print2($chk);

	$c = calculos($pregs,$estructura);

	// print2($estructura);
	// echo "VALRESP:".valResp($pregs,"p_23_255_179_1821")."<br/>";
	
	$sql = "SELECT c.id as cId, c.nombre as cNom, m.nombre as mNom, t.nombre as tNom, v.resumen as vRes, 
		v.hora as horaIni, v.horaSalida as horaFin, v.fecha, v.aceptada, v.gasto,
		CONCAT(COALESCE(t.calle,''),' ',COALESCE(t.numExt,''),' ',COALESCE(t.numInt,'')) as direccion,
		s.nombre as sNom, t.id as tId, t.POS as POS, edo.nombre as edo, rot.audio, rot.video, rot.fotografias
		FROM Visitas v 
		LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		LEFT JOIN Marcas m ON m.id = t.marcasId
		LEFT JOIN Clientes c ON c.id = m.clientesId
		LEFT JOIN Shoppers s ON s.id = v.shoppersId
		LEFT JOIN Estados edo ON edo.id = t.estado
		WHERE v.id = $vId";
	// echo $sql;
	$vis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

	if($vis['aceptada'] < 60 || $vis['aceptada'] >= 90){
		exit('Esta visita no puede ser revisada');
	}
	// print2($vis);


	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $vis[cId]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$LJ = '';
	$fields = '';
	for ($i=0; $i <$numDim ; $i++) {
		if($i == 0){
			$LJ .= " LEFT JOIN DimensionesElem de0 ON de0.id = t.dimensionesElemId 
					 LEFT JOIN Dimensiones d0 ON d0.id = de0.dimensionesId ";
			$fields .= "d0.nombre as d0, de0.nombrePub as de0";
		}else{
			$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre
					 LEFT JOIN Dimensiones d$i ON d$i.id = de$i.dimensionesId";
			$fields .= ",d$i.nombre as d$i, de$i.nombrePub as de$i";
		}
	}

	$sql = "SELECT $fields  FROM Visitas v 
		LEFT JOIN Rotaciones rot ON v.rotacionesId = rot.id 
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		$LJ
		WHERE v.id = $vId";

	// echo $sql;
	$dims = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
	$ponderacion = $c['chk']['max'] != 0?100/$c['chk']['max']:0;
	// echo "Cuentas: ".cuenta($pregs,"p_23_255_179_1820")."<br/>";
	// print2($c);
	// print2($pregs);

?>
<?php if ($_POST['div'] != 1){ ?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />

		<title>Shoppers Consulting</title>
		
		<!-- LIBRERIAS CSS -->
		<link href="<?php echo aRaizHtml();?>lib/js/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo aRaizHtml();?>lib/j/j.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo aRaizHtml();?>lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo aRaizHtml();?>lib/css/general.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo aRaizHtml();?>lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />
		
		<!-- LIBRERIAS JAVASCRIPT -->
		<script src="<?php echo aRaizHtml();?>lib/js/jquery-3.1.1.min.js"></script>
		<script src="<?php echo aRaizHtml();?>lib/js/jqueryUI/jquery-ui.js"></script>
		<script src="<?php echo aRaizHtml();?>lib/js/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo aRaizHtml();?>lib/js/audiojs/audiojs/audio.min.js"></script>
		
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-more.js"></script>
		<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/data.js"></script>
		<script src="https://code.highcharts.com/modules/drilldown.js"></script>


		<script src="<?php echo aRaizHtml();?>lib/js/graficas.js"></script>
		<script src="<?php echo aRaizHtml();?>lib/j/j.js"></script>
	

	</head>
<?php } ?>
	<script>
		$(document).ready(function() {
			var div = <?php echo $_POST['div'] == 1?1:0; ?>;
			var calif = parseFloat(<?php echo $c['chk']['prom']*100; ?>).toFixed(2);
			calif = parseFloat(calif);
			grafCalifFinal($('#grCalif'),calif);


			$('#multimedia').on('click', '.verImg', function(event) {
				event.preventDefault();
				var imgId = $(this).closest('div').attr('id').split('_')[1];  //this.id.split('_')[1];
				// console.log(imgId);
				popUp('reportes/visitas/imgVer.php',{imgId:imgId,div:div},function(e){},{});
			});



			$(function () {
			  $('[data-toggle="tooltip"]').tooltip({html:true})
			})

			var est = <?php echo atj($pregs); ?>;
			var vId = '<?php echo $_POST['vId'];?>';
			$('.edtResp').click(function(event) {
				pId = this.id.split('-')[1];
				pIdentificador = this.id.split('-')[2];

				popUp('admin/revisores/edtPreg.php',{vId:vId,datos:est[pIdentificador]})

			});
			$('#edtVisDat').click(function(event) {
				var vId = '<?php echo $_POST['vId']; ?>';
				// console.log(vId);
				popUp('admin/revisores/areaGeneral.php',{vId:vId},function(){},{});
			});

			$('#multimedia').on('click', '.multDel', function(event) {
				event.preventDefault();
				var multId = this.id.split('_')[1]
				conf('¿Desea eliminar el elemento?',{multId:multId,ele:$(this)},function(e){
					// console.log(e);
					var multId = e.multId;
					var rj = jsonF('admin/revisores/json/json.php',{acc:4,vId:<?php echo $_POST['vId'];?>,mId:multId});
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						e.ele.closest('li').remove();
					}
				})
			});

			$("#revisada").click(function(event) {
				
				popUp('admin/revisores/revConf.php',{},function(e){
					$('#popUp #envOkModal').click(function(event) {
						var vId = '<?php echo $_POST['vId']; ?>';
						var calif = $('#shopCalif').val();
						var rj = jsonF('admin/revisores/json/json.php',{acc:1,vId:vId,opt:1,calif:calif});
						// console.log(rj);
						var r = $.parseJSON(rj);
						if(r.ok == 1){
							$('#porRevisar').empty();
							$('#tabPorRevisar').trigger('click');
							alerta('success','La visita ha sido marcada como revisada');

						}
						$('#popUp #cPop').trigger('click');
					});
				},{});
			});
			$("#necRev").click(function(event) {
				popUp('admin/revisores/revRech.php',{},function(e){
					$('#popUp #envOkModal').click(function(event) {
						var vId = '<?php echo $_POST['vId']; ?>';
						var calif = $('#shopCalif').val();
						var rj = jsonF('admin/revisores/json/json.php',{acc:1,vId:vId,opt:2,calif:calif});
						// console.log(rj);
						var r = $.parseJSON(rj);
						if(r.ok == 1){
							$('#porRevisar').empty();
							$('#tabPorRevisar').trigger('click');
							alerta('success','La visita ha sido marcada como revisada');

						}
						$('#popUp #cPop').trigger('click');
					});
				},{});
			});


			<?php if ($vis['audio'] == 1){ ?>
				subArch($('#subeAudios'),4,'audio_<?php echo $_POST['vId']; ?>_','mp3',false,function(e){
					// console.log(e);
					var rj = jsonF('admin/revisores/json/json.php',{dat:e,acc:3,tipo:2,vId:<?php echo $_POST['vId']; ?>})
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('<li>')
						.attr({
							class:'list-group-item imgEle',
							id:'audioEle_'+r.nId
						})
						.appendTo('#audioList')
						.html(	
							'<div class="row">'+
								'<div class="col-md-10" id="audioNom_'+r.nId+'">'+
								'<audio class="video-js" controls preload="auto" width="320" height="132" data-setup="{}">'+
								  '<source src="../archivos/'+e.prefijo+e.nombreArchivo+'" >'+
								'</audio>'+
								'</div>'+
								'<div class="col-md-2" style="text-align: right;">'+
									'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
										'id="audioDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
								'</div>'+
							'</div>'
						)
					}
				});
			<?php } ?>


			<?php if ($vis['video'] == 1){ ?>
				subArch($('#subeVideos'),4,'video_<?php echo $_POST['vId']; ?>_','avi,mpg4,mov,mp4',false,function(e){
					// console.log(e);
					var rj = jsonF('admin/revisores/json/json.php',{dat:e,acc:3,tipo:3,vId:<?php echo $_POST['vId']; ?>})
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('<li>')
						.attr({
							class:'list-group-item videoEle',
							id:'videoEle_'+r.nId
						})
						.appendTo('#videoList')
						.html(	
							'<div class="row">'+
								'<div class="col-md-10" id="videoNom_'+r.nId+'">'+
									'<video class="video-js" controls preload="auto" width="320" height="132" data-setup="{}">'+
									  '<source src="../archivos/'+e.prefijo+e.nombreArchivo+'" >'+
									'</video>'+
								'</div>'+
								'<div class="col-md-2" style="text-align: right;">'+
									'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
										'id="videoDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
								'</div>'+
							'</div>'
						)
					}
				});
			<?php } ?>


			<?php if ($vis['fotografias'] == 1){ ?>
				subArch($('#subeFotografias'),4,'fotografia_<?php echo $_POST['vId']; ?>_','jpg,png,gif,jpeg',false,function(e){
					// console.log(e);
					var rj = jsonF('admin/revisores/json/json.php',{dat:e,acc:3,tipo:1,vId:<?php echo $_POST['vId']; ?>})
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('<li>')
						.attr({
							class:'list-group-item imgEle',
							id:'imgEle_'+r.nId
						})
						.appendTo('#imgList')
						.html(	
							'<div class="row">'+
								'<div class="col-md-10" id="imgNom_'+r.nId+'">'+
									'<img src="../archivos/'+e.prefijo+e.nombreArchivo+'" class="verImg" height="100px"/>'+
								'</div>'+
								'<div class="col-md-2" style="text-align: right;">'+
									'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
										'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
								'</div>'+
							'</div>'
						)
					}
				});
			<?php } ?>




		});
	</script>

	<?php if ($_POST['div'] != 1){ ?>
		

<body style="background-color: #FFF;">
	<div class="container" >
		<div class="header" id="header"><?php include raiz().'reportes/layout/header.php'; ?></div>
	<?php } ?>
		<div>

			<!-- Datos de la visita -->
			<div style="text-align: center;">
				<h2>Evaluación de cliente</h2>
			</div>
			<table class="table">
				<thead>
					<tr>
						<th>Cliente</th>
						<th>Socio</th>
						<th>Punto de venta visitado</th>
						<th>POS</th>
						<th>Estado</th>
						<th>Dirección</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $vis['cNom']; ?></td>
						<td><?php echo $vis['mNom']; ?></td>
						<td><?php echo $vis['tNom']; ?></td>
						<td><?php echo $vis['POS']; ?></td>
						<td><?php echo $vis['edo']; ?></td>
						<td><?php echo $vis['direccion']; ?></td>
						<td>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="table">
				<thead>
					<tr>
						<th>Fecha de la visita</th>
						<th>Hora de ingreso</th>
						<th>Hora de salida</th>
						<th>Shopper</th>
						<?php if ($chkInf['tipoReembolso'] >= 1){ ?>
							<th>Cantidad pagada</th>
						<?php } ?>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $vis['fecha']; ?></td>
						<td><?php echo $vis['horaIni']; ?></td>
						<td><?php echo $vis['horaFin']; ?></td>
						<td><?php echo $vis['sNom']; ?></td>
						<?php if ($chkInf['tipoReembolso'] >= 1){ ?>
							<td>$<?php echo $vis['gasto']; ?></td>
						<?php } ?>

						<td><span class="btn btn-sm btn-shop " id="edtVisDat">Editar datos generales y resumen</span></td>
					</tr>
				</tbody>
			</table>

			<!-- Encabezado -->

			<table class="table">
				<?php 
					foreach ($c['bloques'] as $bId => $b){
						if($b['encabezado'] != 1){
							continue;
						}
				?>
					<?php 
						foreach ($b['areas'] as $aId => $a){
					?>
						<?php 
							$pI = 1;
							foreach ($a['preguntas'] as $p){ 
								$pp = $pregs[$p['identificador']];
						?>
							<?php 
								if ($p['tipo'] != 'sub'){
							?>
								<tr>
									<td><?php echo $pp['pregunta']; ?></td>

									<td style="text-align: center;">
										<i class="glyphicon glyphicon-pencil edtResp manita" 
											id="edt-<?php echo "$pp[id]-$pp[identificador]"; ?>"></i>
											<?php echo $pp['nomResp'] == ''?$pp['respuesta']:$pp['nomResp']; ?>

									</td>
									<td><?php echo $pp['justificacion']; ?></td>
								</tr>
							<?php }else{ ?>
								<tr>
									<td colspan="3" class="nuevo"><?php echo $pp['pregunta'];?></td>
								</tr>
								<?php 
									foreach ($p['subpregs'] as $sp){ 
										$spp = $pregs[$sp['identificador']];
								?>
									<tr>
										<td><?php echo $spp['pregunta'];?></td>
										<td style="text-align: center;">
											<?php echo $spp['nomResp'] == '' ? $spp['respuesta']:$spp['nomResp']; ?>
										</td>
										<td><?php echo $spp['justificacion']; ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<tr><td colspan="3"></td></tr>
					<?php } ?>
				<?php } ?>
			</table>

			<!-- MULTIMEDIA -->
			<?php 

				$mult = $db->query("SELECT m.tipo as tip, m.* FROM Multimedia m
				WHERE visitasId = $vId")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

				$rotStmt = $db->prepare("SELECT r.* FROM Rotaciones r 
					LEFT JOIN Visitas v ON v.rotacionesId = r.id
					WHERE v.id = ?");

				$rotStmt -> execute([$vId]);
				$rotInf = $rotStmt -> fetchAll(PDO::FETCH_ASSOC)[0];
				// print2($rotInf);

				if($rotInf['audio'] == 1 || $rotInf['video'] == 1 || $rotInf['fotografias'] == 1){
			?>

			<div class="row">
				<div class="col-md-4">Multimedia</div>
				<div class="col-md-8">
					<div id="multimedia">
						<?php if ($rotInf['fotografias'] == 1){ ?>
							<div>
								<div class="nuevo">Imágenes</div>
								<div style='text-align:right;margin:5px;'>
									Subir imágenes: <span id='subeFotografias'></span>
								</div>
								<ul id="imgList" class="list-group">
									<?php
										$mult['img'] = is_array($mult['img'])?$mult['img']:array();
										foreach ($mult['img'] as $i){
									?>
										<li class="list-group-item imgEle" id="imgEle_<?php echo $i['id'];?>">
											<div class="row">
												<div class="col-md-10" id="imgNom_<?php echo $i['id'];?>">
													<img src="../archivos/<?php echo $i['archivo'];?>" height="100px"/>
												</div>
												<div class="col-md-2" style="text-align: right;">
													<i class="glyphicon glyphicon-trash manita multDel rojo" 
														id="imgDel_<?php echo $i['id'];?>"></i>&nbsp;&nbsp;
												</div>
											</div>
										</li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>

						<?php if ($rotInf['audio'] == 1){ ?>
							<div>
								<div class="nuevo">Audios</div>
								<div style='text-align:right;margin:5px;'>
									Subir audios: <span id='subeAudios'></span>
								</div>
								<ul id="audioList" class="list-group">
									<?php
										$mult['audio'] = is_array($mult['audio'])?$mult['audio']:array();
										foreach ($mult['audio'] as $i){
									?>
										<li class="list-group-item audioEle" id="audioEle_<?php echo $i['id'];?>">
											<div class="row">
												<div class="col-md-10" id="audioNom_<?php echo $i['id'];?>">
													<audio class="video-js" controls preload="auto" data-setup="{}">
													  <source src="../archivos/<?php echo $i['archivo'];?>" >
													</audio>
												</div>
												<div class="col-md-2" style="text-align: right;">
													<i class="glyphicon glyphicon-trash manita multDel rojo" 
														id="audioDel_<?php echo $i['id'];?>"></i>&nbsp;&nbsp;
												</div>
											</div>
										</li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>
						<?php if ($rotInf['video'] == 1){ ?>
							<div>
								<div class="nuevo">Videos</div>
								<div style='text-align:right;margin:5px;'>
									Subir videos: <span id='subeVideos'></span>
								</div>
								<ul id="videoList" class="list-group">
									<?php
										$mult['video'] = is_array($mult['video'])?$mult['video']:array();
										foreach ($mult['video'] as $i){
									?>
										<li class="list-group-item videoEle" id="videoEle_<?php echo $i['id'];?>">
											<div class="row">
												<div class="col-md-10" id="videoNom_<?php echo $i['id'];?>">
													<video class="video-js" controls preload="auto" width="320" height="132" data-setup="{}">
													  <source src="../archivos/<?php echo $i['archivo'];?>" >
													</video>
												</div>
												<div class="col-md-2" style="text-align: right;">
													<i class="glyphicon glyphicon-trash manita multDel rojo" 
														id="videoDel_<?php echo $i['id'];?>"></i>&nbsp;&nbsp;
												</div>
											</div>
										</li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>




						<?php
							foreach ($mult as $tipo => $archs) {
								if ($tipo == 'img') {?>
								<?php }elseif($tipo == 'audio'){ ?>
								<?php }elseif($tipo == 'video'){ ?>
								<?php } ?>
						<?php 
							}
						?>
					</div>
				</div>
			</div>
			<?php } ?>

			<!-- Tabla general -->
		</div>

		<!-- Resumen -->
		<?php if ($vis['vRes'] != ''){ ?>
			<div>
				<div class="nuevo bloque negro">
					Resumen
				</div>
				<div style="text-align: justify;">
					<?php echo $vis['vRes']; ?>
				</div>
			</div>
			<br/>

		<?php } ?>
		<!-- Tabla de preguntas -->

		<div>
			<table class="table">
				<?php 
					$bI = 0;
					foreach ($c['bloques'] as $bId => $b){
						if($b['encabezado'] == 1){
							continue;
						}
				?>
					<tr>
						<td class="negro bloque" colspan="7"><?php echo $b['nombre']; ?></td>
					</tr>
					<?php 
						$aI = 1;
						foreach ($b['areas'] as $aId => $a){
					?>
						<?php if ($a['muestra'] == 1){ ?>
							<tr>
								<td rowspan="2" class="area"><?php echo $aI++; ?></td>
								<td rowspan="2" colspan="2" class="negro area"><?php echo $a['nombre']; ?></td>
								<td class="negro">Calificación</td>
								<td style="background-color: black; color:white;">Puntos</td>
								<td class="negro">Respuestas</td>
								<td class="negro area">Observaciones</td>
							</tr>
							<tr>
								<td style="background-color: #666;color:white;">
									<?php echo $a['prom']==='-'?'-':number_format($a['prom']*100,2); ?>
								</td>
								<td style="background-color: #666;color:white;"></td>
								<td style="background-color: #666;color:white;text-align: center;">
									<?php echo "$a[pregPos]/$a[pregTot]"; ?>
								</td>
								<td style="background-color: #666;color:white;"></td>
							</tr>
							<?php 
								$pI = 1;
								foreach ($a['preguntas'] as $p){ 
									$pp = $pregs[$p['identificador']];
							?>
								<?php 
									if ($pp['muestra'] == 1){
								?>
									<?php 
										if ($p['tipo'] == 'sub'){
											$ii = 0;
											foreach ($p['subpregs'] as $sp) {
												$spp = $pregs[$sp['identificador']];
												if($spp['muestra'] == 1){
													$ii++;
												}
											}
									?>
										<tr>
											<td class="negro"><?php echo $pI++ ?></td>
											<td rowspan="<?php echo $ii; ?>" class="subArea"><?php echo $pp['pregunta']; ?></td>
											<?php
												$isp = 0;
												foreach ($p['subpregs'] as $sp){ 
													$spp = $pregs[$sp['identificador']];
													$pond = $c['bloques'][$spp['bloque']]['areas'][$spp['area']]['pond'];
											?>
												<?php 
													if ($spp['muestra'] == 1){
														if($spp['influyeValor'] == 0 || $spp['valPreg'] === '-'){
															$clasePuntos = 'NA';
															// $claseIcono = '';
														}else{
															if($spp['valPreg']>0){
																$clasePuntos = 'buena';
																// $claseIcono = 'glyphicon glyphicon-ok-sign verde';
															}else{
																$clasePuntos = 'mala';
																// $claseIcono = 'glyphicon glyphicon-remove-sign rojo';
															}

														}
												?>
													<?php if ($isp == 0){$isp++; ?>
														<td colspan="2">
															<?php echo $spp['pregunta'];?>&nbsp;
															<?php if (!empty($spp['comVerif'])){ ?>
																<i class="glyphicon glyphicon-comment" 
																	data-toggle="tooltip" data-placement="right" 
																	title="<?php echo $spp['comVerif']; ?>"></i>
															<?php } ?>

															
														</td>
														<td class="<?php echo $clasePuntos ?>">
															<i class="<?php echo $claseIcono ?>"></i>
															<?php echo $clasePuntos != 'NA' ? number_format($spp['valPreg']*$pond,2):''; ?>

														</td>
														<td style="text-align: center;">
															<i class="glyphicon glyphicon-pencil edtResp manita" 
																id="edt-<?php echo "$spp[id]-$spp[identificador]"; ?>"></i>
															<?php echo $spp['nomResp']; ?>
														</td>
														<td><?php echo $spp['justificacion']; ?></td>
													<?php }else{?>
														<tr>
															<td class="negro"><?php echo $pI++ ?></td>
															<td colspan="2">
																<?php echo $spp['pregunta'];?>&nbsp;
																<?php if (!empty($spp['comVerif'])){ ?>
																	<i class="glyphicon glyphicon-comment" 
																		data-toggle="tooltip" data-placement="right" 
																		title="<?php echo $spp['comVerif']; ?>"></i>
																<?php } ?>
															</td>
															<td class="<?php echo $clasePuntos ?>">
																<i class="<?php echo $claseIcono ?>">
																</i>
																<?php echo $clasePuntos != 'NA' ? number_format($spp['valPreg']*$pond,2):''; ?>
															</td>
															<td style="text-align: center;">
																<i class="glyphicon glyphicon-pencil edtResp manita" 
																	id="edt-<?php echo "$spp[id]-$spp[identificador]"; ?>"></i>		
																<?php echo $spp['nomResp']; ?>
															</td>
															<td><?php echo $spp['justificacion']; ?></td>
														</tr>
													<?php } ?>
												<?php }else{ continue; } ?>
												
											<?php } ?>

										</tr>
									<?php 
										}else{
											$pond = $c['bloques'][$pp['bloque']]['areas'][$pp['area']]['pond'];
											if($pp['influyeValor'] == 0 || $pp['valPreg'] === '-'){
												$clasePuntos = 'NA';
												// $claseIcono = '';
											}else{
												if($pp['valPreg']>0){
													$clasePuntos = 'buena';
													// $claseIcono = 'glyphicon glyphicon-ok-sign verde';
												}else{
													$clasePuntos = 'mala';
													// $claseIcono = 'glyphicon glyphicon-remove-sign rojo';
												}
											}
									?>
										<tr>
											<td class="negro"><?php echo $pI++ ?></td>
											<td colspan="3">
												<?php echo $pp['pregunta']; ?>&nbsp;
												<?php if (!empty($pp['comVerif'])){ ?>
													<i class="glyphicon glyphicon-comment" 
														data-toggle="tooltip" data-placement="right" 
														title="<?php echo $pp['comVerif']; ?>"></i>
												<?php } ?>

											</td>
											<td class="<?php echo $clasePuntos ?>">
												<i class="<?php echo $claseIcono ?>"></i>
												<?php echo $clasePuntos != 'NA' ? number_format($pp['valPreg']*$pond,2):''; ?>
											</td>

											<td style="text-align: center;">
												<i class="glyphicon glyphicon-pencil edtResp manita" 
													id="edt-<?php echo "$pp[id]-$pp[identificador]"; ?>"></i>		
												<?php echo $pp['nomResp']; ?>
											</td>
											<td><?php echo $pp['justificacion']; ?></td>
										</tr>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</table>
			<div style="text-align: center">
				<span class="btn btn-sm btn-shop" id="necRev">Necesita revisión con el shopper</span>
				<span class="btn btn-sm btn-shop" id="revisada">Marcar como revisada</span>
			</div>
		</div>
<?php if ($_POST['div'] != 1){ ?>
	</div>
	<div class="modal fade" id="popUp" role="dialog" style="">
		<div id="modal" class="modal-dialog">
			<div class="modal-content" style="border-radius: 0px;" id="popCont">
				Cargando...
			</div>
		</div>
	</div>
</body>
</html>

<?php } ?>

