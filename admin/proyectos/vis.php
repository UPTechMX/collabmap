<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['dimensionId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			nivel de equipo
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<?php  
	session_start();
	if($_SESSION['IU']['admin']['nivel'] <20){
		exit('No tienes acceso');
	}

	// print2($_POST);
	// $chk = "$_POST[vId]_$_POST[cteId]_NLSC";
	// $v = password_verify($chk,$_POST['hash']);
	

	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';

	// print2($_POST);

	$visita = $db->query("SELECT v.* FROM Visitas v
		WHERE v.id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($visita['aceptada']);

	// print2($visita);
	// $vId = isset($vId)?$vId:14112;//4536;//6252;
	$vId = $visita['id'];
	// print2($vId);
	// echo "aqui<br/>";
	// exit();


	$chk = new Checklist($vId);
	$chkInf = $chk->getGeneral();

	
	$estructura = $chk -> getEstructura();
	$pregs = $chk->getResultados($vId);
	// print2($chk);

	$c = calculos($pregs,$estructura);

	// print2($estructura);
	// echo "VALRESP:".valResp($pregs,"p_23_255_179_1821")."<br/>";
	
	$sql = "SELECT v.*, CONCAT(c.nombre,' ',c.aPat,' ',c.aMat) as nombre, v.fecha, v.resumen as vRes,
				c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal
				FROM Visitas v
				LEFT JOIN Clientes c ON c.id = v.clientesId
				LEFT JOIN Estados e ON e.id = c.estadosId
				LEFT JOIN Municipios m ON m.id = c.municipiosId
				WHERE v.id = $vId
				";
	// echo $sql;
	$vis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($vis);
	// if($visita['aceptada'] < 80 || $visita['aceptada'] >=100){

	// print2($vis);


	$ponderacion = $c['chk']['max'] != 0?100/$c['chk']['max']:0;
	// echo "Cuentas: ".cuenta($pregs,"p_23_255_179_1820")."<br/>";
	// print2($c);
	// print2($pregs);

?>
	<script>
		$(document).ready(function() {

			// $('#modal').css({width: '90%'});
			

			var div = 1;
			var calif = parseFloat(<?php echo is_numeric($c['chk']['prom'])?$c['chk']['prom']*100:0; ?>).toFixed(2);
			calif = parseFloat(calif);
			// grafCalifFinal($('#grCalif'),calif);


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
			var vId = '<?php echo $vId;?>';
			$('.edtResp').click(function(event) {
				pId = this.id.split('-')[1];
				pIdentificador = this.id.split('-')[2];
				var vId = <?php echo $_POST['vId']; ?>;
				var cteId = <?php echo $_POST['cteId']; ?>;
				var hash = '<?php echo $_POST['hash']; ?>';
				popUp('admin/revisores/edtPreg.php',{vId:vId,cteId:cteId,hash:hash,vId:vId,datos:est[pIdentificador],noRevisor:1})

			});
			$('#edtVisDat').click(function(event) {
				var vId = '<?php echo $vId; ?>';
				// console.log(vId);
				var vId = <?php echo $_POST['vId']; ?>;
				var cteId = <?php echo $_POST['cteId']; ?>;
				// var hash = '<?php echo $_POST['hash']; ?>';
				popUp('admin/revisores/areaGeneral.php',{vId:vId,vId:vId,cteId:cteId,hash:hash,noRevisor:1},function(){},{});
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
						e.ele.closest('li').remove();
					}
				})
			});

			$("#validar").click(function(event) {

				conf('Con esta acción declaras que la información contenida '+
					'en esta visita es correcta y será publicada en la página del cliente',{},function(e){
						var rj = jsonF('admin/administracion/revisores/json/json.php',{acc:3,vId:vId,revId:revId});
						// console.log(rj);
						var r = $.parseJSON(rj);
						if(r.ok == 1){
							$('#visita').empty();
							alerta('success','La visita ha sido validada y publicada');
						}
					});
			});

			$("#cancelaVis").click(function(event) {

				var vId = '<?php echo $vId; ?>';

				popUp('admin/administracion/revisores/cancelaVis.php',{vId:vId},function(){},{})
				// conf('Con esta acción declaras que la información contenida '+
				// 	'en esta visita es correcta y será publicada en la página del cliente',{},function(e){
				// 		var rj = jsonF('admin/administracion/revisores/json/json.php',{acc:3,vId:vId,revId:revId});
				// 		// console.log(rj);
				// 		var r = $.parseJSON(rj);
				// 		if(r.ok == 1){
				// 			$('#visita').empty();
				// 			alerta('success','La visita ha sido validada y publicada');
				// 		}
				// 	});
			});

			subArch($('#subeFotografias'),4,'fotografia_<?php echo $vId; ?>_','jpg,png,gif,jpeg',false,function(e){
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
								'<img src="'+rz+'archivos/'+e.prefijo+e.nombreArchivo+'" class="verImg" height="100px"/>'+
							'</div>'+
							'<div class="col-md-2" style="text-align: right;">'+
								'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
									'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
							'</div>'+
						'</div>'
					)
				}

			});
		});
	</script>

		<div>

			<!-- Datos de la visita -->
			<div style="text-align: center;">
				<h2 class="nuevo">Evaluación de cliente</h2>
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
											<?php echo empty($spp['nomResp']) ? $spp['respuesta']:$spp['nomResp']; ?>
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
				if(count($mult)>0){
			?>

			<div class="row">
				<div class="col-md-4">Multimedia</div>
				<div class="col-md-8">
					<div id="multimedia">
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
												<img src="<?php echo aRaizHtml(); ?>archivos/<?php echo $i['archivo'];?>" height="100px"/>
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
			<div style="text-align: center;margin-bottom: 10px;">
				<span class="btn btn-sm btn-shop" id="validar">Aceptar</span>
			</div>
		</div>

