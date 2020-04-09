
<?php  

	include_once '../../lib/j/j.func.php';

	// print2($_POST);
	$pregFiltros = [19,20,26,30,33,35,36,37,38,39,40,67,79,80,81];

	$w = '0';
	foreach ($pregFiltros as $p) {
		$w .= " OR id = $p";
	}

	$pregs = $db->query("SELECT * FROM PreguntasShopper WHERE ($w)")->fetchAll(PDO::FETCH_ASSOC);

	// print2($pregs);

	$marcasAutos = $db->query("SELECT respuesta as val, respuesta as nom, id as clase  
		FROM RespuestasGrupos WHERE grupo = 5")->fetchAll(PDO::FETCH_ASSOC);

	foreach ($pregs as $p) {
		if(!empty($p['gpoResp'])){
			$gpoResp[$p['gpoResp']] = $db->query("SELECT respuesta as val, respuesta as nom, id as clase  
				FROM RespuestasGrupos WHERE grupo = $p[gpoResp]")->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	if (!empty($_POST['repId'])) {
		$filtros = $db->query("SELECT rf.*, p.pregunta, p.gpoResp 
			FROM RotacionesFiltros rf
			LEFT JOIN PreguntasShopper p ON p.id = rf.preguntaId
			WHERE rf.repeticionesId = $_POST[repId]")->fetchAll(PDO::FETCH_ASSOC);
		$asunto = 'Asunto del correo de la rotación';
		$cuerpoMasivo = 'Estimado [nombre]<br/><br/>Este es el texto del correo';
	}

?>

<script type="text/javascript">
		eleNum = 0;
	$(document).ready(function() {
		$('#bodyFiltros').on('click', '.delFiltro', function(event) {
			event.preventDefault();
			var fId = $(this).closest('tr').attr('id').split('_')[1];
			var rj = jsonF('admin/proyectos/json/json.php',{fId:fId,opt:15,acc:14});
			// console.log(rj)
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$(this).closest('tr').remove();
			}
		});

		var gpoResp = <?php echo atj($gpoResp); ?>;
		// console.log(gpoResp);
		$('#pregFiltSel').change(function(event) {
			var pregId = $(this).val();
			eleNum ++;
			if(pregId !=''){

				var mult = $('#pregFiltSel :selected').attr('mult');

				switch(pregId){
					case '19':
						html =
							'<td>'+'<i class="glyphicon glyphicon-trash manita rojo delFiltro"></i>'+'</td>'+
							'<td>'+
								'<input type="hidden" id="preguntaId_'+eleNum+'" value="'+$('#pregFiltSel').val()+'" />'+
								$('#pregFiltSel :selected').text()+
							'</td>'+
							'<td><input class="form-control" type="text" id="busqueda_'+eleNum+'"/></td>'+
							'<td style="text-align:center;">a</td>'+
							'<td><input class="form-control" type="text" id="rangoSup_'+eleNum+'"/></td>';
						break;
					case '67':
						html =
							'<td>'+'<i class="glyphicon glyphicon-trash manita rojo delFiltro"></i>'+'</td>'+
							'<td>'+
								'<input type="hidden" id="preguntaId_'+eleNum+'" value="'+$('#pregFiltSel').val()+'" />'+
								'Marca de automóvil'+
							'</td>'+
							'<td><select class="form-control" type="text" id="busqueda_'+eleNum+'"></select></td>'+
							'<td>modelo:</td>'+
							'<td><input class="form-control" type="text" id="rangoSup_'+eleNum+'"/></td>';
						break;
					case '-1':
						html =
							'<td>'+'<i class="glyphicon glyphicon-trash manita rojo delFiltro"></i>'+'</td>'+
							'<td>'+
								'<input type="hidden" id="preguntaId_'+eleNum+'" value="'+$('#pregFiltSel').val()+'" />'+
								$('#pregFiltSel :selected').text()+
							'</td>'+
							'<td colspan="3">'+
								'<select class="form-control" type="text" id="busqueda_'+eleNum+'">'+
									'<option value="H" >Hombre</option>'+
									'<option value="M" >Mujer</option>'+
								'</select>'+
							'</td>';
						break;
					default:
						if(mult > 0 ){
							html =
								'<td>'+'<i class="glyphicon glyphicon-trash manita rojo delFiltro"></i>'+'</td>'+
								'<td>'+
									'<input type="hidden" id="preguntaId_'+eleNum+'" value="'+$('#pregFiltSel').val()+'" />'+
									$('#pregFiltSel :selected').text()+
								'</td>'+
								'<td colspan="3"><select class="form-control" type="text" id="busqueda_'+eleNum+'"></select></td>';
						}else{

							html =
								'<td>'+'<i class="glyphicon glyphicon-trash manita rojo delFiltro"></i>'+'</td>'+
								'<td>'+
									'<input type="hidden" id="preguntaId_'+eleNum+'" value="'+$('#pregFiltSel').val()+'" />'+
									$('#pregFiltSel :selected').text()+
								'</td>'+
								'<td colspan="3"><input class="form-control" type="text" id="busqueda_'+eleNum+'"/></td>';
						}
						break;
				}
				$('<tr>')
				.attr({id: 'tr_'+eleNum})
				.html(html)
				.appendTo('#bodyFiltros')

				if(mult != 0){
					optsSel(gpoResp[mult],$('#busqueda_'+eleNum),true,null);
				}

			}
		});


	});
</script>



<script type="text/javascript">
	$(document).ready(function() {

		$('#modal').css({width:''});

		$(".txArea").jqte({
			source:false,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		}).change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#addFilt').click(function(event) {
			popUpAlerta('admin/inicio/addFilt.php',{},function(){},{});
		});



		$('#env').click(function(event) {
			var filtros = {};
			$.each($('#bodyFiltros tr'), function(index, val) {
				var fId = this.id.split('_')[1];
				var busqueda = $('#busqueda_'+fId).val();
				var rangoSup = typeof($('#rangoSup_'+fId).val()) == 'undefined'?'':$('#rangoSup_'+fId).val();
				var preguntaId = $('#preguntaId_'+fId).val();;

				filtros[fId] = {busqueda:busqueda,rangoSup:rangoSup,preguntaId:preguntaId};
			});

			var mensaje = $('#mensaje').val();
			var asunto = $('#asunto').val();

			console.log(filtros);

			var rj = jsonF('admin/inicio/envMasivo.php',{filtros:filtros,mensaje:mensaje,asunto:asunto});
			console.log(rj);
			var r = $.parseJSON(rj);
			// console.log(r);

			if(r.ok == 1){
				$('#popUp').modal('toggle');
			}


		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Enviar correo masivo</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<div>
		<div class="nuevo">Filtros</div>


		<br/>
			<table class="table" border="0">
				<tr>
					<td>Agregar pregunta</td>
					<td>
						<select id="pregFiltSel" class="form-control">
							<option value="">- - - Selecciona una pregunta - - -</option>
							<?php foreach ($pregs as $p){ ?>
								<?php if ($p['id'] == 19){ ?>
									<option value="<?php echo $p['id']; ?>" mult="0">Edad</option>
								<?php }elseif ($p['id'] == 67){ ?>
									<option value="<?php echo $p['id']; ?>" mult="5">Marca de automóvil</option>
								<?php }else{ ?>
									<option value="<?php echo $p['id']; ?>" 
										mult="<?php echo !empty($p['gpoResp'])?$p['gpoResp']:0; ?>"><?php echo "$p[pregunta]"; ?></option>
								<?php } ?>
							<?php } ?>
							<option value="-1" mult="0">Género</option>
						</select>
					</td>
				</tr>
			</table>
			<hr/>

		<form id="nPry">
			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th>Pregunta</th>
						<th colspan="3">Búsqueda</th>
					</tr>
				</thead>
				<tbody id="bodyFiltros">
					<?php if (!empty($_POST['repId'])){
						$i = 0;
							foreach($filtros as $f){
								$i++;
								switch ($f['preguntaId']) {
									case '19':
										$html =
											'<td><i class="glyphicon glyphicon-trash manita rojo delFiltro"></i></td>'.
											'<td>'.
												'Edad'.
												'<input type="hidden" id="preguntaId_'.$i.'" value="'.$f['preguntaId'].'" />'.
											'</td>'.
											'<td>'.
												'<input class="form-control" type="text" id="busqueda_'.$i.'" value="'.$f['busqueda'].'"/>'.
											'</td>'.
											'<td style="text-align:center;">a</td>'.
											'<td>'.
												'<input class="form-control" type="text" id="rangoSup_'.$i.'" value="'.$f['rangoSup'].'"/>'.
											'</td>';
										break;
									case '67':
										$html =
											'<td><i class="glyphicon glyphicon-trash manita rojo delFiltro"></i></td>'.
											'<td>'.
												'Marca de automóvil'.
												'<input type="hidden" id="preguntaId_'.$i.'" value="'.$f['preguntaId'].'" />'.
											'</td>'.
											'<td>'.
												'<select class="form-control" id="busqueda_'.$i.'">';
													foreach ($gpoResp[5] as $r) {
														$html .= "<option value='$r[val]' ".($f['busqueda'] == $r['val']?'selected':'').">
															$r[nom]</option>";
													}
										$html .='</select>'.
											'</td>'.
											'<td>'.
												'modelo:'.
												'<input type="hidden" id="preguntaId_'.$i.'" value="'.$f['preguntaId'].'" />'.
											'</td>'.
											'<td>
												<input class="form-control" type="text" id="rangoSup_'.$i.'" value="'.$f['rangoSup'].'"/>
											</td>';
										break;
									case '-1':
										$html =
											'<td><i class="glyphicon glyphicon-trash manita rojo delFiltro"></i></td>'.
											'<td>'.
												'Género'.
												'<input type="hidden" id="preguntaId_'.$i.'" value="'.$f['preguntaId'].'" />'.
											'</td>'.
											'<td colspan="3">'.
												'<select class="form-control" id="busqueda_'.$i.'">'.
													'<option value="H" '.($f['busqueda'] == 'H'?'selected':'').' >Hombre</option>'.
													'<option value="M" '.($f['busqueda'] == 'M'?'selected':'').' >Mujer</option>'.
												'</select>'.
											'</td>';
										break;
									default:
										if(!empty($f['gpoResp'])){
											$html =
												'<td><i class="glyphicon glyphicon-trash manita rojo delFiltro"></i></td>'.
												'<td>'.
													$f['pregunta'].
													'<input type="hidden" id="preguntaId_'.$i.'" value="'.$f['preguntaId'].'" />'.
												'</td>'.
												'<td colspan="3">'.
													'<select class="form-control" id="busqueda_'.$i.'">';
													foreach ($gpoResp[$f['gpoResp']] as $r) {
														$html .= "<option value='$r[val]' ".($f['busqueda'] == $r['val']?'selected':'').">
															$r[nom]</option>";
													}
											$html .='</select>'.
												'</td>';
										}else{								
											$html =
												'<td><i class="glyphicon glyphicon-trash manita rojo delFiltro"></i></td>'.
												'<td>'.
													$f['pregunta'].
													'<input type="hidden" id="preguntaId_'.$i.'" value="'.$f['preguntaId'].'" />'.
												'</td>'.
												'<td colspan="3">'.
													'<input class="form-control" type="text" id="busqueda_'.$i.'" value="'.$f['busqueda'].'" />'.
												'</td>';
										}
										break;
								}
								echo "<tr id='tr_$f[id]'>$html</tr>";
							}
					?>
					<script type="text/javascript">
						eleNum = <?php echo $i+1; ?>;
					</script>

					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>
	<div class="nuevo">Asunto</div>
	<input type="text" id="asunto" name="asunto" class="form-control" value="<?php echo $asunto; ?>">
	<div class="nuevo" style="margin-top: 10px;">Mensaje</div>
	<textarea id="mensaje" name="mensaje" class="form-control txArea"><?php echo $cuerpoMasivo; ?></textarea>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
