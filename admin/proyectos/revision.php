<?php  
	session_start();
	if($_SESSION['CM']['admin']['nivel'] <10){
		exit('No tienes acceso');
	}

	$chk = "$_POST[vId]_NLIU";
	$v = password_verify($chk,$_POST['hash']);




	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';

	// print2($_POST);
	// print2($_POST);
	// if(!$v){
	// 	exit('<div class="nuevo">NO EXISTE LA VISITA SOLICITADA</div>');
	// }

	// print2($_POST);

	// $visita = $db->query("SELECT v.* 
	// 	FROM Visitas v
	// 	LEFT JOIN Rotaciones r ON v.id = r.visitaAct
	// 	WHERE r.id = $_POST[rotId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// // print2($visita['aceptada']);

	// if($visita['aceptada'] < 60 || $visita['aceptada'] >=100){
	// 	exit("<br/><div class='nuevo'>ESTA VISITA NO SE PUEDE REVISAR</div>");
	// }

	// print2($visita);
	// $vId = isset($vId)?$vId:14112;//4536;//6252;
	$vId = $_POST['vId'];
	// print2($vId);
	// echo "aqui<br/>";
	// exit();


	$chk = new Checklist($vId);
	$chkInf = $chk->getGeneral();


	// print2($chkInf);

	
	$estructura = $chk -> getEstructura();
	$pregs = $chk->getResultados($vId);
	// print2($chk);

	$c = calculos($pregs,$estructura);

	// print2($estructura);
	// echo "VALRESP:".valResp($pregs,"p_23_255_179_1821")."<br/>";
	
	$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre, v.fecha, v.resumen as vRes,c.instalacionRealizada, c.instalacionSug, v.equipo, v.fechaRealizacion, v.horaRealizacion,
		c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal,
		CONCAT(IFNULL(u.nombre,''),' ',IFNULL(u.aPat,''),' ',IFNULL(u.aMat,'')) as uNom
		FROM Visitas v
		LEFT JOIN Clientes c ON c.id = v.clientesId
		LEFT JOIN Estados e ON e.id = c.estadosId
		LEFT JOIN Municipios m ON m.id = c.municipiosId
		LEFT JOIN usrAdmin u ON u.id = v.usuarioRealizo
		WHERE v.id = $vId
		";
	// echo $sql;
	$vis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

	$equipos = $db -> query("SELECT * FROM InstalacionesEquipos 
		WHERE instalacionesId = $vis[instalacionRealizada]")->fetchAll(PDO::FETCH_ASSOC);

	$personal = $db->query("SELECT ep.nivel, 
		CONCAT(IFNULL(u.nombre,''),' ',IFNULL(u.aPat,''),' ',IFNULL(u.aMat,'')) as uNom
		FROM EquiposPersonal ep 
		LEFT JOIN usrAdmin u ON u.id = ep.usuariosId
		WHERE equiposId = '$vis[equipo]'
		ORDER BY nivel
		")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$personal[42] = empty($personal[42])?array():$personal[42];
	$personal[44] = empty($personal[44])?array():$personal[44];


	// print2($equipos);
	// if($vis['aceptada'] < 60 || $vis['aceptada'] >= 100){
	// 	exit('<br/><div class="nuevo">Esta visita no puede ser revisada</br>');
	// }
	// print2($vis);


	// $nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $vis[cId]")->fetch(PDO::FETCH_NUM);
	// $numDim = $nD[0];

	// $LJ = '';
	// $fields = '';
	// for ($i=0; $i <$numDim ; $i++) {
	// 	if($i == 0){
	// 		$LJ .= " LEFT JOIN DimensionesElem de0 ON de0.id = t.dimensionesElemId 
	// 				 LEFT JOIN Dimensiones d0 ON d0.id = de0.dimensionesId ";
	// 		$fields .= "d0.nombre as d0, de0.nombrePub as de0";
	// 	}else{
	// 		$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre
	// 				 LEFT JOIN Dimensiones d$i ON d$i.id = de$i.dimensionesId";
	// 		$fields .= ",d$i.nombre as d$i, de$i.nombrePub as de$i";
	// 	}
	// }

	// $sql = "SELECT $fields  FROM Visitas v 
	// 	LEFT JOIN Rotaciones rot ON v.rotacionesId = rot.id 
	// 	LEFT JOIN Tiendas t ON t.id = rot.tiendasId
	// 	$LJ
	// 	WHERE v.id = $vId";

	// // echo $sql;
	// $dims = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
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

		<title>Hábitat IU</title>
		
		<!-- LIBRERIAS CSS -->
		<link href="../../lib/js/bootstrap4/css/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="../../lib/j/j.css" rel="stylesheet" type="text/css" />
		<link href="../../lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
		<link href="../../lib/css/general.css" rel="stylesheet" type="text/css" />
		<link href="../../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />
		
		<!-- LIBRERIAS JAVASCRIPT -->

		<script src="../../lib/js/graficas.js"></script>
	

	</head>
<?php } ?>
	<script>
		$(document).ready(function() {

			// $('#modal').css({width: '90%'});
			

			var div = <?php echo $_POST['div'] == 1?1:0; ?>;
			var calif = parseFloat(<?php echo is_numeric($c['chk']['prom'])?$c['chk']['prom']*100:0; ?>).toFixed(2);
			calif = parseFloat(calif);
			// grafCalifFinal($('#grCalif'),calif);


			$('#multimedia').on('click', '.verImg', function(event) {
				event.preventDefault();
				var imgId = $(this).closest('div').attr('id').split('_')[1];  //this.id.split('_')[1];
				// console.log(imgId);
				popUp('reportes/visitas/imgVer.php',{imgId:imgId,div:0},function(e){},{});
			});



			$(function () {
			  $('[data-toggle="tooltip"]').tooltip({html:true})
			})

			var est = <?php echo atj($pregs); ?>;
			var vId = '<?php echo $vId;?>';
			$('.edtResp').click(function(event) {
				pId = this.id.split('-')[1];
				pIdentificador = this.id.split('-')[2];

				console.log('aaaaa',pId,pIdentificador);
				// var rotId = <?php echo $_POST['rotId']; ?>;
				// var repId = <?php echo $_POST['repId']; ?>;
				var hash = '<?php echo $_POST['hash']; ?>';
				popUp('admin/revisores/edtPreg.php',{hash:hash,vId:vId,datos:est[pIdentificador],noRevisor:1})

			});
			$('#edtVisDat').click(function(event) {
				var vId = '<?php echo $vId; ?>';
				// console.log(vId);
				// var rotId = <?php echo $_POST['rotId']; ?>;
				// var repId = <?php echo $_POST['repId']; ?>;
				var hash = '<?php echo $_POST['hash']; ?>';
				popUp('admin/revisores/areaGeneral.php',{vId:vId,hash:hash,noRevisor:1},function(){},{});
				// console.log(rz);
			});

			$('#multimedia').on('click', '.multDel', function(event) {
				event.preventDefault();
				// console.log('asas');
				var multId = this.id.split('_')[1]
				conf('¿Desea eliminar el elemento?',{multId:multId,ele:$(this)},function(e){
					// console.log(e);
					var multId = e.multId;
					var rj = jsonF('admin/revisores/json/json.php',{acc:4,vId:<?php echo $vId;?>,mId:multId});
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						if('instalacion' == '<?php echo $vis['etapa']; ?>'){
							e.ele.closest('tr').find('.imgSubArch').show();
							e.ele.closest('tr').find('.imgCont').hide();
						}
						e.ele.closest('.imgEle').remove();
					}
				})
			});

			$("#revisada").click(function(event) {

				// console.log('aaa');
				popUp('admin/proyectos/revConf.php',{},function(e){
					$('#popUp #envOkModal').click(function(event) {
						console.log('asas');
						var vId = '<?php echo $vId; ?>';
						var calif = $('#shopCalif').val();
						var rj = jsonF('admin/proyectos/json/json.php',{acc:19,vId:vId,calif:calif});
						console.log(rj);
						var r = $.parseJSON(rj);
						if(r.ok == 1){
							$('#visita').empty();
							alerta('success','La visita ha sido marcada como revisada');
						}
						$('#popUp #cPop').trigger('click');
						});
				},{});

				// conf('Con esta acción declaras que la información contenida '+
				// 	'en esta visita es correcta y pasará a la etapa de auditoría para su publicación',{},function(e){
				// 		var vId = '<?php echo $vId; ?>';
				// 		var rj = jsonF('admin/proyectos/json/json.php',{acc:19,vId:vId});
				// 		// console.log(rj);
				// 		var r = $.parseJSON(rj);
				// 		if(r.ok == 1){
				// 			$('#visita').empty();
				// 			alerta('success','La visita ha sido marcada como revisada');
				// 		}
				// });
			});

			<?php if ($vis['audio'] == 1){ ?>
				subArch($('#subeAudios'),4,'audio_<?php echo $vId; ?>_','mp3,wav,mp2,m4a',false,function(e){
					// console.log(e);
					var rj = jsonF('admin/revisores/json/json.php',{dat:e,acc:3,tipo:2,vId:<?php echo $vId; ?>})
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
								  '<source src="'+rz+'archivos/'+e.prefijo+e.nombreArchivo+'" >'+
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

			<?php if($vis['etapa'] == 'instalacion'){  ?>
				<?php foreach ($fotosInst as $id => $nombre){ ?>
					subArchInst('<?php echo $id; ?>',<?php echo $vId; ?>)
				<?php } ?>
			<?php } ?>



				subArch($('#subeFotografias'),1,'fotografia_<?php echo $vId; ?>_','jpg,png,gif,jpeg',false,function(e){
					// console.log(e);
					var rj = jsonF('admin/revisores/json/json.php',{dat:e,acc:3,tipo:1,vId:<?php echo $vId; ?>})
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
									'<img src="'+rz+'campo/archivosCuest/'+e.prefijo+e.nombreArchivo+'" class="verImg" height="100px"/>'+
								'</div>'+
								'<div class="col-md-2" style="text-align: right;">'+
									'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
										'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
								'</div>'+
							'</div>'
						)
					}

				});

				$('.edtInst').click(function(event) {
					var vId = '<?php echo $vId; ?>';
					// console.log(vId);
					// var rotId = <?php echo $_POST['rotId']; ?>;
					// var repId = <?php echo $_POST['repId']; ?>;
					var hash = '<?php echo $_POST['hash']; ?>';
					popUp('admin/revisores/instalacion.php',{vId:vId,hash:hash,noRevisor:1},function(){},{});

				});


		});

	function subArchInst(idFoto,vId){
		var elem = $('#subArch_'+idFoto);
		subArch(elem,1,'fotografia_'+vId+'_'+idFoto+'_','jpg,png,gif,jpeg',false,function(e){
			var rj = jsonF('admin/revisores/json/json.php',{dat:e,acc:3,tipo:1,vId:vId})
			console.log(rj);
			var r = $.parseJSON(rj);
			console.log(idFoto,'fotografia_'+vId+'_'+idFoto+'_');
			if(r.ok == 1){
				$('#subArch_'+idFoto).closest('tr').find('.imgSubArch').hide();
				$('#subArch_'+idFoto).closest('tr').find('.imgCont').show();
				$('#subArch_'+idFoto).closest('tr').find('.imgCont').empty();
				$('<div>')
				.attr({
					class:'imgEle',
					id:'imgEle_'+r.nId
				})
				.appendTo($('#subArch_'+idFoto).closest('tr').find('.imgCont'))
				.html(	
					'<div class="row">'+
						'<div class="col-md-10" id="imgNom_'+r.nId+'">'+
							'<img src="'+rz+'campo/archivosCuest/'+e.prefijo+e.nombreArchivo+'" class="verImg" height="100px"/>'+
						'</div>'+
						'<div class="col-md-2" style="text-align: right;">'+
							'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
								'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
						'</div>'+
					'</div>'
				)

			}

		})
	}

	</script>


		<div>

			<!-- Datos de la visita -->
			<div style="text-align: center;">
				<h2 class="nuevo">Respuestas del cuestionario</h2>
			</div>
			<table class="table" style="font-size:small;">
				<thead>
					<tr>
						<th>Integrante IU</th>
						<th>Usuario</th>
						<th>Dirección</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $vis['uNom']; ?></td>
						<td><?php echo $vis['nombre']; ?></td>
						<td>
							<?php echo 
							"$vis[calle] $vis[numeroExt] $vis[numeroInt]<br/>
							$vis[colonia], <br/>$vis[mNom], $vis[eNom]<br/> CP: $vis[codigoPostal]
							"; ?>
						</td>
						<td><?php echo $vis['fechaRealizacion']; ?></td>
					</tr>
				</tbody>
			</table>
			<table class="table">
				<thead>
					<tr>
						<th>Fecha de la visita</th>
						<!-- <th>Hora de la visita</th> -->
						<?php if ($chkInf['tipoReembolso'] >= 1){ ?>
							<th>Cantidad pagada</th>
						<?php } ?>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $vis['fechaRealizacion']; ?></td>
						<!-- <td><?php echo $vis['horaRealizacion']; ?></td> -->
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
											<i class="glyphicon glyphicon-pencil edtResp manita" 
												id="edt-<?php echo "$spp[id]-$spp[identificador]"; ?>"></i>	
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
			?>
			<div class="row">
				<div class="col-md-4">Multimedia</div>
				<div class="col-md-8">
					<div id="multimedia">
						<div>
							<div class="nuevo">Imágenes</div>
								<?php if ($vis['etapa'] == 'instalacion'){ ?>
									<table border="0">
										<?php foreach ($fotosInst as $id => $nombre){ ?>
											<?php 
												$sql = "SELECT * 
												FROM Multimedia 
												WHERE archivo LIKE 'fotografia_".$vId."_$id"."_%' ";

												$img = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
											?>
											<tr>
												<td><?php echo $nombre; ?></td>
												<td class="imgSubArch"
												style="display:<?php echo empty($img)?'block':'none'; ?>">
													<span id="subArch_<?php echo $id; ?>"></span>
												</td>

												<td id="img_<?php echo $id; ?>" class="imgCont"
												style="display:<?php echo !empty($img)?'block':'none'; ?>" >
													<div class="imgEle" id="imgEle_<?php echo $i['id'];?>">							
														<div class="row">
															<div class="col-md-10" id="imgNom_<?php echo $img['id'];?>">
																<img src="../../campo/archivosCuest/<?php echo $img['archivo'];?>" 
																	height="100px" class="verImg" />
															</div>
															<div class="col-md-2" style="text-align: right;">
																<i class="glyphicon glyphicon-trash manita multDel rojo" 
																	id="imgDel_<?php echo $img['id'];?>"></i>&nbsp;&nbsp;
															</div>
														</div>
													</div>

												</td>
											</tr>
										<?php } ?>
									</table>
								<?php }else{ ?>
									<div style='text-align:right;margin:5px;'>
										Subir imágenes: <span id='subeFotografias'>aa</span>
									</div>
									<ul id="imgList" class="list-group">
										<?php
											$mult['img'] = is_array($mult['img'])?$mult['img']:array();
											foreach ($mult['img'] as $i){
										?>
											<li class="list-group-item imgEle" id="imgEle_<?php echo $i['id'];?>">
												<div class="row">
													<div class="col-md-10" id="imgNom_<?php echo $i['id'];?>">
														<img src="../../campo/archivosCuest/<?php echo $i['archivo'];?>" height="100px" class="verImg" />
													</div>
													<div class="col-md-2" style="text-align: right;">
														<i class="glyphicon glyphicon-trash manita multDel rojo" 
															id="imgDel_<?php echo $i['id'];?>"></i>&nbsp;&nbsp;
													</div>
												</div>
											</li>
										<?php } ?>
									</ul>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

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
								<td class="area"><?php echo $aI++; ?></td>
								<td colspan="2" class="negro area"><?php echo $a['nombre']; ?></td>
								<td class="negro"></td>
								<!-- <td style="background-color: #00aeef; color:white;"></td> -->
								<td class="negro" style="text-align: center;">Respuestas</td>
								<td class="negro area">Observaciones</td>
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
			<div style="text-align: center;margin-bottom: 10px;">
				<!-- <span class="btn btn-sm btn-shop" id="revisada">Marcar como revisada</span> -->
			</div>
		</div>
		<?php if ($chkInf['etapa'] == 'instalacion'){ ?>
			<div class="row">
				<div class="col-6">
					<div>
						<div class="nuevo negro">Equipo de instalación</div>
						<table class="table" style="font-size: x-small;">
							<thead>
								<tr>
									<th>Equipo</th>
									<th>Vehiculo</th>
									<th>Jefe de cuadrilla</th>
									<th>Instaladores</th>
									<th>Aprendices</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $equipo['eNom']; ?></td>
									<td><?php echo $equipo['vNom']; ?></td>
									<td><?php echo $equipo['instNom']; ?></td>
									<td>
										<?php foreach ($personal[44] as $u){ ?>
											<?php echo $u['uNom']; ?><br/>
										<?php } ?>
									</td>
									<td>
										<?php foreach ($personal[42] as $u){ ?>
											<?php echo $u['uNom']; ?><br/>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-6">
					<?php $vis['instalacionRealizada'] = empty($vis['instalacionRealizada'])?0:$vis['instalacionRealizada']; ?>
					<?php $datI = $db->query("SELECT * FROM Instalaciones WHERE id = $vis[instalacionRealizada]")->fetchAll(PDO::FETCH_ASSOC)[0]; ?>
					<div class="nuevo">Instalacion <i class="glyphicon glyphicon-pencil manita edtInst"></i></div>
					<div style="font-size: x-small;">
						
						<div style="border-bottom: solid 1px;width: 40%">
							<strong>Instalación realizada:</strong>
						</div>
						<div>
							<?php echo $datI['nombre']; ?>
						</div>
						<br/>

						<div style="border-bottom: solid 1px;width: 40%;">Componentes:</div>
						<div>
							<?php
								$equipos = $db -> query("SELECT * FROM InstalacionesEquipos 
									WHERE instalacionesId = $vis[instalacionRealizada]")->fetchAll(PDO::FETCH_ASSOC);
								foreach ($equipos as $e){ 
									$datEle = datosEquip($e['dimensionesElemId']);
									// print2($datEle);
									$html = "<strong> $datEle[area] :</strong><br/>";
									$arbol = $datEle['arbol'];
									for ($i=$datEle['numDim']-1; $i >= 0; $i--) { 

										// echo $i."<br/>";
										$html .= $arbol["d$i"]." : ".$arbol["de$i"];
										if ($i==0) {
											continue;
										}
										$html .= "&nbsp;<i class='glyphicon glyphicon-chevron-right'></i>&nbsp;";
									}
									if ($datEle['arbol']['variables'] == 1) {
										$unidad = $datEle['arbol']['unidad'];
										$html .= " (".$componentes[$e['dimensionesElemId']][0]['cantidad']." $unidad) ";
									}
								?>
									<?php echo "$html<br/><br/>" ?>
								<?php } ?>
						</div>
					</div>

				</div>
			</div>

		<?php } ?>
		<?php if ($chkInf['etapa'] == 'visita'){ ?>
			<?php $vis['instalacionSug'] = empty($vis['instalacionSug'])?0:$vis['instalacionSug']; ?>
			<?php $datI = $db->query("SELECT * FROM Instalaciones WHERE id = $vis[instalacionSug]")->fetchAll(PDO::FETCH_ASSOC)[0]; ?>
			<div class="nuevo">Instalacion <i class="glyphicon glyphicon-pencil manita edtInst"></i></div>
			<div style="border-bottom: solid 1px;width: 40%">
				<strong>Instalación sugerida:</strong>
			</div>
			<div>
				<?php echo $datI['nombre']; ?>
			</div>
			<br/>

			<div style="border-bottom: solid 1px;width: 40%;">Componentes:</div>
			<div>
				<?php
					$equipos = $db -> query("SELECT * FROM InstalacionesEquipos 
						WHERE instalacionesId = $vis[instalacionSug]")->fetchAll(PDO::FETCH_ASSOC);
					foreach ($equipos as $e){ 
						$datEle = datosEquip($e['dimensionesElemId']);
						// print2($datEle);
						$html = "<strong> $datEle[area] :</strong><br/>";
						$arbol = $datEle['arbol'];
						for ($i=$datEle['numDim']-1; $i >= 0; $i--) { 

							// echo $i."<br/>";
							$html .= $arbol["d$i"]." : ".$arbol["de$i"];
							if ($i==0) {
								continue;
							}
							$html .= "&nbsp;<i class='glyphicon glyphicon-chevron-right'></i>&nbsp;";
						}
						if ($datEle['arbol']['variables'] == 1) {
							$unidad = $datEle['arbol']['unidad'];
							// $html .= " (".$componentes[$e['dimensionesElemId']][0]['cantidad']." $unidad) ";
						}
					?>
						<?php echo "$html<br/><br/>" ?>
					<?php } ?>
			</div>
		<?php } ?>


<?php if ($_POST['div'] != 1){ ?>
	</div>
</body>
</html>

<?php } ?>

