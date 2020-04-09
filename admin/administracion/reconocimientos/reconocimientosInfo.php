<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT r.*,u.nombre as uNom, u.aPat as uaPat, u.aMat as uaMat 
			FROM Reconocimientos r
			LEFT JOIN usrAdmin u ON u.id = r.usuariosId
			WHERE r.id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		// print2($datC);
	}

	$medios = $db->query("SELECT m.id as mId, m.nombre as mNom, tm.id as tmId, tm.nombre as tmNom, 
		tm.manejo as manejo, mm.nombre as mmNom, tm.manejo, mm.id as mmId
		FROM Medios m
		LEFT JOIN TipoMedios tm ON tm.mediosId = m.id
		LEFT JOIN manejoMedios  mm ON mm.tipoMediosId = tm.id
	")->fetchAll(PDO::FETCH_ASSOC);

	$meds = array();
	foreach ($medios as $m) {
		$meds[$m['mNom']]['id'] = $m['mId'];
		$meds[$m['mNom']][$m['tmNom']]['id'] = $m['tmId'];
		$meds[$m['mNom']][$m['tmNom']]['manejo'] = $m['manejo'];
		$meds[$m['mNom']][$m['tmNom']]['datos'][] = $m;

	}

	$ubicaciones = $db->query("SELECT id as val, nombre as nom, 'clase' as class FROM Ubicaciones ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
	$lideres = $db->query("SELECT id as val, CONCAT(IFNULL(aPat,''),' ',IFNULL(aMat,''),', ', IFNULL(nombre,'')) as nom, 
		'clase' as class FROM LideresComunitarios ORDER BY aPat, aMat, nombre")->fetchAll(PDO::FETCH_ASSOC);

	// $ru = $db->query("SELECT * FROM ")
	$mediosRec = $db->query("SELECT * FROM ReconocimientoMedios WHERE reconocimientosId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);
	// print2($mediosRec);


?>

<?php

$nivel = $_SESSION['IU']['admin']['nivel'];
if($nivel<50){
	exit('No tienes acceso a esta área');
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		var datC = <?php echo atj($datC); ?>;

		 $('.verArea').click(function(event) {
			$(this).siblings('.area').toggle();
			if($(this).siblings('.area').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});


		// colonias

		direcciones(estados,municipios,'divCols');
		if(datC.codigoPostal != ''){
			// console.log(datC);
			$('#divCols #codigoPostal').trigger('blur');
			if( $('#divCols #coloniaSel option[value="'+datC.colonia+'"]').length > 0 ){
				$('#divCols #coloniaSel').val(datC.colonia).trigger('change');
			}else{
				$('#divCols #coloniaSel').val('-1');
				$('#divCols #colonia').val(datC.colonia);
			}
		}else{
			// console.log('bbb');
			$('#divCols #estadosId').val(datC.estadosId).trigger('change');
			$('#divCols #municipiosId').val(datC.municipiosId).trigger('change');
			if( $('#divCols #coloniaSel option[value="'+datC.colonia+'"]').length > 0 ){
				$('#divCols #coloniaSel').val(datC.colonia).trigger('change');
			}else{
				$('#divCols #coloniaSel').val('-1');
				$('#divCols #colonia').val(datC.colonia);
			}

		}

		// $('#estadosId').val(<?php echo $datC['estadosId']; ?>).trigger('change');;
		// $('#municipiosId').val(<?php echo $datC['municipiosId']; ?>).trigger('change');
		$('#addRecCol').click(function(event) {
			var dat = {};
			dat.id = <?php echo $_POST['eleId']; ?>;
			dat.colonia = $('#colonia').val().trim();
			dat.estadosId = $('#estadosId').val();
			dat.municipiosId = $('#municipiosId').val();
			dat.territorial = $('#territorial').val();
			dat.barrio = $('#barrio').val();
			dat.precariedad = $('#precariedad').val();
			dat.codigoPostal = $('#codigoPostal').val();

			// allOk = true;
			// console.log(dat.colonia);
			// $.each($('#pryCols .colNom'), function(index, val) {
			// 	 if(dat.colonia == $(this).text()){
			// 	 	allOk = false;
			// 	 	alertar('Ya existe esta colonia en el proyecto',function(){},{});
			// 	 }
			// });
			// if(!allOk){
			// 	return;
			// }

			if(dat.colonia != '' && dat.estadosId != ''&& dat.municipiosId != ''){
				var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{datos:dat,acc:2,opt:1});
				try{
					var r = $.parseJSON(rj);
				}catch(e){
					console.log('error de parseo');
					console.log(rj);
					var r = {ok:0};
				}

				if(r.ok == 1){
					alertar('Los datos han sido guardados correctamente',function(){},{})
					// $('#pryColsList').load(rz+'admin/administracion/reconocimientos/recColsList.php',{eleId:dat.reconocimientosId});
				}
			}else{
				alertar('Los datos de estado, municipio y colonia no pueden estar vacíos',function(){},{})
			}
		});

		// Medios

		datMed = <?php echo atj($mediosRec); ?>;
		for(var i in datMed){
			var dm = datMed[i];
			// console.log(dm);
			// console.log('#medio_'+dm.mediosId+'_'+dm.tipoMediosId+'_'+dm.manejoMediosId)
			var chk = $('#medio_'+dm.mediosId+'_'+dm.tipoMediosId+'_'+dm.manejoMediosId);
			chk.prop('checked',dm.valor == 1);
		}

		$('.chkMed').change(function(event) {
			$('#cMed').show();
		});

		$('#gMed').click(function(event) {
			$('#gMed').hide();
			var recId = <?php echo $_POST['eleId']; ?>;
			datMed = [];
			$.each($('.chkMed'), function(index, val) {
				var ids =$(this).attr('id').split('_');
				var tmp = {};
				tmp.mediosId = ids[1];
				tmp.tipoMediosId = ids[2];
				tmp.manejoMediosId = ids[3];
				tmp.valor = $(this).is(':checked')?1:0;
				tmp.reconocimientosId = recId;
				datMed.push(tmp);
				
			});

			// console.log(datMed);
			
			var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{acc:4,datos:datMed,recId:recId});
			try{
				var r = $.parseJSON(rj);
				// console.log(rj);
			}catch(e){
				console.log('error de parseo');
				console.log(rj);
				var r = {ok:0,err:'error de parseo'};
			}

			if(r.ok == 1){
				alertar('La información ha sido guardada correctamente',function(){},{});
				$('#gMed').show();
				$('#cMed').hide();
			}else{
				alertar('Hubo un error al guardar la información<br/>'+r.err,function(){},{});
				$('#gMed').show();
			}

			// console.log(datMed);
		});

		$('#cMed').click(function(event) {
			for(var i in datMed){
				var dm = datMed[i];
				// console.log(dm);
				// console.log('#medio_'+dm.mediosId+'_'+dm.tipoMediosId+'_'+dm.manejoMediosId)
				var chk = $('#medio_'+dm.mediosId+'_'+dm.tipoMediosId+'_'+dm.manejoMediosId);
				chk.prop('checked',dm.valor == 1);
			}
			$('#cMed').hide();
		});

		var obs = new Quill('#textEditor', {
		  theme: 'snow'
		});

		$("#addMedPI").click(function(event) {
			$('#addMedPI').hide();

			var dat = {};
			dat.medio = $('#medioPI').val();
			dat.observaciones = obs.root.innerHTML.trim();
			dat.reconocimientosId = <?php echo $_POST['eleId']; ?>;

			if(dat.medio == ''){
				alertar('Debes seleccionar un medio',function(){},{});
				$('#addMedPI').show();
				return;
			}

			var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{datos:dat,opt:2,acc:1})
			// console.log(dat);
			try{
				var r = $.parseJSON(rj);
				// console.log(rj);
			}catch(e){
				console.log('error de parseo');
				console.log(rj);
				var r = {ok:0,err:'error de parseo'};
			}
			if(r.ok == 1){
				alertar('La información ha sido guardada correctamente',function(){},{});
				$('#recMedsList').load(rz+'admin/administracion/reconocimientos/recMedsList.php',{eleId:dat.reconocimientosId});
				$('#addMedPI').show();
				obs.setText('');
				$('#medioPI').val('');
			}else{
				alertar('Hubo un error al guardar la información<br/>'+r.err,function(){},{});
				$('#addMedPI').show();
			}
		});

		// Observaciones

		var observaciones = new Quill('#observaciones', {
		  theme: 'snow'
		});

		$('#gObs').click(function(event) {
			var dat = {};
			dat.id = <?php echo $_POST['eleId']; ?>;
			dat.observaciones = observaciones.root.innerHTML.trim();

			var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{datos:dat,opt:1,acc:2})

			try{
				var r = $.parseJSON(rj);
				// console.log(rj);
			}catch(e){
				console.log('error de parseo');
				console.log(rj);
				var r = {ok:0,err:'error de parseo'};
			}

			if(r.ok == 1){
				alertar('La información ha sido guardada correctamente',function(){},{});
			}
		});

		// Ubicaciones

		var ubicaciones = <?php echo atj($ubicaciones); ?>;
		// optsSel(ubicaciones,$('#ubicacionesId'),false,'- Ubicacion -',false);


		$('#addRecUbic').click(function(event) {
			var dat = {};
			dat.reconocimientosId = <?php echo $_POST['eleId']; ?>;
			dat.ubicacionesId = $("#ubicacionesId").val();

			// console.log(dat.ubicacionesId,$('#ubic_'+dat.ubicacionesId).length);
			if($('.ubic_'+dat.ubicacionesId).length > 0){
				alertar('El sitio de interés ya está dada de alta en este reconocimiento',function(){},{});
			}else{			
				if(dat.ubicacionesId != '' ){
					var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{datos:dat,acc:1,opt:5});
					try{
						var r = $.parseJSON(rj);
					}catch(e){
						console.log('error de parseo');
						console.log(rj);
						var r = {ok:0};
					}
					console.log(rj);
					if(r.ok == 1){
						// alertar('Los datos han sido guardados correctamente',function(){},{})
						$('#recUbicList').load(rz+'admin/administracion/reconocimientos/recUbicList.php',{eleId:dat.reconocimientosId});
					}
				}else{
					alertar('Debes seleccionar una ubicacion.',function(){},{})
				}
			}
		});

		$('#verUbic').click(function(event) {
			var ubicId = $("#ubicacionesId").val();
			if(ubicId != ''){

				popUp('general/ubicaciones/ubicacionesAdd.php',{eleId:ubicId,readonly:1},function(){
					$('<div>').attr({class:'modal-header nuevo',id:'headerMod'})
					.insertAfter( "#encabezado" )
					.html(`
						<div style="text-align: center;">
							<h4 class="modal-title">Sitio de interés</h4>
						</div>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					`);
					$("#encabezado").remove();
					$('<div>').attr({class:'modal-footer',id:'pieMod'})
					.insertAfter( "#pie" )
					.html(`
						<span id="cancelM" data-dismiss="modal" class="btn btn-sm btn-shop">Salir</span>
					`);

					$("#pie").remove();
				},{});
			}else{
				alertar('Debes seleccionar una ubicacion.',function(){},{})
			}
		});

		$('#ubicAdd').click(function(event) {

			var actividad = `
				$('#popUp').modal('toggle');
				var o = new Option($('#fUbic #nombre ').val(),r.nId );
				$('#ubicaciones #ubicacionesId').append(o);
				$('#ubicaciones #ubicacionesId').val(r.nId);
				$('#ubicaciones #addRecUbic').trigger('click');
				$('#ubicacionesId').selectpicker('refresh');

			`;

			popUp('general/ubicaciones/ubicacionesAdd.php',{actividad:actividad},function(){
				$('<div>').attr({class:'modal-header nuevo',id:'headerMod'})
				.insertAfter( "#encabezado" )
				.html(`
					<div style="text-align: center;">
						<h4 class="modal-title">Sitio de interés</h4>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				`);
				$("#encabezado").remove();
				$('<div>').attr({class:'modal-footer',id:'pieMod'})
				.insertAfter( "#pie" );
				$('#pie').appendTo('#pieMod')
				$('#cancelUbic').remove();
				$('#pie').prepend(`
					<span id="cancelUbicc" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
				`);
			},{});
		});

		$('#ubicacionesId').selectpicker();



		// Líderes comunitarios

		var lideres = <?php echo atj($lideres); ?>;
		// optsSel(ubicaciones,$('#lideresId'),false,'- Ubicacion -',false);


		$('#addRecLider').click(function(event) {
			var dat = {};
			dat.reconocimientosId = <?php echo $_POST['eleId']; ?>;
			dat.lideresId = $("#lideresId").val();

			// console.log(dat.lideresId,$('#ubic_'+dat.lideresId).length);
			if($('.lider_'+dat.lideresId).length > 0){
				alertar('El líder comunitario ya está dado de alta en este reconocimiento',function(){},{});
			}else{			
				if(dat.lideresId != '' ){
					var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{datos:dat,acc:1,opt:6});
					try{
						var r = $.parseJSON(rj);
					}catch(e){
						console.log('error de parseo');
						console.log(rj);
						var r = {ok:0};
					}
					console.log(rj);
					if(r.ok == 1){
						// alertar('Los datos han sido guardados correctamente',function(){},{})
						$('#recUbicList').load(rz+'admin/administracion/reconocimientos/recLiderList.php',{eleId:dat.reconocimientosId});
					}
				}else{
					alertar('Debes seleccionar un líder comunitario.',function(){},{})
				}
			}
		});

		$('#verLider').click(function(event) {
			var liderId = $("#lideresId").val();
			if(liderId != ''){

				popUp('general/ubicaciones/lideresAdd.php',{eleId:liderId,readonly:1},function(){
					$('<div>').attr({class:'modal-header nuevo',id:'headerMod'})
					.insertAfter( "#encabezado" )
					.html(`
						<div style="text-align: center;">
							<h4 class="modal-title">Líder comunitario</h4>
						</div>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					`);
					$("#encabezado").remove();
					$('<div>').attr({class:'modal-footer',id:'pieMod'})
					.insertAfter( "#pie" )
					.html(`
						<span id="cancelM" data-dismiss="modal" class="btn btn-sm btn-shop">Salir</span>
					`);

					$("#pie").remove();
				},{});
			}else{
				alertar('Debes seleccionar una ubicacion.',function(){},{})
			}
		});

		$('#addLider').click(function(event) {

			var actividad = `
				$('#popUp').modal('toggle');
				var o = new Option($('#nCte #nombre ').val()+' '+$('#nCte #aPat ').val()+' '+$('#nCte #aMat').val(),r.nId );
				$('#lideres #lideresId').append(o);
				$('#lideres #lideresId').val(r.nId);
				$('#lideres #addRecUbic').trigger('click');
				$('#lideresId').selectpicker('refresh');
			`;

			popUp('general/ubicaciones/lideresAdd.php',{actividad:actividad},function(){
				$('<div>').attr({class:'modal-header nuevo',id:'headerMod'})
				.insertAfter( "#encabezado" )
				.html(`
					<div style="text-align: center;">
						<h4 class="modal-title">Sitio de interés</h4>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				`);
				$("#encabezado").remove();
				$('<div>').attr({class:'modal-footer',id:'pieMod'})
				.insertAfter( "#pie" );
				$('#pie').appendTo('#pieMod')
				$('#cancelLider').remove();
				$('#pie').prepend(`
					<span id="cancelUbicc" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
				`);
			},{});
		});

		$('#lideresId').selectpicker();
		


	});
</script>

<div class="nuevo" style="text-align: center;">
	Datos del reconocimiento <strong><?php echo $datC['nombre']; ?></strong>
</div>

<br/>
<table class="table" style="font-family: Courier;">
	<tr>
		<td>Nombre</td>
		<td>
			<?php echo "$datC[nombre] "; ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>Usuario que realizó el reconocimiento</td>
		<td>
			<?php echo "$datC[uNom] $datC[uaPat] $datC[uaMat]"; ?>
		</td>
		<td></td>
	</tr>
</table>
<div>
	<div class="nuevo barra manita verArea">
		Colonias de instalación <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display: none;" id="divCols">
		<div style="margin:10px;">
			<table class="table">
				<tr>
					<td>CP</td>
					<td colspan="3">
						<input type="text" value="<?php echo $datC['codigoPostal']; ?>" name="codigoPostal" id="codigoPostal" class="form-control" >
					</td>
				</tr>
				<tr>
					<td width="15%">Estado</td>
					<td width="35%">
						<select name="estadosId" id="estadosId" class="form-control">
							<option value="">- Estado -</option>
							<?php foreach ($edos as $e){ ?>
								<option value="<?php echo $e['id']; ?>"><?php echo $e['nombre']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td width="15%">Municipio</td>
					<td width="35%">
						<select name="municipiosId" id="municipiosId" class="form-control">
							<option >- Municipio -</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Colonia</td>
					<td colspan="3" id="tdColonia">
						<select id="coloniaSel" class="form-control " style="display: none;"></select>
						<input type="text"  name="colonia" value="<?php echo $datC['colonia']; ?>" id="colonia" class="form-control" >
					</td>
				</tr>
				<tr>
					<td>Territorial</td>
					<td colspan="3" >
						<input type="text"  name="territorial" value="<?php echo $datC['territorial']; ?>" id="territorial" class="form-control" >
					</td>
				</tr>
				<tr>
					<td>Barrio</td>
					<td colspan="3" >
						<input type="text"  name="barrio" value="<?php echo $datC['barrio']; ?>" id="barrio" class="form-control" >
					</td>
				</tr>
				<tr>
					<td>Precariedad hidrica</td>
					<td colspan="3" >
						<select id="precariedad" id="precariedad" class="form-control " >
							<option value="">- Precariedad hidrica -</option>
							<option value="Muy alta"  <?php echo $datC['precariedad'] == "Muy alta"?"selected":""; ?>>Muy alta</option>
							<option value="Alta"  <?php echo $datC['precariedad'] == "Alta"?"selected":""; ?>>Alta</option>
							<option value="Media"  <?php echo $datC['precariedad'] == "Media"?"selected":""; ?>>Media</option>
							<option value="Baja"  <?php echo $datC['precariedad'] == "Baja"?"selected":""; ?>>Baja</option>
							<option value="Muy baja"  <?php echo $datC['precariedad'] == "Muy baja"?"selected":""; ?>>Muy baja</option>
							<option value="Revisar"  <?php echo $datC['precariedad'] == "Revisar"?"selected":""; ?>>Revisar</option>
						</select>
					</td>
				</tr>
			</table>
			<div style="text-align: right;">
				<span class="btn btn-sm btn-shop" id="addRecCol">Guardar información</span>
			</div>
		</div>
	</div>
</div>

<div>
	<div class="nuevo barra manita verArea">
		Medios de comunicacion <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display:none">
		<div>
			<?php foreach ($meds as $m => $tm){ ?>
				<div>
					<div style="text-align: center;">
						<strong><?php echo $m; ?></strong>
						<div class="row justify-content-between" style="text-align: left;">
							<?php 
							foreach ($tm as $t => $tipo){ 
								if($t == 'id'){continue;}
							?>
								<div class="col-4" style="">
									<?php if (empty($tipo['manejo'])){ ?>
										<input type="checkbox" class="chkMed"
											id="medio_<?php echo $tm['id']."_".$tipo['id']."_0";  ?>" />
										
									<?php } ?>
									<?php echo $t ?>
									<?php if (!empty($tipo['manejo'])){ ?>
										<div>
											<?php foreach ($tipo['datos'] as $m => $d){ ?>
												<div>
													<input type="checkbox" class="chkMed"
														id="medio_<?php echo $tm['id']."_".$tipo['id']."_".$d['mmId'];  ?>" />
													<?php echo $d['mmNom'] ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
						<hr/>
					</div>
				</div>
			<?php } ?>
		</div>
		<div style="text-align: right;">
			<span class="btn btn-sm btn-cancel" id="cMed" style="display: none;">Cancelar</span>
			<span class="btn btn-sm btn-shop" id="gMed">Guardar medios</span>
		</div>
		<hr style="border-top: solid 2px black;width: 100%" />
		<div class="nuevo" style="background-color: whitesmoke;margin-top: 15px;">
			¿Qué medios de comunicación crees que se pueden implementar?
		</div>
		<div class="row">
			<div class="col-6" style="text-align: right;">Medio:</div>
			<div class="col-6" >
				<select id="medioPI" id="medioPI" class="form-control " >
					<option value="">- - Medio - - </option>
					<option value="Televisión">Televisión</option>
					<option value="Radio">Radio</option>
					<option value="Periódico">Periódico</option>
					<option value="Redes Sociales">Redes Sociales</option>
					<option value="Megáfono a pie">Megáfono a pie</option>
					<option value="Megáfono rodante">Megáfono rodante</option>
					<option value="Manta">Manta</option>
					<option value="Mural">Mural</option>
					<option value="Anuncio en barda">Anuncio en barda</option>
					<option value="Folletos">Folletos</option>
					<option value="Carteles">Carteles</option>
					<option value="Otro">Otro</option>
				</select>
			</div>
		</div>

		<div id="textEditor" style="height: 200px;"></div>
		<div style="text-align: right;margin: 10px 0px;">
			<span class="btn btn-sm btn-shop" id="addMedPI">Agregar</span>
		</div>
		<div id="recMedsList"><?php include_once 'recMedsList.php' ?></div>
	</div>
</div>

<div>
	<div class="nuevo barra manita verArea">
		Observaciones <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display: none;">
		<div id="observaciones" style="height: 200px;"><?php echo $datC['observaciones']; ?></div>
		<div style="text-align: right;margin: 10px 0px;">
			<span class="btn btn-sm btn-shop" id="gObs">Guardar</span>
		</div>

	</div>
</div>

<div>
	<div class="nuevo barra manita verArea">
		Líderes comunitarios <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display:none;" id="lideres">
		<div style="margin:10px 0px;">
			<div class="row">
				<div class="col-5">
					<select class="form-control" id="lideresId" data-live-search="true">
						<option value="">- Lider comunitario -</option>
						<?php foreach ($lideres as $u){ ?>
							<option value="<?php echo $u['val']; ?>"><?php echo $u['nom']; ?></option>
						<?php } ?>
					</select>

				</div>
				<div class="col-7">
					<span  class="btn btn-sm btn-shop" id="verLider">Ver lider comunitario</span>
					<span  class="btn btn-sm btn-shop" id="addLider">Nuevo lider comunitario</span>
				</div>
			</div>
			<div style="text-align: left;margin: 10px 0px;">
				<span class="btn btn-sm btn-shop" id="addRecLider">Agregar lider comunitario</span>
			</div>
		</div>
		<div id="recUbicList"><?php include_once 'recLiderList.php'; ?></div>
	</div>
</div>

<div>
	<div class="nuevo barra manita verArea">
		Sitios de interés <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display:none;" id="ubicaciones">
		<div style="margin:10px 0px;">
			<div class="row">
				<div class="col-5">
					<select class="form-control" id="ubicacionesId" data-live-search="true">
						<option value="">- Ubicacion -</option>
						<?php foreach ($ubicaciones as $u){ ?>
							<option value="<?php echo $u['val']; ?>"><?php echo $u['nom']; ?></option>
						<?php } ?>
					</select>

				</div>
				<div class="col-7">
					<span  class="btn btn-sm btn-shop" id="verUbic">Ver sitio de interés</span>
					<span  class="btn btn-sm btn-shop" id="ubicAdd">Nuevo sitio de interés</span>
				</div>
			</div>
			<div style="text-align: left;margin: 10px 0px;">
				<span class="btn btn-sm btn-shop" id="addRecUbic">Agregar sitio de interés</span>
			</div>
		</div>
		<div id="recUbicList"><?php include_once 'recUbicList.php'; ?></div>
	</div>
</div>


