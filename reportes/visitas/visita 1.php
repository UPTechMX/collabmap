<?php  

	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';



	$vId = isset($_POST['vId'])?$_POST['vId']:5054;//4536;//6252;

	$chkId = getChkId($vId);
	$estructura = estructura($chkId);
	$pregs = resultados($vId,$estructura);
	$c = calculos($pregs,$estructura);

	// print2($estructura);
	// echo "VALRESP:".valResp($pregs,"p_23_255_179_1821")."<br/>";
	
	$sql = "SELECT c.id as cId, c.nombre as cNom, m.nombre as mNom, t.nombre as tNom, v.resumen as vRes, 
		v.hora as horaIni, v.horaSalida as horaFin, v.fecha, 
		CONCAT(COALESCE(t.calle,''),' ',COALESCE(t.numExt,''),' ',COALESCE(t.numInt,'')) as direccion,
		s.nombre as sNom, t.id as tId, t.POS as POS, edo.nombre as edo, rep.nombre as repNom, c.pdf
		FROM Visitas v 
		LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
		LEFT JOIN Repeticiones rep ON rot.repeticionesId = rep.id
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		LEFT JOIN Marcas m ON m.id = t.marcasId
		LEFT JOIN Clientes c ON c.id = m.clientesId
		LEFT JOIN Shoppers s ON s.id = v.shoppersId
		LEFT JOIN Estados edo ON edo.id = t.estado
		WHERE v.id = $vId";
	// echo $sql;
	$vis = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

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
		<link href="http://sistema.shoppersconsulting.com/lib/js/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="http://sistema.shoppersconsulting.com/lib/j/j.css" rel="stylesheet" type="text/css" />
		<link href="http://sistema.shoppersconsulting.com/lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
		<link href="http://sistema.shoppersconsulting.com/lib/css/general.css" rel="stylesheet" type="text/css" />
		<link href="http://sistema.shoppersconsulting.com/lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />
		
		<!-- LIBRERIAS JAVASCRIPT -->
		<script src="http://sistema.shoppersconsulting.com/lib/js/jquery-3.1.1.min.js"></script>
		<script src="http://sistema.shoppersconsulting.com/lib/js/jqueryUI/jquery-ui.js"></script>
		<script src="http://sistema.shoppersconsulting.com/lib/js/bootstrap/js/bootstrap.min.js"></script>
		<script src="http://sistema.shoppersconsulting.com/lib/js/audiojs/audiojs/audio.min.js"></script>
		
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-more.js"></script>
		<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/data.js"></script>
		<script src="https://code.highcharts.com/modules/drilldown.js"></script>


		<script src="http://sistema.shoppersconsulting.com/lib/js/graficas.js"></script>
		<script src="http://sistema.shoppersconsulting.com/lib/j/j.js"></script>
	

	</head>
<?php } ?>


	<script>
		$(document).ready(function() {
			var div = <?php echo $_POST['div'] == 1?1:0; ?>;
			var calif = parseFloat(<?php echo $c['chk']['prom']*100; ?>).toFixed(2);
			calif = parseFloat(calif);
			grafCalifFinal($('#grCalif'),calif);


			$('.verImg').click(function(event) {
				event.preventDefault();
				var imgId = this.id.split('_')[1];
				// console.log(imgId);
				popUp('reportes/visitas/imgVer.php',{imgId:imgId,div:div},function(e){},{});
			});


			audiojs.events.ready(function() {
			  var as = audiojs.createAll();
			});


		});
	</script>


	<?php if ($vis['pdf'] == 1){ ?>
		
		<script>
			var nomArch = '<?php echo "$vis[repNom] - $vis[POS]";  ?>';
			var downloadPDF = function() {
				var width = 0;
				$.each($('.grInf'), function(index, val) {
					// console.log(this.id);
					var chart = $(this).highcharts();
					width = $(this).width();
					// console.log(width);
					chart.setSize(width/2);
					// chart.redraw();
					// console.log(chart);
				});

				var widthCalif = $('#grCalif').width();
				var chartCalif = $('#grCalif').highcharts();
				chartCalif.setSize(widthCalif/2);
				// $('#tablaGral').css({width:'300px'})
				// $('#tablaGral').css({width:'300px'})

				setTimeout(function(){ 
					DocRaptor.createAndDownloadDoc("xiyCfGbxZlcK3VFCriu", {
						test: false, // test documents are free, but watermarked
						type: "pdf",
						name:nomArch+'.pdf',
						// document_content: document.querySelector('html').innerHTML, // use this page's HTML
						document_content: $('#contTodo').html(),               // or supply HTML directly
						// document_url: "http://sistema.shoppersconsulting.com/reportes/visitas/visita.php",            // or use a URL
						// javascript: true,                                        // enable JavaScript processing
						prince_options: {
							media: "screen",                                       // use screen styles instead of print styles
						}
					})
					$.each($('.grInf'), function(index, val) {
						var chart = $(this).highcharts();
						chart.setSize(width);
					});
					chartCalif.setSize(widthCalif);


				}, 1000);

				setTimeout(function(){ 
				}, 10);


			}
		</script>
	<?php }else{ ?>
		<script type="text/javascript">
			$(document).ready(function() {

				$('#dwlVis').click(function(event) {
					var quotes = document.getElementById('contTodo');


					html2canvas(quotes, {
						onrendered: function(canvas) {

							var pdf = new jsPDF('p', 'pt', 'a4');
								var hImgPage = 1580;
							 for (var i = 0; i <= quotes.clientHeight/hImgPage; i++) {
								 //! This is all just html2canvas stuff
								 var srcImg  = canvas;
								 var sX      = 0;
								 var sY      = hImgPage*i; // start 980 pixels down for every new page
								 var sWidth  = 1200;
								 var sHeight = hImgPage;
								 var dX      = 30;
								 var dY      = 10;
								 var dWidth  = 800;
								 var dHeight = 1110;

								 window.onePageCanvas = document.createElement("canvas");
								 onePageCanvas.setAttribute('width', 900);
								 onePageCanvas.setAttribute('height', 1120);
								 var ctx = onePageCanvas.getContext('2d');
								 // details on this usage of this function: 
								 // https://developer.mozilla.org/en-US/docs/Web/API/Canvas_API/Tutorial/Using_images#Slicing
								 ctx.drawImage(srcImg,sX,sY,sWidth,sHeight,dX,dY,dWidth,dHeight);

								 // document.body.appendChild(canvas);
								 var canvasDataURL = onePageCanvas.toDataURL("image/png", 1.0);

								 var width         = onePageCanvas.width;
								 var height        = onePageCanvas.clientHeight;

								 //! If we're on anything other than the first page,
								 // add another page
								 if (i > 0) {
									 pdf.addPage(595, 842); //8.5" x 11" in pts (in*72)
								 }
								 //! now we declare that we're working on that page
								 pdf.setPage(i+1);
								 //! now we add content to that page!
								 pdf.addImage(canvasDataURL, 'PNG', 0, 0, (width*.72), (height*.71));

							 }
							 var nomArch = '<?php echo "$vis[repNom] - $vis[POS]";  ?>';

							 //! after the for loop is finished running, we save the pdf.
							 pdf.save(nomArch+'.pdf');



						}
					});

				});

			});
		</script>

	<?php } ?>

	<div style="text-align: right;margin-top: 5px;">
		<?php if ($vis['pdf'] == 1){ ?>
			<input id="pdf-button" type="button" class="btn btn-sm btn-shop" value="Descargar PDF" onclick="downloadPDF()" />
		<?php }else{ ?>
			<span class="btn btn-sm btn-shop" id="dwlVis">Descargar PDF</span>
		<?php } ?>
	</div>
	<?php if ($_POST['div'] != 1){ ?>
		

<body style="background-color: #FFF;">
	<div class="container" >
		<div class="header" id="header">
			<div style="text-align:center;margin-top: 20px;" class="hidden-xs">
				<img src="<?php echo aRaiz(); ?>img/marquesina.png" width="100%" id="Insert_logo" style=" margin-left:auto;margin-right:auto;" usemap="#logosMap" />
			</div>
		</div>
	<?php } ?>
		<div id="contTodo">

			<link href="http://sistema.shoppersconsulting.com/lib/js/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
			<link href="http://sistema.shoppersconsulting.com/lib/j/j.css" rel="stylesheet" type="text/css" />
			<link href="http://sistema.shoppersconsulting.com/lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
			<link href="http://sistema.shoppersconsulting.com/lib/css/general.css" rel="stylesheet" type="text/css" />
			<link href="http://sistema.shoppersconsulting.com/lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />

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
								<table>
									<?php for($i = count($dims)/2; $i>0;$i--){ ?>
										<tr>
											<td style="text-align: right;"><strong><?php echo $dims["d".($i-1)]; ?></strong>:</td>
											<td>&nbsp;</td>
											<td><?php echo $dims["de".($i-1)]; ?></td>
										</tr>	
									<?php } ?>
								</table>
							</td>
						</tr>
					</tbody>
				</table>


				<!-- Tabla general -->
				<table class="table" id="tablaGral">
					<thead>				
						<tr>
							<th style="background-color: black;color: white;" width="20%">Calificación</th>
							<th style="background-color: #9fbb59;color: black;" colspan="4" width="80%">Resumen</th>
						</tr>
					</thead>
					<?php if ($estructura['tipoAnalisis'] == 1){ ?>
						<tbody>
							<?php
								$i = 1;
								foreach ($c['bloques'] as $b) {
									if($b['prom'] != '-'){
										$i++;
										foreach ($b['areas'] as $a) {
											if($a['prom'] !== '-'){
												$i++;
											}
										}
									}
								}
							?>
							<tr class="trArea" height="30px">
								<td rowspan="<?php echo $i; ?>" width="20%">
									<div id="grCalif" style=""></div>
								</td>
								<th class="tdArea" width="20%">Área</th>
								<th class="tdArea" width="10%">Puntos obtenidos</th>
								<th class="tdArea" width="10%">Calificación</th>
								<th style="text-align: center;vertical-align: middle;" >Gráfica de resultados por área</th>
							</tr>
							<?php 
							foreach ($c['bloques'] as $bId => $b){ 
								$numAreas = 0;
								foreach ($b['areas'] as $a) {
									if($a['prom'] !== '-'){
										$numAreas++;
									}
								}
								if($b['prom'] != "-"){ ?>
									<?php 
									$j = 0;
									$serie = array();
									foreach ($b['areas'] as $a){
										if($a['prom'] !== '-'){
											$tmp['name'] = $a['nombre'];
											$tmp['y'] = number_format($a['prom']*100,2);
											if($a['prom']*100<50){
												$tmp['color'] = '#f00';
											}else{
												$tmp['color'] = 'green';
											}
											$serie[] = $tmp;
									?>
										<tr class="trInf">
											<td><?php echo $a['nombre']; ?></td>
											<td><?php echo "$a[pregPos]/$a[pregTot]"; ?></td>
											<td><?php echo number_format($a['prom']*100,2); ?></td>
											<?php if($j == 0){?>
												<td  rowspan="<?php echo $numAreas;?>">
													<div id="gr_<?php echo $bId; ?>" style="height: 200px;" class="grInf"></div>
												</td>
											<?php $j++; } ?>

										</tr>

									<?php
										}
									?>
									<?php } ?>
									<tr  class="trTotBloque">
										<td class="tdTotBloque">Bloque: <?php echo $b['nombre']; ?></td>
										<td class="tdTotBloque"><?php echo "$b[pregPos]/$b[pregTot]"; ?></td>
										<td class="tdTotBloque"><?php echo number_format($b['prom']*100,2) ?></td>
										<td></td>
									</tr>
									<script type="text/javascript">
										$(document).ready(function() {
											var serie = <?php echo atj($serie);?>;
											// console.log(serie);
											parseaObjeto(serie);
											var rows = <?php echo $numAreas; ?>;
											// console.log(rows);
											$('#gr_<?php echo $bId;?>').css({height: parseInt(rows)*50});
											barras('#gr_<?php echo $bId;?>',serie,false);
										});
									</script>
								<?php } ?>
							<?php
							} 
							?>
							<tr class="trTotChk">
								<td class="tdTotChk"></td>
								<td class="tdTotChk">Calificación final</td>
								<td class="tdTotChk"><?php echo is_numeric($c['chk']['prom'])?$c['chk']['pregPos']."/".$c['chk']["pregTot"]:'-'; ?></td>
								<td class="tdTotChk"><?php echo is_numeric($c['chk']['prom'])?number_format($c['chk']['prom']*100,2):'-'; ?></td>
								<td class="tdTotChk"></td>
							</tr>
						</tbody>
					<?php }else{ ?>
						<tbody>

							<?php 
								$i = 1;
								foreach ($c['bloques'] as $b) {
									if($b['prom'] !== '-'){
										$i++;
										// echo "$b[nombre] = $b[prom]<br/>";
									}
								}
							?>
							<tr class="trArea" height="30px">
								<td rowspan="<?php echo $i; ?>" width="20%">
									<div id="grCalif" style=""></div>
								</td>
								<th class="tdArea" width="20%">Bloque</th>
								<th class="tdArea" width="10%">Puntos obtenidos</th>
								<th class="tdArea" width="10%">Calificación</th>
								<th style="text-align: center;vertical-align: middle;" >Gráfica de resultados por bloque</th>
							</tr>

							<?php 
								$serie = array();
								foreach ($c['bloques'] as $bId => $b){
									if($b['prom'] === '-'){
										continue;
									}else{
										$tmp['name'] = $b['nombre'];
										$tmp['y'] = number_format($b['prom']*100,2);
										if($b['prom']*100<50){
											$tmp['color'] = '#f00';
										}else{
											$tmp['color'] = 'green';
										}
										$serie[] = $tmp;
									}

							?>

							<tr class="trInf">
								<td><?php echo $b['nombre']; ?></td>
								<td><?php echo "$b[pregPos]/$b[pregTot]"; ?></td>
								<td><?php echo number_format($b['prom']*100,2); ?></td>
								<?php if($j == 0){?>
									<td  rowspan="<?php echo $i-1; ?>">
										<div id="grB" style="height: 200px;" class="grInf"></div>
									</td>
								<?php $j++; } ?>

							</tr>
							<?php } ?>

							<script type="text/javascript">
								$(document).ready(function() {
									var serie = <?php echo atj($serie);?>;
									parseaObjeto(serie);
									var rows = <?php echo $i; ?>;
									// console.log(rows);
									$('#grB').css({height: parseInt(rows)*50});
									barras('#grB',serie,false);
								});
							</script>
							<tr class="trTotChk">
								<td class="tdTotChk"></td>
								<td class="tdTotChk">Calificación final</td>
								<td class="tdTotChk"><?php echo is_numeric($c['chk']['prom'])?$c['chk']['pregPos']."/".$c['chk']["pregTot"]:'-'; ?></td>
								<td class="tdTotChk"><?php echo is_numeric($c['chk']['prom'])?number_format($c['chk']['prom']*100,2):'-'; ?></td>
								<td class="tdTotChk"></td>
							</tr>
						</tbody>

					<?php } ?>
				</table>

				<!-- Encabezado -->
				<div class="nuevo">
					Datos generales de la visita
				</div>

				<table class="table">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Hora de ingreso</th>
							<th>Hora de salida</th>
							<th>Shopper</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $vis['fecha']; ?></td>
							<td><?php echo $vis['horaIni']; ?></td>
							<td><?php echo $vis['horaFin']; ?></td>
							<td><?php echo $vis['sNom']; ?></td>
						</tr>
					</tbody>
				</table>

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
												echo '<ul class="list-group">';
													foreach ($archs as $a) {
														echo "<li class='list-group-item manita verImg' id='verImg_$a[id]'>$a[nombre]</li>";
													}
												echo '<ul>';
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
									<td style="background-color: #666;color:white;text-align: center;"><?php echo "$a[pregPos]/$a[pregTot]"; ?></td>
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
																<?php echo $spp['pregunta'];?>
																
															</td>
															<td class="<?php echo $clasePuntos ?>">
																<i class="<?php echo $claseIcono ?>"></i>
																<?php echo $clasePuntos != 'NA' ? number_format($spp['valPreg']*$pond,2):''; ?>

															</td>
															<td style="text-align: center;"><?php echo $spp['nomResp']; ?></td>
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
																	<?php echo $clasePuntos != 'NA' ? number_format($spp['valPreg']*$pond,2):''; ?>
																</td>
																<td style="text-align: center;"><?php echo $spp['nomResp']; ?></td>
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
												<td colspan="3"><?php echo $pp['pregunta']; ?></td>
												<td class="<?php echo $clasePuntos ?>">
													<i class="<?php echo $claseIcono ?>"></i>
													<?php echo $clasePuntos != 'NA' ? number_format($pp['valPreg']*$pond,2):''; ?>
												</td>

												<td style="text-align: center;"><?php echo $pp['nomResp']; ?></td>
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

