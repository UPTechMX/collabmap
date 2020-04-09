<?php  

	session_start();
	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/checklist.php';
	include_once raiz().'lib/php/calcCuest.php';

	$uId = $_SESSION['CM']['admin']['usrId'];
	// print2($_POST);
	$bId = $_POST['datos']['bloque'];
	$aId = $_POST['datos']['area'];
	$pId = $_POST['datos']['identificador'];

	if( $_SESSION['CM']['admin']['nivel'] < 10){
		exit('No tienes acceso');
	}

	$res[$_POST['datos']['identificador']] = $_POST['datos'];

	$p = $_POST['datos'];


	$infPreg = $db->query("SELECT p.*,sa.pregunta as saNom FROM Preguntas p 
		LEFT JOIN Preguntas sa ON sa.id = p.subareasId
		WHERE p.id = $p[id]")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($infPreg);

	$hash = encriptaUsr("NL-$_POST[vId]-".$uId."-$p[id]-$pId");
	// print2($p);
	// print2 ($res[$pId]);
	$respuestas = array();
	$respuestas = $db->query("SELECT * FROM Respuestas 
		WHERE preguntasId = $p[id] AND (elim IS NULL OR elim != 1)")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {


		$('#respuesta_<?php echo $pId; ?>').focus();
		$('#justificacion').keyup(function(event) {
			$(this).css({backgroundColor:'rgba(255,255,255,1)'});
		});


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

		$('#env').click(function(event) {

			var allOk = camposObligatorios('#datosPreg');
			if(allOk){
				// console.log('allOk');
				var datos = {};
				datos['visitasId'] = <?php echo $_POST['vId']; ?>;
				datos['preguntasId'] = <?php echo $p['id']; ?>;
				datos['respuesta'] = $('#respuesta_<?php echo $pId; ?>').val();
				datos['justificacion'] = $('#justif').is(':visible')?$('#justificacion').val():'';

				var hash = '<?php echo $hash; ?>';
				var pIdAct = '<?php echo $pId; ?>';

				// console.log(datos);
				var rj = jsonF('admin/proyectos/json/envResp.php',{datos:datos,hash:hash,pId:pIdAct});
				console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					$('#popUpMapa').modal('toggle');
					var vId = <?php echo $_POST['vId']; ?>;
					var cteId = <?php echo $_POST['cteId']; ?>;
					var hash = '<?php echo $_POST['hash']; ?>';
					// console.log(vId);
					$('#popCont').load(rz+'admin/proyectos/verAvComp.php',{div:1,vId:vId,rev:1,cteId:cteId,hash:hash});
				}

				
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Editar respuesta</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<?php if (!empty($p['subarea'])){ ?>
		<div class="nomSubArea">
			<?php echo $infPreg['saNom']; ?>	
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
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
