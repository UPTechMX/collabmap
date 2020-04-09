<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$datC = $db-> query("SELECT * FROM Proyectos WHERE id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$cuestionarios = $db->query("SELECT c.etapa as cEtapa, pc.checklistId as sel, c.* 
		FROM Checklist c
		LEFT JOIN ProyectosChecklist pc ON pc.checklistId = c.id AND pc.proyectosId = $_POST[eleId]
		WHERE (c.elim = 0 OR c.elim IS NULL)")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$edos = $db->query("SELECT * FROM Estados")->fetchAll(PDO::FETCH_ASSOC);

	// print2($cuestionarios);
	$etapas = $db->query("SELECT * FROM Etapas ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>


<script type="text/javascript">
	$(document).ready(function() {

		var pryId = <?php echo $_POST['eleId']; ?>;
		subArch($('#chImgPry'),9,'imgPry_'+pryId+'_','jpg,img,gif',false,function(e){
			console.log(e)
			var pryId = <?php echo $_POST['eleId']; ?>;
			var dat = {id:pryId,logotipo:e.prefijo+e.nombreArchivo};
			var rj = jsonF('admin/administracion/proyectos/json/json.php',{'datos':dat,acc:2,opt:1});
			try{
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					console.log(e.prefijo,e.nombreArchivo);
					$('#imgPry').attr({src:'../archivos/imgPry/'+e.prefijo+e.nombreArchivo}).show();
				}
			}catch(e){
				console.log('error de parseo');
				console.log(rj);
			}
			// console.log(rj);
		})

		// Fondeadores

		$('#addPryFond').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			popUp('admin/administracion/proyectos/pryFondAdd.php',{pryId:pryId},function(){},{});
		});

		$("#verFond").click(function(event) {
			$('#divFond').toggle();
			if($('#divFond').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});



		// Instalaciones


		$('#addPryInst').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			popUp('admin/administracion/proyectos/pryInstAdd.php',{pryId:pryId},function(){},{});
		});


		$("#verInst").click(function(event) {
			$('#divInst').toggle();
			if($('#divInst').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});

		// Colonias

		direcciones(estados,municipios,'divCols')

		$('#addPryCol').click(function(event) {
			var dat = {};
			dat.proyectosId = <?php echo $_POST['eleId']; ?>;
			dat.colonia = $('#colonia').val().trim();
			dat.estadosId = $('#estadosId').val();
			dat.municipiosId = $('#municipiosId').val();
			dat.codigoPostal = $('#codigoPostal').val();

			allOk = true;
			// console.log(dat.colonia);
			$.each($('#pryCols .colNom'), function(index, val) {
				 if(dat.colonia == $(this).text()){
				 	allOk = false;
				 	alertar('Ya existe esta colonia en el proyecto',function(){},{});
				 }
			});
			if(!allOk){
				return;
			}

			if(dat.colonia != '' && dat.estadosId != ''&& dat.municipiosId != '' && dat.codigoPostal != ''){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:1,opt:4});
				try{
					var r = $.parseJSON(rj);
				}catch(e){
					console.log('error de parseo');
					console.log(rj);
					var r = {ok:0};
				}

				if(r.ok == 1){
					$('#pryColsList').load(rz+'admin/administracion/proyectos/pryColsList.php',{eleId:dat.proyectosId});
				}
			}else{
				alertar('Los datos de estado, municipio, colonia y CP no pueden estar vacíos',function(){},{})
			}
		});

		$("#verCols").click(function(event) {
			$('#divCols').toggle();
			if($('#divCols').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});

		// Ubicaciones
		$('#addPryUbic').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			popUp('admin/administracion/proyectos/pryUbicAdd.php',{pryId:pryId},function(){},{});
		});


		$("#verUbic").click(function(event) {
			$('#divUbic').toggle();
			if($('#divUbic').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});

		// Cuestionarios

		$("#verCuest").click(function(event) {
			$('#divCuest').toggle();
			if($('#divCuest').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});

		$('#gCuest').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			var dat = {};
			$.each($('.cuestSel'), function(index, val) {
				if($(this).val() != ''){
					dat[$(this).attr('id')] = $(this).val();
				}
			});

			// console.log({datos:dat,acc:8,opt:12,pryId:pryId});
			var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:4,opt:7,pryId:pryId});
			try{
				var r = $.parseJSON(rj);
			}catch(e){
				console.log('error de parseo');
				console.log(rj);
				var r = {ok:0};
			}

			if(r.ok == 1){
				alertar('La información fue guardada correctamente',function(){},{});
			}else{
				console.log(r);
			}

		});

	});
</script>

<div class="nuevo">Datos del proyecto <strong><?php echo $datC['nombre']; ?></strong></div>
<table class="table" style="font-family: Courier;">
	<tr>
		<td>Nombre:</td>
		<td><?php echo $datC['nombre']; ?></td>
	</tr>
	<tr>
		<td>Periodo:</td>
		<td><?php echo "Del <strong>$datC[fIni]</strong> al <strong>$datC[fFin]</strong>"; ?></td>
	</tr>
	<tr>
		<td>Monto asignado:</td>
		<td><?php echo "\$ ".number_format($datC['presupuesto'],2); ?></td>
	</tr>
	<tr>
		<td>Meta mínima:</td>
		<td><?php echo "$datC[metaMinima]"; ?></td>
	</tr>
	<tr>
		<td>Meta final:</td>
		<td><?php echo "$datC[metaFinal]"; ?></td>
	</tr>
	<tr>
		<td>Imágen</td>
		<td>
			<img src="../archivos/imgPry/<?php echo $datC['logotipo'];?>" width="200px" id="imgPry">
			<?php if (!empty($datC['logotipo'])){ ?>
				<br/>
				<span id="chImgPry" >Cambiar imágen</span>
			<?php }else{ ?>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#imgPry').hide();
					});
				</script>
				<span id="chImgPry">Agregar imágen</span>
			<?php } ?>
		</td>
	</tr>
</table>
<div class="nuevo barra manita" id="verFond">
	Financiadores <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
</div>
<div id="divFond" style="display: none;">
	<div style="margin:10px;">
		<span class="btn btn-sm btn-shop" id="addPryFond">Agregar financiador</span>
	</div>

	<div id="pryFondList"><?php include_once 'pryFondList.php'; ?></div>
</div>

<div class="nuevo barra manita" id="verInst">
	Instalaciones <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
</div>
<div id="divInst" style="display: none;">
	
	<div style="margin:10px;">
		<span class="btn btn-sm btn-shop" id="addPryInst">Agregar instalacion</span>
	</div>
	<div id="pryInstList"><?php include_once 'pryInstList.php'; ?></div>

</div>

<div class="nuevo barra manita" id="verCols">
	Colonias de instalación <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
</div>
<div id="divCols" style="display: none;">
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
					<select id="coloniaSel" class="form-control " style="display: none;">
					<input type="text"  name="colonia" id="colonia" class="form-control" >
				</td>
			</tr>
		</table>
		<div style="text-align: right;">
			<span class="btn btn-sm btn-shop" id="addPryCol">Agregar Colonia</span>
		</div>
	</div>
	<div id="pryColsList"><?php include_once 'pryColsList.php'; ?></div>
</div>

<div class="nuevo barra manita" id="verUbic">
	Ubicaciones para Actividades
	<span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
</div>
<div id="divUbic" style="display:none;">
	
	<div style="margin:10px;">
		<span class="btn btn-sm btn-shop" id="addPryUbic">Agregar ubicación</span>
	</div>
	<div id="pryUbicList"><?php include_once 'pryUbicList.php'; ?></div>

</div>

<div class="nuevo barra manita" id="verCuest">
	Cuestionarios del proyecto
	<span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
</div>
<div id="divCuest" style="display:none;">
	<table class="table">
		<?php foreach ($etapas as $e){ ?>
			<tr>
				<td><?php echo $e['nombre'] ?></td>
				<td>
					<select class="form-control cuestSel" id="<?php echo $e['nomInt']; ?>">
						<option value="">- - <?php echo $e['nombre'] ?>- -</option>
						<?php foreach ($cuestionarios[$e['nomInt']] as $c){ ?>
							<option value="<?php echo $c['id']; ?>" <?php echo empty($c['sel'])?'':'selected'; ?>>
								<?php echo $c['nombre']; ?>
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
		<?php } ?>
	</table>
	<div style="text-align: right;">
		<span class="btn btn-sm btn-shop" id="gCuest">Guardar</span>
	</div>

</div>


