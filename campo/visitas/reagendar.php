<?php  

	include_once '../../lib/j/j.func.php';
	session_start();
	$usrId = $_SESSION['IU']['admin']['usrId'];
	// echo $usrId;
	$fechaHoy = date("Y-m-d");
	// echo $fechaHoy;

	// print2($_POST);
	// exit();
	$datCte = $db->query("SELECT c.* 
		FROM Visitas v
		LEFT JOIN Clientes c ON v.clientesId = c.id
		WHERE v.id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";

	// $vis = $db->query("SELECT * FROM Visitas WHERE id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC);
	// print2($vis);
	// print2($datCte);
	$elem = explode('_',$_POST['act'])[1];
	// echo $elem;
?>

<script type="text/javascript">
	$(document).ready(function() {

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
			dat.usuariosCreador = <?php echo $usrId; ?>;
			comentarios = $("#comentarios").val();
			var allOk = camposObligatorios('#fVis');
			
			if(allOk){
				var vId = <?php echo $_POST['vId']; ?>;
				var rj = jsonF('campo/visitas/json/agendas.php',{datos:dat,acc:1,opt:2,comentarios:comentarios,vId:vId});
				// console.log(rj);
				try{
					var r = $.parseJSON(rj);
					console.log(r);
				}catch(e){
					console.log('Error de parseo');
					console.log(rj);
					var r = {ok:0};
				}
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					// $('#tr_'+dat.clientesId).load(rz+'admin/proyectos/clienteFila.php',{cId: dat.clientesId},function(){});
				}
			}
		});
	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Agendar <?php echo $elem; ?></h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
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
					<input type="text" name="fecha" id="fecha" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<?php if ($elem == 'visita'){ ?>
				<tr>
					<td>Hora</td>
					<td>
						<select name="hora" id="hora" class="form-control" >
							<option value="8:00:00">8:00:00</option>
							<option value="9:00:00">9:00:00</option>
							<option value="10:00:00">10:00:00</option>
							<option value="11:00:00">11:00:00</option>
							<option value="12:00:00">12:00:00</option>
							<option value="13:00:00">13:00:00</option>
							<option value="14:00:00">14:00:00</option>
							<option value="15:00:00">15:00:00</option>
							<option value="16:00:00">16:00:00</option>
							<option value="17:00:00">17:00:00</option>
							<option value="18:00:00">18:00:00</option>
							<option value="19:00:00">19:00:00</option>
							<option value="20:00:00">20:00:00</option>
							<option value="21:00:00">21:00:00</option>
						</select>
					</td>
					<td></td>
				</tr>

			<?php }elseif ($elem == 'instalacion'){ ?>
				<tr>
					<td>Horario</td>
					<td>
						<select name="horario" id="horario" class="form-control" >
							<option value="1">Matutino</option>
							<option value="2">Vespertino</option>
						</select>
						
					</td>
					<td></td>
				</tr>
			<?php }elseif ($elem == 'reparacion'){ ?>
				<tr>
					<td>Descripción</td>
					<td>
						<textarea id="comentarios" class="form-control"></textarea>						
					</td>
					<td></td>
				</tr>
			<?php } ?>

		</table>
		<input type="hidden" name="clientesId" value="<?php echo $datCte['id']; ?>">
		<input type="hidden" name="proyectosId" value="<?php echo $datCte['proyectosId']; ?>">
		<input type="hidden" name="etapa" value="<?php echo $elem; ?>">
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
