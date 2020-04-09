<?php

	session_start();
	include_once '../../lib/j/j.func.php';
	checaAcceso(49);

	// include_once raiz().'/lib/php/usrInt.php';
	// print2($_SESSION);
	$usrId = $_SESSION['IU']['admin']['usrId'];
	$nivel = $_SESSION['IU']['admin']['nivel'];
	// $usr = new Usuario($usrId);

	// print2($_POST);
	$fechaHoy = date("Y-m-d");

	$pryTots = getPryTotales($_POST['pryId']);
	// print2($pryTots);

	$pryInf = $db->query("SELECT * 
		FROM Proyectos WHERE id = $_POST[pryId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// $pryTots = $usr->getpryTots($_POST['pryId']);
	// print2($pryTots);

	$total = empty($pryTots['total'])?0:$pryTots['total'];
	$canceladas = empty($pryTots['canceladas'])?0:$pryTots['canceladas'];
	$enRegistro = empty($pryTots['enRegistro'])?0:$pryTots['enRegistro'];
	$enVisita = empty($pryTots['enVisita'])?0:$pryTots['enVisita'];
	$enInstalacion = empty($pryTots['enInstalacion'])?0:$pryTots['enInstalacion'];
	$enSeguimiento = empty($pryTots['enSeguimiento'])?0:$pryTots['enSeguimiento'];

	$clientes = $db->query("SELECT c.*, e.nombre as eNom, e.color, 
		cv.bloqueCalif as avComp, vv.id as vvId, v.fecha, v.hora, v.horario, v.etapa, canc.timestamp as fechaCancelacion,
		v.id as vId, vst.id as vstId, vst.finalizada as vstFin, vrs.id as vrsId, vrs.finalizada as vrsFin
		FROM Clientes c
		LEFT JOIN Estatus e ON c.estatus = e.id
		LEFT JOIN Visitas v ON v.id = c.visitasId
		LEFT JOIN estatusHist canc ON canc.clientesId = c.id AND canc.estatus = 4 
			AND canc.id = (SELECT id FROM estatusHist j 
					WHERE j.clientesId = canc.clientesId 
					AND j.estatus = 4 
					ORDER BY j.timestamp DESC 
					LIMIT 1)
		LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'visita' 
			AND vv.id = (SELECT id FROM Visitas z 
				WHERE z.clientesId = vv.clientesId 
				AND z.etapa = 'visita' 
				ORDER BY z.fechaRealizacion DESC 
				LIMIT 1)
		LEFT JOIN Visitas vst ON vst.clientesId = c.id AND vst.etapa = 'seguimientoTel' 
			AND vst.id = (SELECT id FROM Visitas k 
				WHERE k.clientesId = vst.clientesId 
				AND k.etapa = 'seguimientoTel' AND (finalizada IS NULL OR finalizada != 1)
				ORDER BY k.fechaRealizacion DESC 
				LIMIT 1)
		LEFT JOIN Visitas vrs ON vrs.clientesId = c.id AND vrs.etapa = 'reparacion' 
			AND vrs.id = (SELECT id FROM Visitas l 
				WHERE l.clientesId = vrs.clientesId 
				AND l.etapa = 'reparacion' AND (finalizada IS NULL OR finalizada != 1)
				ORDER BY l.fechaRealizacion DESC 
				LIMIT 1)
		LEFT JOIN CalculosVisita cv ON cv.visitasId = vv.id AND cv.bloque = 'comp'
		WHERE c.proyectosId = $_POST[pryId] 
		GROUP BY c.id, bloque
		ORDER BY v.fecha DESC")->fetchAll(PDO::FETCH_ASSOC);
	
	$estatus = $db->query("SELECT id as val, nombre as nom, CONCAT('c_',id) as clase 
		FROM Estatus 
		ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
	// print2($clientes[0]);

	// print2($clientes);
?>


<script type="text/javascript">


	// clearInterval(buscaCambios);
	if(typeof(buscaCambios) != 'undefined'){
		clearInterval(buscaCambios);
	}
	buscaCambios = setInterval(function(){
		var pryId = <?php echo $_POST['pryId']; ?>;
		var cc = jsonFA('admin/proyectos/json/cambios.php',{pryId:pryId},function(cj){
			// console.log(cj);
			if(cj != ''){
				// console.log(cj);
				var c = $.parseJSON(cj);
				// console.log(c);
				for(var i in c){
					var cteId = c[i].clientesId;
					$('#tr_'+cteId).load(rz+'admin/proyectos/clienteFila.php',{cId: cteId},function(){});
				}

				$('#calcular').trigger('click');
				var rj = jsonF('admin/proyectos/json/json.php',{fecha:'<?php echo $fechaHoy; ?>',pryId:<?php echo $_POST['pryId']; ?>,acc:4});
				var r = $.parseJSON(rj);
				$('#insPrgHoyMat').text(r.programadasMat);
				$('#insPrgHoyVesp').text(r.programadasVesp);
				$('#insRealHoy').text(r.realizadas);

				var rj = jsonF('admin/proyectos/json/json.php',{fecha:'',pryId:<?php echo $_POST['pryId']; ?>,acc:4});
				var r = $.parseJSON(rj);
				$('#insPrgPryMat').text(r.programadasMat);
				$('#insPrgPryVesp').text(r.programadasVesp);
				$('#insRealPry').text(r.realizadas);

				// if(c.length > 0){
				// 	$('#indicadores').load(rz+'admin/proyectos/rotNumerotes.php',{pryId:pryId})

				// }
			}
		});
	},2000);

	$(document).ready(function() {
		$('#bodyCtes').on('click', '.edtFecha', function(event) {
			event.preventDefault();

			var cteId = this.id.split('_')[1];
			var campo = this.id.split('_')[0];
			var fecha = $(this).closest('td').find('span').text();
			var pryId = <?php echo $_POST['pryId']; ?>;
			// console.log(cteId);
			popUp('admin/proyectos/rotacionesFechaEdt.php',{cteId:cteId,campo:campo,fecha:fecha,pryId:pryId},function(){},{});
		});

		$('#bodyCtes').on('click', '.valVisita', function(event) {
			var cteId = this.id.split('_')[1];
			// console.log(cteId);
			$('#fVis_'+cteId).submit();
		});

		$('#bodyCtes').on('click', '.revVis', function(event) {

			var cteId = this.id.split('_')[1];
			var pryId = <?php echo $_POST['pryId']; ?>;
			// console.log(pryId);
			// popUp('admin/proyectos/revision.php',{div:1,cteId:cteId,rev:1},function(){},{});
			// $('#repeticionesTiendas').load(rz+'admin/proyectos/revision.php',{div:1,cteId:cteId,pryId:pryId,rev:1});
			$('#revForm_'+cteId).submit();

		});

		$('#bodyCtes').on('click', '.verAvComp', function(event) {

			var cteId = $(this).closest('tr').attr('id').split('_')[1];
			var vvId = this.id.split('_')[1];
			// console.log(vvId,cteId);

			popUp('admin/proyectos/verAvComp.php',{cteId:cteId,vId:vvId});

		});

		$('#bodyCtes').on('click', '.verUbic', function(event) {

			var lat = $(this).attr('lat');
			var lng = $(this).attr('lng');
			// console.log(vvId,cteId);

			popUp('lib/j/php/verUbic.php',{lat:lat,lng:lng});

		});

		$('#bodyCtes').on('click', '.docCte', function(event) {
			var cteId = $(this).attr('id').split('_')[1];
			var token = $(this).attr('id').split('_')[2];
			console.log($(this).attr('id'), token);
			popUp('informes/apDocs.php', {codigo:token, clienteId:cteId, admin:1});
			// popUp('clientes/documentacion.php', {clienteId:cteId, validador:1})
		});

		$('#bodyCtes').on('click', '.regLlamada', function(event) {

			var cteId = $(this).closest('tr').attr('id').split('_')[1];

			popUp('admin/proyectos/regLlamada.php',{cteId:cteId});

		});

		$('#bodyCtes').on('click', '.dwlPics', function(event) {
			var cteId = $(this).closest('tr').attr('id').split('_')[1];
			console.log(cteId);

			$('#downloadImgForm #cteId').val(cteId);
			$('#downloadImgForm').submit();


		});

		$('#busqNom').keyup(function(event) {
			var busq = $(this).val().toLowerCase()
			if(busq == ''){
				$('.nombres').closest('tr').show();
			}else{
				// console.log(busq);
				$.each($('.nombres'), function(index, val) {
					if( $(this).text().toLowerCase().search(busq) == -1 && this.id.toLowerCase().search(busq) == -1  ){
						$(this).closest('tr').hide();
					}else{
						$(this).closest('tr').show();
					}
				});
			}
		});



		$('#bodyCtes').on('click', '.addVis', function(event) {

			var act = $(this).attr('act');
			var vId = $(this).attr('vId');
			var cteId = $(this).closest('tr').attr('id').split('_')[1];
			var pryId = <?php echo $_POST['pryId'];  ?>;
			console.log('AAAAAAA');
			switch(act){
				case 'agenda_visita':
				case 'agenda_instalacion':
				case 'agenda_reparacion':
					popUp('admin/proyectos/agendar.php',{act:act,cteId:cteId,pryId:pryId},function(){},{});
					break;
				case 'conf_instalacion':
				case 'conf_reparacion':
				case 'conf_visita':
					popUp('admin/proyectos/confirmar.php',{act:act,cteId:cteId,pryId:pryId,vId:vId},function(){},{});
					break;
				case 'ver_instalacion':
				case 'ver_visita':
				case 'ver_reparacion':
					popUp('admin/proyectos/datosVisita.php',{act:act,cteId:cteId,vId:vId},function(){},{});
					break;
				case 'cancel_instalacion':
				case 'cancel_visita':
				case 'cancel_reparacion':
					popUp('admin/proyectos/cancelaVisita.php',{act:act,cteId:cteId,vId:vId},function(){},{});
					break;
				case 'ver_seguimientoTelAdd':
					// console.log('ver_seguimientoTelAdd');
					var vId = $(this).attr('vId');
					var cId = $(this).closest('tr').attr('id').split('_')[1];
					console.log(vId.length,typeof vId, cId);
					if(vId.length == 0){
						console.log('cId');
						var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'seguimientoTel',acc:2});
						console.log(rj);
						var r = $.parseJSON(rj);

						if(r.ok == 1){
							vId = r.nId;
						}
					}
					// console.log('vId',vId);
					popUpCuest('admin/proyectos/respCuest.php',{vId:vId},function(){})
					setTimeout(function(){
						$('#contentCuest').load(rz+'campo/checklist/cuestionario.php',{vId:vId},function(){});
					},500);



					break;
				default:
					break;
			}
			// console.log(act,cteId);

		});


		$('#bodyCtes').on('click', '.histRot', function(event) {
			event.preventDefault();
			var cteId = this.id.split('_')[1];
			// console.log(cteId);
			popUp('admin/proyectos/cHist.php',{cteId:cteId},function(){},{});
		});


		var estatus = <?php echo atj($estatus); ?>;
		optsSel(estatus,$('#estSel'),true,'');

		var estatusSumo = $('#estSel').SumoSelect({
			placeholder: 'Selecciona los estatus',
		});

		$('#bodyCtes').on('click', '.edtCte', function(event) {
			event.preventDefault();
			var cteId = this.id.split('_')[1];

			popUp('admin/proyectos/cteInfo.php',{eleId:cteId},function(){},{});
		});



		$('#estSel').change(function(event) {
			var estatus = $('#estSel').val();
			// console.log(estatus);

			if(estatus.length > 0){
				$('.cliente').hide();
				$('.cliente').hide();
				for(var i in estatus){
					$('.e'+estatus[i]).closest('tr').show();
				}

			}else{
				$('.cliente').show();
			}
		});

		$('.refrescaFiltro').click(function(event) {
			$('#estSel').trigger('change')
		});

		$('#filtrosRot').click(function(event) {
			var pryId = <?php echo $_POST['pryId']; ?>;
			popUp('admin/proyectos/filtrosShopper.php',{pryId:pryId},function(){});
		});

		

	});
</script>
<div class="nuevo mayusculas">Usuarios  <?php echo $pryInf['nombre']; ?></div>
<div class="row">
</div>
<div style="text-align: center;">

</div>

<table class="table" border="0" style="font-size: small;">
	<thead>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td><input type="text" id="busqNom" class="form-control" placeholder="buscar" /></td>
			<td></td>
			<td></td>
			<td>
				<select class="form-control" id="estSel" multiple="multiple" 
					style="margin-bottom: 5px;height: 30px;" height="10px"></select>
					<i class="glyphicon glyphicon-refresh manita refrescaFiltro"></i>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>

		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th>Nombre</th>
			<th>Colonia</th>
			<th style="text-align: center;">Ubicación</th>
			<th>Estatus</th>
			<th style="text-align: right;">Compromisos</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody id="bodyCtes">

	<?php 
		foreach ($clientes as $r){
	?>
		<tr class='cliente' id="tr_<?php echo $r['id']; ?>">
			<?php include raiz().'admin/proyectos/clienteFila.php'; ?>
		</tr>
	<?php } ?>
	</tbody>
</table>
<form id="downloadImgForm" method="post" action="proyectos/json/dwlFotos.php" target="_blank">
	<input type="hidden" name="cteId" id="cteId">
</form>


