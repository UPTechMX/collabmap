<?php  

	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';

	checaAcceso(46);

	$vId = isset($_POST['vId'])?$_POST['vId']:5;//4536;//6252;

	$chk = new Checklist($vId);
	// echo "VISITA: $vId<br/>";
	$vInfo = $chk->getVisita();

	if ($vInfo['etapa'] == 'instalacion') {
		$componentes = $db->query("SELECT cc.dimensionesElemId,cc.* FROM ClientesComponentes cc
			WHERE clientesId = $vInfo[clientesId]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	}


	$chkId = $chk->id;
	$chkInf = $chk->getGeneral();

	$estructura = $chk->getEstructura();
	$pregs = resultados($vId,$estructura);
	$c = calculos($pregs,$estructura);

	// print2($estructura);
	// echo "VALRESP:".valResp($pregs,"p_23_255_179_1821")."<br/>";
	
	// $sql = "SELECT c.id as cId, c.nombre as cNom, m.nombre as mNom, t.nombre as tNom, v.resumen as vRes, 
	// 	v.hora as horaIni, v.horaSalida as horaFin, v.fecha, 
	// 	CONCAT(COALESCE(t.calle,''),' ',COALESCE(t.numExt,''),' ',COALESCE(t.numInt,'')) as direccion,
	// 	s.nombre as sNom, t.id as tId, t.POS as POS, edo.nombre as edo
	// 	FROM Visitas v 
	// 	WHERE v.id = $vId";

	$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre, v.fecha, v.resumen as vRes,c.instalacionRealizada, c.instalacionSug,
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

	$ponderacion = $c['chk']['max'] != 0?100/$c['chk']['max']:0;

	if($vis['etapa'] == 'instalacion'){
		$equipo = $db->query("SELECT ei.nombre as eNom, 
			CONCAT(IFNULL(u.nombre,''),' ',IFNULL(u.aPat,''),' ',IFNULL(u.aMat,'')) as instNom,
			v.nombre as vNom
			FROM EquiposInstalacion ei
			LEFT JOIN usrAdmin u ON u.id = ei.instalador
			LEFT JOIN Vehiculos v ON v.id = ei.vehiculo
			WHERE ei.id = $vis[equipo]")->fetchAll(PDO::FETCH_ASSOC)[0];

		$personal = $db->query("SELECT ep.nivel, 
			CONCAT(IFNULL(u.nombre,''),' ',IFNULL(u.aPat,''),' ',IFNULL(u.aMat,'')) as uNom
			FROM EquiposPersonal ep 
			LEFT JOIN usrAdmin u ON u.id = ep.usuariosId
			WHERE equiposId = $vis[equipo]
			ORDER BY nivel
			")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

		$personal[42] = empty($personal[42])?array():$personal[42];
		$personal[44] = empty($personal[44])?array():$personal[44];

	}
	// echo "Cuentas: ".cuenta($pregs,"p_23_255_179_1820")."<br/>";
	// print2($c);
	// print2($vis);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.verImg').click(function(event) {
			console.log('aaq');
			verImagen($(this).attr('src'));
		});

		$('#edtResp').click(function(){
			$('#vIdEdt').submit();
		});
		
	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Respuestas del cuestionario</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>

	<div>
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
		<div class="nuevo">Datos generales</div>
		<table class="table" style="font-size:small;">
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
									<?php echo empty($pp['nomResp'])?$pp['respuesta']:$pp['nomResp']; ?>
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
					<?php
						foreach ($mult as $tipo => $archs) {
							switch ($tipo) {
								case 'img':
									echo '<div>';
										echo '<div class="nuevo">Imágenes</div>';
										if($vis['etapa'] == 'instalacion'){ ?>
											<table>
												<?php foreach ($fotosInst as $id => $nombre){ ?>
													<?php 
														$sql = "SELECT * 
														FROM Multimedia 
														WHERE archivo LIKE 'fotografia_".$vId."_$id"."_%' ";

														$img = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
													?>
													<tr>
														<td><?php echo $nombre; ?></td>

														<td id="img_<?php echo $id; ?>" class="imgCont" >
															<div class="imgEle" id="imgEle_<?php echo $i['id'];?>">							
																<div class="row">
																	<div class="col-md-10" id="imgNom_<?php echo $img['id'];?>">
																		<img src="../campo/archivosCuest/<?php echo $img['archivo'];?>" 
																			height="100px" class="verImg" />
																	</div>
																</div>
															</div>

														</td>
													</tr>

												<?php } ?>
											</table>
										<?php }else{
											echo '<ul class="list-group">';
											foreach ($archs as $a) {
												echo "<div style='margin:5px;'><img class='verImg' src='../campo/archivosCuest/$a[archivo]' 
													style='max-height:100px;max-width:100px;'/></div>";
												// echo "<li class='list-group-item manita verImg' id='verImg_$a[id]'>$a[/nombre]</li>";
											}
											echo '<ul>';
										}
									echo '</div>';

									break;
								
								default:
									# code...
									break;
							}
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
			<div class="nuevo negro">
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
		<table class="table" style="font-size:small;">
			<?php 
				$bI = 0;
				$faltaPreg = null;
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
							<td style="background-color: #00aeef; color:white;"></td>
							<td class="negro" style="text-align: center;">Respuestas</td>
							<td class="negro area">Observaciones</td>
						</tr>
						<!-- <tr>
							<td style="background-color: #666;color:white;">
								
							</td>
							<td style="background-color: #666;color:white;"></td>
							<td style="background-color: #666;color:white;text-align: center;"><?php echo "$a[pregPos]/$a[pregTot]"; ?></td>
							<td style="background-color: #666;color:white;"></td>
						</tr> -->
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
										<td rowspan="<?php echo $ii; ?>" class="subArea"><?php echo $pp['pregunta'];?></td>
										<?php
											$isp = 0;
											foreach ($p['subpregs'] as $sp){ 
												$spp = $pregs[$sp['identificador']];
												$pond = $c['bloques'][$spp['bloque']]['areas'][$spp['area']]['pond'];
										?>
											<?php 
												if ($spp['muestra'] == 1){
													if($spp['influyeValor'] == 0 || $spp['valPreg'] === '-'){
														$clasePuntos = '';// NA
														// $claseIcono = '';
													}else{
														if($spp['valPreg']>0){
															$clasePuntos = '';
															// $claseIcono = 'glyphicon glyphicon-ok-sign verde';
														}else{
															$clasePuntos = '';// mala
															// $claseIcono = 'glyphicon glyphicon-remove-sign rojo';
														}

													}
											?>
												<?php if ($isp == 0){$isp++; ?>
													<td colspan="2">
														<?php echo $spp['pregunta'];?>
														
													</td>
													<td class="<?php echo $clasePuntos ?>">
														<i class="<?php echo $claseIcono ?>"></i>
														<?php 
															//echo $clasePuntos != 'NA' ? number_format($spp['valPreg']*$pond,2):''; 
														?>

													</td>
													<td style="text-align: center;">
														<?php 
															$nomResp = $spp['nomResp'];
															echo $nomResp;
															if($nomResp == "" && $faltaPreg == null){
																$faltaPreg = $spp['identificador'];
																// echo "<br/>VACIA!!";
															}
														?>
																
													</td>
													<td><?php echo $spp['justificacion']; ?></td>
												<?php }else{?>
													<tr>
														<td class="negro"><?php echo $pI++ ?></td>
														<td colspan="2">
															<?php echo $spp['pregunta'];?>
														</td>
														<td class="<?php echo $clasePuntos ?>">
															<i class="<?php echo $claseIcono ?>">
															</i>
															<?php 
																// echo $clasePuntos != 'NA' ? number_format($spp['valPreg']*$pond,2):''; 
															?>
														</td>
														<td style="text-align: center;">
															<?php 
																$nomResp = $spp['nomResp'];
																echo $nomResp;
																if($nomResp == "" && $faltaPreg == null){
																	$faltaPreg = $spp['identificador'];
																}
															?>
															
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
											$clasePuntos = '';// NA
											// $claseIcono = '';
										}else{
											if($pp['valPreg']>0){
												$clasePuntos = '';
												// $claseIcono = 'glyphicon glyphicon-ok-sign verde';
											}else{
												$clasePuntos = '';// mala
												// $claseIcono = 'glyphicon glyphicon-remove-sign rojo';
											}
										}
								?>
									<tr>
										<td class="negro"><?php echo $pI++ ?></td>
										<td colspan="3"><?php echo $pp['pregunta'];  //print2($pp);?></td>
										<td class="<?php echo $clasePuntos ?>">
											<i class="<?php echo $claseIcono ?>"></i>
											<?php 
												// echo $clasePuntos != 'NA' ? number_format($pp['valPreg']*$pond,2):''; 
											?>
										</td>

										<td style="text-align: center;">
											<?php 
												$nomResp = $pp['nomResp'];
												echo $nomResp;
												if($nomResp == "" && $faltaPreg == null){
													$faltaPreg = $pp['identificador'];
												}
											?>		
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
	</div>
	<?php if ($vInfo['etapa'] == 'instalacion'){ ?>
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
				<div class="nuevo">Instalacion</div>
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
	<?php if ($vInfo['etapa'] == 'visita'){ ?>
		<?php $vis['instalacionSug'] = empty($vis['instalacionSug'])?0:$vis['instalacionSug']; ?>
		<?php $datI = $db->query("SELECT * FROM Instalaciones WHERE id = $vis[instalacionSug]")->fetchAll(PDO::FETCH_ASSOC)[0]; ?>
		<div class="nuevo">Instalacion</div>
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

	
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<?php
		$nivelTmp = $_SESSION['IU']['admin']['nivel'];
		// echo "nivel: ".$nivelTmp;
		if($nivelTmp>=49){
			?>
			<span id="edtResp" data-dismiss="modal" class="btn btn-sm btn-shop">Editar respuestas</span>
			<?php 
		}
		?>
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
	</div>
</div>

<form method="post" target="_blank" id="vIdEdt" action="proyectos/revisionCont.php" >
	<input type="hidden" name="vId" value="<?php echo $vId; ?>"  />
	<input type="hidden" name="hash" value="<?php echo encriptaUsr("$_POST[vId]_NLIU"); ?>" />
</form>

