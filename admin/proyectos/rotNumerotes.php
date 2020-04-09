<?php

	session_start();
	include_once '../../lib/j/j.func.php';
	checaAcceso(49);


	$usrId = $_SESSION['IU']['admin']['usrId'];
	$nivel = $_SESSION['IU']['admin']['nivel'];

	$pId = $_POST['pryId'];

	$fechaHoy = date("Y-m-d");
	// $fechaHoy = '2019-02-02';//date("Y-m-d");

	$instGral = instalaciones(null,$pId);
	$instHoy = instalaciones($fechaHoy,$pId);


	function cuentas($gpos){
		$rotTotales['total'] = 0;
		foreach ($gpos as $g => $num) {
			$rotTotales['total'] += $num[0]['cuenta'];
		}

		$rotTotales['total'] -= $gpos['noRealizara'][0]['cuenta'];
		$rotTotales['canceladas'] = $gpos['canceladas'][0]['cuenta'];
		$rotTotales['canceladasNV'] = $gpos['canceladasNV'][0]['cuenta'];
		$rotTotales['enRegistro'] = $gpos['enRegistro'][0]['cuenta'];
		$rotTotales['enVisita'] = $gpos['enVisita'][0]['cuenta'];
		$rotTotales['instalacion'] = $gpos['instalacion'][0]['cuenta'];
		$rotTotales['reparacion'] = $gpos['reparacion'][0]['cuenta'];
		$rotTotales['seguimiento'] = $gpos['seguimiento'][0]['cuenta'];

		return $rotTotales;

	}

	$sql = "SELECT 
		CASE
			WHEN r.estatus < 3 THEN 'sinEst'
			WHEN r.estatus = 3 THEN 'canceladasNV'
			WHEN r.estatus = 4 THEN 'canceladas'
			WHEN r.estatus >= 5 AND r.estatus <10 THEN 'enRegistro'
			WHEN (r.estatus >= 30 AND r.estatus <40) OR r.estatus = 10 THEN 'enVisita'
			WHEN r.estatus >= 40 AND r.estatus <50 THEN 'instalacion'
			WHEN r.estatus = 55 THEN 'reparacion'
			WHEN r.estatus >= 60  THEN 'seguimiento'
			ELSE 'algo'
		END as eGroup,
		COUNT(*) as cuenta, estatus
		FROM Clientes r 
		WHERE proyectosId = $pId
		GROUP BY eGroup";
	// echo $sql;
	$gpos = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$rotTotales = cuentas($gpos);

	$rotTotales['gpos'] = $gpos;


	// print2($rotTotales);

	$total = empty($rotTotales['total'])?0:$rotTotales['total'];
	$canceladas = empty($rotTotales['canceladas'])?0:$rotTotales['canceladas'];
	$canceladasNV = empty($rotTotales['canceladasNV'])?0:$rotTotales['canceladasNV'];
	$enRegistro = empty($rotTotales['enRegistro'])?0:$rotTotales['enRegistro'];
	$enVisita = empty($rotTotales['enVisita'])?0:$rotTotales['enVisita'];
	$instalacion = empty($rotTotales['instalacion'])?0:$rotTotales['instalacion'];
	$reparacion = empty($rotTotales['reparacion'])?0:$rotTotales['reparacion'];
	$seguimiento = empty($rotTotales['seguimiento'])?0:$rotTotales['seguimiento'];


	// print2($rotacion);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#indicadores').on('click', '.chFiltro', function(event) {
			event.preventDefault();
			var filt = this.id;
			// console.log(filt);

			// console.log($('#estSel').val());


			switch(filt){
				case 'totalNum':
					var arr = [];
					break;
				case 'canceladasNum':
					var arr = [4];
					break;
				case 'canceladasNVNum':
					var arr = [3];
					break;
				case 'enVisitaNum':
					var arr = [10,30,31,32,34,35,37,38];
					break;
				case 'instalacionNum':
					var arr = [40,42,44,45,46,47,48];
					break;
				case 'reparacionNum':
					var arr = [55];
					break;
				case 'seguimientoNum':
					var arr = [60];
					break;
			}
			var estatusSumo = $('#estSel').SumoSelect();

			estatusSumo.sumo.unSelectAll();

			// console.log(arr);
			for(var i in arr){
				estatusSumo.sumo.selectItem(arr[i].toString());
			}

			$('#estSel').val(arr);
			$('#estSel').trigger('change');

		});

		$('#indicadores').on('click', '.pendienteNum', function(event) {
			event.preventDefault();
			$('#totalNum').trigger('click');
			$('#bodyCtes tr').hide();
			$('#bodyCtes tr .pendiente').parent().show();

		});

		$('#calcular').click(function(event) {
			var fecha = $("#fechaBusq").val();
			if(fecha != ''){
				var rj = jsonF('admin/proyectos/json/json.php',{fecha:fecha,pryId:<?php echo $pId; ?>,acc:4});
				console.log(rj);
				var r = $.parseJSON(rj);
				$('#insPrgFechaMat').text(r.programadasMat);
				$('#insPrgFechaVesp').text(r.programadasVesp);
				$('#insRealFecha').text(r.realizadas);
			}
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

		$('#fechaBusq').datepicker( { 
			dateFormat: 'yy-mm-dd',
			changeYear: true,
			changeMonth: true,
			minDate: new Date("2000-12-30"),
			maxDate: new Date("2040-12-30"),
			showAnim:"slideDown" 
		});

		setTimeout(function(){		
			var pendienteNum = $('.pendiente').length;
			$('#pendienteNum').text(pendienteNum);
		},500)
		// console.log(pendienteNum);
		
	});
</script>

<div class="nuevo mayusculas">Indicadores</div>
<table style="font-size: x-small; margin-bottom: 10px;">
	<tr>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Total</div>
				<div id="totalNum" class="numerote manita chFiltro"><?php echo $total; ?></div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">
					Canceladas
				</div>
				<div id="canceladasNum" class="numerote manita chFiltro"><?php echo $canceladas; ?></div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">
					No viable
				</div>
				<div id="canceladasNVNum" class="numerote manita chFiltro"><?php echo $canceladasNV; ?></div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">
					Pendiente
				</div>
				<div id="pendienteNum" class="numerote manita pendienteNum">&nbsp;</div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Visita en proceso</div>
				<div id="enVisitaNum" class="numerote manita chFiltro"><?php echo $enVisita; ?></div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Inst. en proceso</div>
				<div id="instalacionNum" class="numerote manita chFiltro"><?php echo $instalacion; ?></div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Reparación</div>
				<div id="reparacionNum" class="numerote manita chFiltro"><?php echo $reparacion; ?></div>
			</div>
		</td>
		<td>
			<div style="border:solid 1px #43729a; border-radius: 10px;">
				<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">
					En seguimiento
				</div>
				<div id="seguimientoNum" class="numerote manita chFiltro"><?php echo $seguimiento; ?></div>
			</div>
		</td>
	</tr>
</table>


<!-- <div class="row" style="font-size: x-small; margin-bottom: 10px;">
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Total</div>
			<div id="totalNum" class="numerote manita chFiltro"><?php echo $total; ?></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;padding:10px 0px;">
				Canceladas
			</div>
			<div id="canceladasNum" class="numerote manita chFiltro"><?php echo $canceladas; ?></div>
		</div>
	</div>


	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;padding:10px 0px;">
				No viable
			</div>
			<div id="canceladasNVNum" class="numerote manita chFiltro"><?php echo $canceladasNV; ?></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;padding:10px 0px;">
				Pendiente
			</div>
			<div id="pendienteNum" class="numerote manita chFiltro"></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Visita en proceso</div>
			<div id="enVisitaNum" class="numerote manita chFiltro"><?php echo $enVisita; ?></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Inst. en proceso</div>
			<div id="instalacionNum" class="numerote manita chFiltro"><?php echo $instalacion; ?></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Reparación</div>
			<div id="reparacionNum" class="numerote manita chFiltro"><?php echo $reparacion; ?></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;">Reparación</div>
			<div id="reparacionNum" class="numerote manita chFiltro"><?php echo $reparacion; ?></div>
		</div>
	</div>
	<div class="col-md-2">
		<div style="border:solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 50px; border-top-left-radius: 10px;border-top-right-radius: 10px;padding:10px 0px;">
				En seguimiento
			</div>
			<div id="seguimientoNum" class="numerote manita chFiltro"><?php echo $seguimiento; ?></div>
		</div>
	</div>
</div> -->

<div class="nuevo mayusculas">Instalaciones</div>
<div class="row" style="margin-bottom: 10px;">
	<div class="col-6">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">
				Instalaciones programadas del proyecto
			</div>
			<div id="insPrgPry" style="font-size: 2em;text-align: center;"><?php echo $instGral['programadas']; ?></div>
		</div>
	</div>
	<div class="col-6">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">
				Instalaciones realizadas del proyecto
			</div>
			<div id="insRealPry" style="font-size: 2em;text-align: center;"><?php echo $instGral['realizadas']; ?></div>
		</div>
	</div>
</div>
<div class="row" style="margin-bottom: 10px;">
	<div class="col-3">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">
				Instalaciones programadas hoy matutinas
			</div>
			<div id="insPrgHoy" style="font-size: 2em;text-align: center;"><?php echo $instHoy['programadasMat']; ?></div>
		</div>
	</div>
	<div class="col-3">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">
				Instalaciones programadas hoy vespertinas
			</div>
			<div id="insPrgHoy" style="font-size: 2em;text-align: center;"><?php echo $instHoy['programadasMat']; ?></div>
		</div>
	</div>
	<div class="col-6">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 115px;border-top-left-radius: 10px;border-top-right-radius: 10px;padding-top: 40px;">
				Instalaciones realizadas hoy
			</div>
			<div id="insRealHoy" style="font-size: 2em;text-align: center;"><?php echo $instHoy['realizadas']; ?></div>
		</div>
	</div>
</div>
<div>
	<table class="table">
		<tr>
			<td width="70%">
				<input type="text" id="fechaBusq" class="form-control" />
			</td>
			<td>
				<span class="btn btn-sm btn-shop" id="calcular">Calcular</span>
			</td>
		</tr>
	</table>
</div>
<div class="row" style="margin-bottom: 10px;">
	<div class="col-3">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">
				Instalaciones programadas matutinas
			</div>
			<div id="insPrgFechaMat" style="font-size: 2em;text-align: center;">&nbsp;</div>
		</div>
	</div>
	<div class="col-3">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">
				Instalaciones programadas vespertinas
			</div>
			<div id="insPrgFechaVesp" style="font-size: 2em;text-align: center;">&nbsp;</div>
		</div>
	</div>
	<div class="col-6">
		<div style="border: solid 1px #43729a; border-radius: 10px;">
			<div class="nuevo mayusculas" style="height: 90px;border-top-left-radius: 10px;border-top-right-radius: 10px;padding-top: 30px;">
				Instalaciones realizadas
			</div>
			<div id="insRealFecha" style="font-size: 2em;text-align: center;">&nbsp;</div>
		</div>
	</div>
</div>



