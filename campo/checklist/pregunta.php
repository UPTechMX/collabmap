<?php  

session_start();
include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/checklist.php';
include_once raiz().'lib/php/calcCuest.php';

$uId = $_SESSION['IU']['admin']['usrId'];
// print2($_POST);
$bId = str_replace('a_', '', $_POST['abId']);
$aId = $_POST['aId'];
$pId = $_POST['pId'];
// print2($pId);
if( !isset($_SESSION['IU']['chk'][$_POST['vId']]) ){
	exit;
}

$est = $_SESSION['IU']['chk'][$_POST['vId']]['est'];
$res = $_SESSION['IU']['chk'][$_POST['vId']]['res'];

$preguntas = $est['bloques'][$bId]['areas'][$_POST['aId']]['preguntas'];
// print2($preguntas);
if(empty($pId)){
	$pId = key($preguntas);
	if(  !empty($preguntas[$pId]['subpregs'])  ){
		$pId = key($preguntas[$pId]['subpregs']);
	}else{
		if($res[$pId]['tipo'] == 'sub'){
			$pId = next($preguntas);
		}
	}
}

$direccion = isset($_POST['direccion'])?$_POST['direccion']:'siguiente';
$pId = visible($pId,$res,$est,$direccion);

// print2($res);
// echo $hash;
$p = $res[$pId];
// print2($p);
$hash = encriptaUsr("NL-".$_POST['vId']."-".$uId."-$p[id]-$pId");
// print2($p);
// print2 ($res[$pId]);
$sP = siguiente($pId,$est,$res);
$rA = regArea($bId,$aId,$est);
$rP = regresar($pId,$est,$res);

$respuestas = array();
if($p['tipo'] == 'mult'){
	if(  empty( $_SESSION['IU']['chk'][$_POST['vId']]['respuestas'][$pId] )  ){
		$respuestas = $db->query("SELECT * FROM Respuestas 
			WHERE preguntasId = $p[id] AND (elim IS NULL OR elim != 1)")->fetchAll(PDO::FETCH_ASSOC);

		$_SESSION['IU']['chk'][$_POST['vId']]['respuestas'][$pId] = $respuestas;	
	}else{
		$respuestas = $_SESSION['IU']['chk'][$_POST['vId']]['respuestas'][$pId];
	}
}


?>

<?php if ($pId != null){ ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('#respuesta_<?php echo $pId; ?>').focus();
			$('#justificacion').keyup(function(event) {
				$(this).css({backgroundColor:'rgba(255,255,255,1)'});
			});
			<?php if($p['tipo'] == 'num'){ ?>
				soloNumeros($('#respuesta_<?php echo $pId; ?>'));
				
			<?php } ?>

			var respuestas = <?php echo atj($respuestas); ?>;
			// console.log(respuestas);
			var resps = {};
			for (var resp in respuestas){
				var r = respuestas[resp];
				resps[r['id']] = {};
				resps[r['id']]['justif'] = r['justif'];
				resps[r['id']]['valor'] = r['valor'];
			}

			var paId = '<?php echo $p['area']; ?>';
			var pbId = '<?php echo $p['bloque']; ?>';
			var pabId = 'a_'+pbId;

			$('.areasBloque').hide()
			$('#'+pabId).show();
			$('.bloqueBtn').removeClass('active');
			$('#'+pbId).removeClass('disabled').addClass('active');
			$('.areaBtn').removeClass('active');
			$('#'+paId).removeClass('disabled').addClass('active');


			$('.oblig').keyup(function(event) {
				$(this).prev('.livespell_textarea').css({backgroundColor:'rgba(255,255,255,1)'});
				$(this).css({backgroundColor:'rgba(255,255,255,1)'});
			});
			// console.log(resps);
			<?php if($p['justif'] != 1 && $p['tipo'] == 'mult' ){ ?>
				$('#respuesta_<?php echo $pId; ?>').change(function(event) {
					$(this).css({backgroundColor:'rgba(255,255,255,1)'});
					var respuesta = $(this).val();
					if(respuesta != ''){
						if(resps[respuesta]['justif'] == 1){
							$('#justif').show();
						}else{
							$('#justif').hide();
						}
					}else{
							$('#justif').hide();
					}

				});
				$('#respuesta_<?php echo $pId; ?>').trigger('change');
			<?php } ?>

			// console.log('bbb')
			<?php if( !empty($sP['pId']) ){ ?>
				$('#siguiente').click(function(event) {
					// console.log('aaa');
					var aId = '<?php echo $sP['aId']; ?>';
					var chkId = '<?php echo $_POST['chkId']; ?>';
					var vId = '<?php echo $_POST['vId']; ?>';
					var abId = 'a_<?php echo $sP['bId']; ?>';
					var pId = '<?php echo $sP['pId']; ?>';
					var bId = '<?php echo $sP['bId']; ?>';

					var allOk = camposObligatorios('#datosPreg');
					if(allOk){
						var datos = {};
						datos['visitasId'] = <?php echo $_POST['vId']; ?>;
						datos['preguntasId'] = <?php echo $p['id']; ?>;
						datos['identificador'] = '<?php echo $p['identificador']; ?>';
						datos['respuesta'] = $('#respuesta_<?php echo $pId; ?>').val();
						datos['justificacion'] = $('#justif').is(':visible')?$('#justificacion').val():'';

						var hash = '<?php echo $hash; ?>';
						var pIdAct = '<?php echo $pId; ?>';
						<?php if($p['tipo'] == 'mult' ){ ?>
							var valResp = resps[datos['respuesta']]['valor'];
						<?php }else{ ?>
							var valResp = datos['respuesta'];

						<?php } ?>

						// console.log(datos);
						var rj = jsonF('campo/checklist/json/envResp.php',{datos:datos,hash:hash,pId:pIdAct,valResp:valResp});
						// console.log(rj)
						var r = $.parseJSON(rj);

						if(r.ok == 1){
							// console.log('aa');

							$('#pregunta').load(rz+'campo/checklist/pregunta.php',{
								aId: aId,
								chkId:chkId,
								vId:vId,
								abId:abId,
								pId:pId,
								direccion:'siguiente'
							} ,function(){});
						}

						
					}

				});

			<?php }else{ ?>
				$('#goFinalizar').click(function(event){
					// console.log('bbb');

					var allOk = camposObligatorios('#datosPreg');
					if(allOk){
						var datos = {};
						datos['visitasId'] = <?php echo $_POST['vId']; ?>;
						datos['preguntasId'] = <?php echo $p['id']; ?>;
						datos['identificador'] = '<?php echo $p['identificador']; ?>';
						datos['respuesta'] = $('#respuesta_<?php echo $pId; ?>').val();
						datos['justificacion'] = $('#justif').is(':visible')?$('#justificacion').val():'';

						var hash = '<?php echo $hash; ?>';
						var pIdAct = '<?php echo $pId; ?>';
						<?php if($p['tipo'] == 'mult' ){ ?>
							var valResp = resps[datos['respuesta']]['valor'];
						<?php }else{ ?>
							var valResp = datos['respuesta'];

						<?php } ?>

						// console.log('bbb');
						var rj = jsonF('campo/checklist/json/envResp.php',{datos:datos,hash:hash,pId:pIdAct,valResp:valResp});
						var r = $.parseJSON(rj);

						if(r.ok == 1){
							// console.log('aa');
							$('#finalizar').trigger('click');
							$('#area_archivos').trigger('click');
						}
						
					}

				});
			<?php } ?>



			<?php  if( !empty($rP['pId']) ){ ?>
				$('#regresar').click(function(event) {
					// console.log('ccc')
					var aId = '<?php echo $rP['aId']; ?>';
					var chkId = '<?php echo $_POST['chkId']; ?>';
					var vId = '<?php echo $_POST['vId']; ?>';
					var abId = 'a_<?php echo $rP['bId']; ?>';
					var pId = '<?php echo $rP['pId']; ?>';
					var bId = '<?php echo $rP['bId']; ?>';
					var direccion = 'regresar';

					var allOk = camposObligatorios('#datosPreg');
					if(allOk){
						var datos = {};
						datos['visitasId'] = <?php echo $_POST['vId']; ?>;
						datos['preguntasId'] = <?php echo $p['id']; ?>;
						datos['identificador'] = '<?php echo $p['identificador']; ?>';
						datos['respuesta'] = $('#respuesta_<?php echo $pId; ?>').val();
						datos['justificacion'] = $('#justif').is(':visible')?$('#justificacion').val():'';
						var hash = '<?php echo $hash; ?>';
						var pIdAct= '<?php echo $pId; ?>';
						<?php if($p['tipo'] == 'mult' ){ ?>
							var valResp = resps[datos['respuesta']]['valor'];
						<?php }else{ ?>
							var valResp = datos['respuesta'];
						<?php } ?>

						var rj = jsonF('campo/checklist/json/envResp.php',{datos:datos,hash:hash,pId:pIdAct,valResp:valResp});
						var r = $.parseJSON(rj);

						// if(r.ok == 1){

						// }
						
					}

					$('#pregunta').load(rz+'campo/checklist/pregunta.php',{
						aId: aId,
						chkId:chkId,
						vId:vId,
						abId:abId,
						pId:pId,
						direccion:'regresar'
					} ,function(){});

				});
			<?php }else{ ?>
				$('#regresaGral').click(function(event) {
					$('#general').trigger('click');
					$('#area_general').trigger('click');
				});
			<?php } ?>

				

		});
	</script>
<?php }elseif($direccion == 'siguiente'){ ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('#finalizar').trigger('click');
			$('#area_archivos').trigger('click');

		});
	</script>
<?php }else{ ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#general').trigger('click');
			$('#area_general').trigger('click');
		});
	</script>
<?php } ?>

<div style="min-height: 350px;">
	<?php if (!empty($p['subarea'])){ ?>
		<div class="nomSubArea">
			<?php echo $res[$p['subarea']]['pregunta']; ?>	
		</div>
	<?php } ?>
	<!-- Pregunta -->
	<div class="pregChk">
		<?php echo $p['pregunta']; ?>
	</div>
	
	<form id="datosPreg">

		<!-- Respuesta -->
		<div class="resp" style="width: 80%;margin-left: auto;margin-right: auto;">
			<?php if ($p['tipo'] == 'mult'){ ?>
				<select id="respuesta_<?php echo $pId; ?>" name="respuesta" class="form-control oblig" style="height: 50px;">
					<option value="">- - - Selecciona una respuesta - - -</option>
					<?php foreach ($respuestas as $r){ ?>
						<option value="<?php echo $r['id']; ?>" <?php echo $res[$pId]['respuesta'] == $r['id']?'selected':''; ?> >
							<?php echo $r['respuesta']; ?>
						</option>
					<?php } ?>
				</select>
			<?php }elseif($p['tipo'] == 'ab'){ ?>
				<!-- <div style="text-align: right;margin-bottom:5px;" >
					<span class="btn btn-sm btn-shop" id="revisarResp">Revisar ortografía</span>
				</div> -->
				<textarea class="form-control oblig" id="respuesta_<?php echo $pId; ?>" spellcheck="true" lang="es" 
					style="display: block;width: 100%;height: 50px;padding: 6px 12px;font-size: 14px;line-height: 
						1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px 
						solid #ccc;border-radius: 4px;"><?php echo $p['respuesta']; ?></textarea>			
			<?php }else{ ?>
				<input type="text" id="respuesta_<?php echo $pId; ?>" name="respuesta" class="form-control oblig" 
					style="height: 50px;" value="<?php echo $res[$pId]['respuesta']; ?>">
			<?php } ?>
		</div>

		<?php if (!empty($p['comShopper'])){ ?>
			<div style="width: 80%;margin-left: auto;margin-right: auto;margin-top: 5px;">
				<strong style="color:grey;">Comentarios a considerar:<br/></strong>
			</div>
			<div id="comentario" style="width: 80%;margin-left: auto;margin-right: auto; 
				background-color: whitesmoke; border-radius: 5px;padding: 5px;
				border:solid 1px lightgrey; margin-top: 5px;">
				 
				<?php echo $p['comShopper']; ?>
			</div>
		<?php } ?>
		<div id="comentario"></div>

		<!-- Justificacion -->
		<?php $justificar = $p['justif'] == 1?'':'display:none;'; ?>
		<div class="justif" style="<?php echo $justificar; ?>" id="justif">
			<span>Justificación</span>
			
			<textarea hh="justif" class="form-control oblig" id="justificacion" spellcheck="true" lang="es" 
				style="display: block;width: 100%;height: 100px;padding: 6px 12px;font-size: 14px;line-height: 
					1.42857143;color: #555;background-color: #fff;background-image: none; resize: vertical;
					border: 1px solid #ccc;border-radius: 4px;"><?php echo $p['justificacion']; ?></textarea>
		</div>
	</form>
</div>
<div style="text-align:center;width: 96%;margin-top: 5px;">
	<?php if( !empty($rP['pId']) ){ ?>
	<span id="regresar" class="btn btn-sm btn-shop">< Regresar</span>	
	<?php }else{ ?>
	<span id="regresaGral" class="btn btn-sm btn-shop">< Regresar</span>	
	<?php } ?>
	<?php if( !empty($sP['pId']) ){ ?>
	<span id="siguiente" class="btn btn-sm btn-shop">Siguiente ></span>
	<?php }else{ ?>
	<span id="goFinalizar" class="btn btn-sm btn-shop">Siguiente ></span>
	<?php } ?>
</div>
