<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Proyectos WHERE id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	}
	$nivel = $_SESSION['CM']['admin']['nivel'];
	if($nivel<50){
		exit('No tienes acceso a esta área');
	}



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
			$(this).css({backgroundColor:''});
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

		$('#fIni').datepicker( { 
			dateFormat: 'yy-mm-dd',
			changeYear: true,
			changeMonth: true,
			minDate: new Date("2010-01-02"),
			maxDate: new Date("2040-12-30"),
			showAnim:"slideDown" 
		});
		$('#fFin').datepicker( { 
			dateFormat: 'yy-mm-dd',
			changeYear: true,
			changeMonth: true,
			minDate: new Date("2010-01-02"),
			maxDate: new Date("2040-12-30"),
			showAnim:"slideDown" 
		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');


			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>


			if(allOk){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:acc,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#proyectosList').load(rz+'admin/administracion/proyectos/proyectosList.php',{ajax:1});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['eleId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			proyecto
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td>Nombre</td>
				<td>
					<input type="text" value="<?php echo $datC['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Monto asignado</td>
				<td>
					<input type="text" value="<?php echo $datC['presupuesto']; ?>" name="presupuesto" id="presupuesto" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Meta mínima</td>
				<td>
					<input type="text" value="<?php echo $datC['metaMinima']; ?>" name="metaMinima" id="metaMinima" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Meta final</td>
				<td>
					<input type="text" value="<?php echo $datC['metaFinal']; ?>" name="metaFinal" id="metaFinal" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Fecha de inicio</td>
				<td>
					<input type="text" value="<?php echo $datC['fIni']; ?>" name="fIni" id="fIni" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Fecha de término</td>
				<td>
					<input type="text" value="<?php echo $datC['fFin']; ?>" name="fFin" id="fFin" class="form-control oblig" >
				</td>
				<td></td>
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
