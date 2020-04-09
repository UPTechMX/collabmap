<?php  
	include_once '../../lib/j/j.func.php';
	// print2($_POST);
	session_start();
	checaAcceso(49);
	$usrId = $_SESSION['CM']['admin']['usrId'];
	// echo $usrId;
	$fechaHoy = date("Y-m-d");
	// echo $fechaHoy;
	$vInfo = $db->query("SELECT * FROM Visitas WHERE id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$datCte = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";
	// print2($_POST);
	$elem = explode('_',$_POST['act'])[1];
	// echo $elem;

?>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Confirmación de <?php echo $elem== 'visita'? 'visita':'instalación'; ?></h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>



<script type="text/javascript">
	
	var mapaConf;

	$(document).ready(function() {

		var activadoClick;

		mapaConf = new Mapa(null, "mapdiv", "panelDiv");
		mapaConf.creaMapa();
		// mapa.bubbleButton(true,"Usar esta ubicación");
		mapaConf.changeActiveMarker(true);

		mapaConf.setBubbles(false);

		mapaConf.setFuncionClick( function(){  
			if(mapaConf.activeMarker.label == "cliente"){
				// console.log("cliente actual");
				return;
			}

			activadoClick = 1;

			$('#usuarioProgramado').val(mapaConf.activeMarker.valor); 
			// console.log(mapa.activeMarker, mapa.activeMarker.valor);
			$('#usuarioProgramado').change();
			
		});

		<?php
		if(!empty($datCte['lat']) && !empty($datCte['lat'])) {
			echo "var positionCte = {lat: $datCte[lat], lng: $datCte[lng]};";
			echo "mapaConf.addSingleCoord($datCte[lat], $datCte[lng], 'cliente', 'cliente', 'darkGreen', 'darkGreen', false);";
		}else{
			echo "var positionCte = null;";
		}
		?>



		$('#btnAsignaCercano').click(function(ev){

			ev.preventDefault();
			mapaConf.dibuja();

			if(positionCte == null)
				return;

			a = mapaConf.markerMasCercano(positionCte.lat, positionCte.lng, {grupo:"marcadores", activar: true} );
			
			activadoClick = 1;
			// console.log(activadoClick);

			$('#usuarioProgramado').val(mapaConf.activeMarker.valor); 
			$('#usuarioProgramado').change();

        });


		function getPersonal(nivel,fecha){
			var pj = jsonF('admin/proyectos/json/getPersonal.php',{nivel:nivel,fecha:fecha});
			console.log(pj);
			var p = $.parseJSON(pj);

			var ps = [];
			var ubics = [];
			for(var i in p){
				var pp = p[i];
				var tmp = {};
				tmp.nom = pp[0].uNom;
				tmp.val = pp[0].id;
				tmp.clase = 'clase';
				ps.push(tmp);

				for(var u in pp){
					// console.log(pp);
					var tmp2 = {};
					tmp2.lat = pp[u].lat;
					tmp2.lng = pp[u].lng;
					tmp2.valor = pp[u].id;
					tmp2.label = pp[u].uNom;
					ubics.push(tmp2);
				}
			}

			optsSel(ps,$('#usuarioProgramado'),false,'- - - -',false);

			var personal = {};
			personal.all = p;
			personal.ubics = ubics;

			// console.log("QQQQQQQQQq", personal.ubics);
			mapaConf.setManyCoordsAsGroup("marcadores", personal.ubics, null, null);
			mapaConf.dibuja();

			return personal;
		}

		var act = '<?php echo $elem; ?>';
		if(act == 'visita'){
			var nivel = 30;
		}else{
			var nivel = 40;
		}
		// console.log(act,nivel);

		personal = getPersonal(nivel,$('#fecha').val());
		// console.log(personal);

		$('#fecha').change(function(event) {
			personal = getPersonal(nivel,$('#fecha').val());
		});

		$('#addEqI').click(function(event) {
			var fecha = $('#fecha').val();
			if(fecha == ''){
				alertar('La fecha no puede ser vacía',function(){},{});
			}else{
				popUpMapa('admin/proyectos/addEqI.php',{fecha:fecha})
			}
		});

		$('#usuarioProgramado').change(function(event) {
			var uId = $(this).val();
			var visitasUsr = personal.all[uId];

			if(activadoClick != 1){
				mapaConf.dibuja();
			}
			activadoClick = 0;

			$('#tableHorarios').empty();
			if(uId != ''){
				for(var vu in visitasUsr){
					var v = visitasUsr[vu];
					// console.log(typeof v.hora);
					if(v.vId == null){
						// console.log(v);
						continue;
					}

					if(act == 'visita'){				
						var html = `
							<tr>
								<td>Hora:</td>
								<td>${v.hora}</td>
							</tr>
						`;
					}else{
						var html = `
							<tr>
								<td>Horario:</td>
								<td>${v.horario == '1'?'Matutino':'Vespertino'}</td>
							</tr>
						`;
					}
					$('#tableHorarios').append(html);
				}

				if($('#tableHorarios tr').length == 0){
					$('#tableHorarios')
					.append('<td colspan="2" style="text-align:center;">NO HAY VISITAS ASIGNADAS PARA ESTE USUARIO EN ESTA FECHA</td>');
				}	
			}
		});

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		jQuery(function($){
			$.datepicker.regional['es'] = {
				closeText: 'Cerrar',
				prevText: '&#x3C;Ant',
				nextText: 'Sig&#x3E;',
				currentText: 'Hoy',
				monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
				'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
				'Jul','Ago','Sep','Oct','Nov','Dic'],
				dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
				dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
				dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
				weekHeader: 'Sm',
				dateFormat: 'dd/mm/yy',
				firstDay: 1,
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''
			};
			$.datepicker.setDefaults($.datepicker.regional['es']);
		});

		$('#fecha').datepicker( { 
			dateFormat: 'yy-mm-dd',
			changeYear: true,
			changeMonth: true,
			minDate: new Date("<?php echo $fechaHoy; ?>"),
			maxDate: new Date("2040-12-30"),
			showAnim:"slideDown" 
		});


		$('#env').click(function(event) {
			var dat = $('#fVis').serializeObject();
			dat.id = <?php echo $_POST['vId']; ?>;
			var allOk = camposObligatorios('#fVis');
			
			if(allOk){
				console.log(act);
				conf(`¿Estás seguro que deseas confirmar la 
					${act == 'visita'?'visita':(act == 'instalacion'?'instalación':'reparación')} con los siguientes datos?<br/>
				<strong>Fecha :</strong>  ${$('#fecha').val()} <br/>
				<strong>${act == 'visita'?'Visitador':'Equipo'} :</strong>  ${$('#usuarioProgramado option:selected').text()}<br/>
				<strong>Cliente :</strong>  <?php echo $cteNom; ?><br/>
				`,{datos:dat,acc:2,opt:1,act:act},
				function(e){
					var rj = jsonF('admin/proyectos/json/agendas.php',{datos:e.datos,acc:2,opt:1,act:e.act});
					// console.log(rj);
					cteId = <?php echo $_POST['cteId']; ?>;
					try{
						var r = $.parseJSON(rj);
						// console.log(r);
					}catch(e){
						console.log('Error de parseo');
						console.log(rj);
						var r = {ok:0};
					}
					// console.log(r);
					if(r.ok == 1){
						$('#popUp').modal('toggle');
						$('#tr_'+cteId).load(rz+'admin/proyectos/clienteFila.php',{cId: cteId},function(){});
					}

				})
			}
		});

	});
</script>


<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="fVis">
		<table class="table" border="0">
			<tr>
				<td>Cliente</td>
				<td>
					<?php echo $cteNom; ?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Fecha</td>
				<td>
					<input type="text" name="fecha" id="fecha" class="form-control oblig" value="<?php echo $vInfo['fecha'] ?>" />
				</td>
				<td></td>
			</tr>
			<?php if ($elem == 'visita'){ ?>
				<tr>
					<td>Hora:</td>
					<td>
						<select name="hora" id="hora" class="form-control" >
							<option value="08:00:00" <?php echo $vInfo['hora'] == '08:00:00'?'selected':''; ?> >08:00:00</option>
							<option value="09:00:00" <?php echo $vInfo['hora'] == '09:00:00'?'selected':''; ?> >09:00:00</option>
							<option value="10:00:00" <?php echo $vInfo['hora'] == '10:00:00'?'selected':''; ?> >10:00:00</option>
							<option value="11:00:00" <?php echo $vInfo['hora'] == '11:00:00'?'selected':''; ?> >11:00:00</option>
							<option value="12:00:00" <?php echo $vInfo['hora'] == '12:00:00'?'selected':''; ?> >12:00:00</option>
							<option value="13:00:00" <?php echo $vInfo['hora'] == '13:00:00'?'selected':''; ?> >13:00:00</option>
							<option value="14:00:00" <?php echo $vInfo['hora'] == '14:00:00'?'selected':''; ?> >14:00:00</option>
							<option value="15:00:00" <?php echo $vInfo['hora'] == '15:00:00'?'selected':''; ?> >15:00:00</option>
							<option value="16:00:00" <?php echo $vInfo['hora'] == '16:00:00'?'selected':''; ?> >16:00:00</option>
							<option value="17:00:00" <?php echo $vInfo['hora'] == '17:00:00'?'selected':''; ?> >17:00:00</option>
							<option value="18:00:00" <?php echo $vInfo['hora'] == '18:00:00'?'selected':''; ?> >18:00:00</option>
							<option value="19:00:00" <?php echo $vInfo['hora'] == '19:00:00'?'selected':''; ?> >19:00:00</option>
							<option value="20:00:00" <?php echo $vInfo['hora'] == '20:00:00'?'selected':''; ?> >20:00:00</option>
							<option value="21:00:00" <?php echo $vInfo['hora'] == '21:00:00'?'selected':''; ?> >21:00:00</option>
						</select>
					</td>
					<td></td>
				</tr>

			<?php }else{ ?>
				<tr>
					<td>Horario</td>
					<td>
						<select name="horario" id="horario" class="form-control" >
							<option value="1" <?php echo $vInfo['horario'] == 1?'selected':''; ?> >Matutino</option>
							<option value="2" <?php echo $vInfo['horario'] == 2?'selected':''; ?> >Vespertino</option>
						</select>
						
					</td>
					<td></td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="3">
					<div style="position:relative">
						<div style="position:absolute; top:5px; right:5px; z-index:2">
							<span id="btnAsignaCercano" class="btn btn-sm btn-shop ">
								Asignar <?php echo $elem == 'visita'?'visitador':'equipo'; ?> automáticamente
							</span>
						</div>
						<div id="mapdiv" style="height: 300px;">		
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $elem == 'visita'?'Visitador':'Equipo'; ?>
				</td>
					<?php if ($elem == 'instalacion' || $elem == 'reparacion'){ ?>
						<td class="form-inline">
							<span class="btn btn-sm btn-shop" id="addEqI" >
								<i class="glyphicon glyphicon-plus manita inline" style="color: white"></i> Equipo
							</span>&nbsp;&nbsp;
							<select id="usuarioProgramado" name="equipo" class="form-control oblig obligSel" style="width: 300px;">
							</select>
						</td>
					<?php }else{ ?>
						<td>
							<select id="usuarioProgramado" name="usuarioProgramado" class="form-control oblig obligSel"></select>
						</td>
					<?php } ?>
				<td></td>
			</tr>
			<tr>
				<td colspan="3">
					<table class="table" id="tableHorarios"></table>
				</td>
			</tr>
		</table>
	</form>

</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
