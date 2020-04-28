<?php 
	session_start();
	$root = $_SESSION['CM']['raiz'];

	include_once $root.'lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';

	// print2($_POST);

	$vId = isset($_POST['vId'])?$_POST['vId']:14112;//4536;//6252;

	$chk = new Checklist($vId);
	// echo "VISITA: $vId<br/>";
	$vInfo = $chk->getVisita();

	if ($vInfo['etapa'] == 'instalacion') {
		$componentes = $db->query("SELECT cc.dimensionesElemId,cc.* FROM ClientesComponentes cc
			WHERE clientesId = $vInfo[clientesId]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	}

	$chkId = $chk->id;
	$chkInf = $chk->getGeneral();

	$estructura = $chk -> getEstructura();
	$pregs = $chk -> getResultados($vId);//resultados($vId,$estructura);
	$c = calculos($pregs,$estructura);


	// print2($pregs);
	// echo "VALRESP:".valResp($pregs,"p_23_255_179_1821")."<br/>";
	
	// $sql = "SELECT c.id as cId, c.nombre as cNom, m.nombre as mNom, t.nombre as tNom, v.resumen as vRes, 
	// 	v.hora as horaIni, v.horaSalida as horaFin, v.fecha, 
	// 	CONCAT(COALESCE(t.calle,''),' ',COALESCE(t.numExt,''),' ',COALESCE(t.numInt,'')) as direccion,
	// 	s.nombre as sNom, t.id as tId, t.POS as POS, edo.nombre as edo
	// 	FROM Visitas v 
	// 	WHERE v.id = $vId";

	$sql = "SELECT v.*
				FROM Visitas v
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

		// audiojs.events.ready(function() {
		//   var as = audiojs.createAll();
		// });

		$('.verImg').click(function(event) {
			verImagen($(this).attr('src'));
		});

		var pregs = <?php echo atj($pregs); ?>;
		// console.log(pregs);

		$('.pClick').click(function(event) {
			var pId = this.id;
			var preg = pregs[pId];
			var chkInf = <?php echo atj($chkInf); ?>;
			var vInf = <?php echo atj($vInfo); ?>;
			console.log(pId,preg,chkInf,vInf);

			$('#pregunta').load(rz+'checklist/pregunta.php',{
				pId: pId,
				aId: preg['aId'],
				chkId:chkInf['id'],
				vId:vInf['id'],
				abId:'a_'+preg['bloque'],
				direccion:'regresar'
			} ,function(){});
		});



	});
</script>

	<?php if ($_POST['div'] != 1){ ?>
		

<body style="background-color: #FFF;">
	<div class="container" >
		<div class="header" id="header"><?php include raiz().'reportes/layout/header.php'; ?></div>
	<?php } ?>
		<div>

			<!-- Datos de la visita -->
			<div style="text-align: center;margin-top: 10px;" class="nuevo">
				<h2><?php echo TR('questionnairePreview'); ?></h2>
			</div>

			<!-- <table class="table" style="font-size:x-small;">
				<thead>
					<tr>
						<th>Cliente</th>
						<th>Dirección</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					<tr>
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
			</table> -->

			<!-- <table class="table" style="font-size:x-small;">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Hora de ingreso</th>
						<th>Hora de salida</th>
						<th>Shopper</th>
						<?php if ($chkInf['tipoReembolso'] >= 1){ ?>
							<th>Cantidad pagada</th>
						<?php } ?>
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
					</tr>
				</tbody>
			</table> -->

			<!-- Encabezado -->
<!-- 			<div class="nuevo">Datos generales</div>

			<?php if ($vInfo['etapa'] == 'instalacion'){ ?>
				El usuario autoriza el uso de su imágen para fines de difusión y promoción del proyecto :  
				<span><?php echo $vInfo['permiso']==1?'SÍ':'NO' ?></span>
			<?php } ?>

			<table class="table" style="font-size:x-small;">
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
 -->
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
																			<img src="../chkPhotos/<?php echo $img['archivo'];?>" 
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
									case 'audio':
										echo '<div>';
											echo '<div class="nuevo">Audios</div>';
											echo '<ul class="list-group">';
												foreach ($archs as $a) {
													$archivo = $_POST['div'] != 1 ? "../../archivos/$a[archivo]":"../archivos/$a[archivo]";
													echo "<li class='list-group-item ' style='border:none;' >";
													echo "<table>";
														echo "<tr>";
														echo "<td>";
															echo "<audio src='$archivo' preload='auto' style='display:inline;'/>";
														echo "</td>";
														echo "<td style='font-size:2em;'>";
															echo "<a href='$archivo' target='_blank'>";
															echo "&nbsp;<i class='glyphicon glyphicon-download-alt manita'></i>";
															echo "</a>";
														echo "</td>";
														echo "</tr>";
													echo "</table>";
													echo "</li>";
												}
											echo '<ul>';
										echo '</div>';

										break;
									case 'video':
										echo '<div>';
											echo '<div class="nuevo">Videos</div>';
											echo '<ul class="list-group">';
												foreach ($archs as $a) {
													$archivo = $_POST['div'] != 1 ? "../../archivos/$a[archivo]":"../archivos/$a[archivo]";
													echo "<li class='list-group-item ' style='border:none;' >";
													echo "<table>";
														echo "<tr>";
														echo "<td>";
															echo "<video class='video-js' controls preload='auto' 
																width='320' height='132' data-setup='{}'>";
															  echo "<source src='../archivos/$a[archivo]' >";
															echo "</video>";
														echo "</td>";
														echo "<td style='font-size:2em;'>";
															echo "<a href='$archivo' target='_blank'>";
															echo "&nbsp;<i class='glyphicon glyphicon-download-alt manita'></i>";
															echo "</a>";
														echo "</td>";
														echo "</tr>";
													echo "</table>";
													echo "</li>";
												}
											echo '<ul>';
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
		<?php if ($vis['resumen'] != ''){ ?>
			<div>
				<div class="nuevo bloque negro">
					Resumen
				</div>
				<div style="text-align: justify;">
					<?php echo $vis['resumen']; ?>
				</div>
			</div>
			<br/>

		<?php } ?>
		<!-- Tabla de preguntas -->

		<div>
			<table class="table" style="font-size:x-small;">
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
								<td style="background-color: white; color:white;"></td>
								<td class="negro" style="text-align: center;"><?php echo TR('answer'); ?></td>
								<td class="negro area"><?php echo TR('justification'); ?></td>
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
															<span class="manita pClick" id="<?php echo $spp['identificador']; ?>">
																<?php echo $spp['pregunta']; ?>
															</span>															
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
																<span class="manita pClick" id="<?php echo $spp['identificador']; ?>">
																	<?php echo $spp['pregunta']; ?>
																</span>															
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
											<td colspan="3">
												<span class="manita pClick" id="<?php echo $pp['identificador']; ?>">
													<?php echo $pp['pregunta']; ?>
												</span>
											</td>
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
<?php if ($_POST['div'] != 1){ ?>
	</div>
</body>
</html>

<?php } ?>

