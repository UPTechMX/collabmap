<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Fondeadores WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
		// print2($datC);
	}
?>

<?php

$nivel = $_SESSION['CM']['admin']['nivel'];
if($nivel<50){
	exit('No tienes acceso a esta área');
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		var subtipos = {};
		subtipos['Público'] = [
			{nom:"Federal",val:"Federal",clase:'clase'},
			{nom:"Estatal",val:"Estatal",clase:'clase'},
			{nom:"Local/Municipal",val:"Local/Municipal",clase:'clase'}
		];
		subtipos['Privado'] = [
			{nom:"Individuales",val:"Individuales",clase:'clase'},
			{nom:"ONG",val:"ONG",clase:'clase'},
		];

		$('#tipo').change(function(event) {
			optsSel(subtipos[$(this).val()],$('#subtipo'),false,'- - - Subtipo - - -',false);
		});


		$('.form-control').keydown(function(event) {
			if(!$(this).prop('disable'))
				$(this).css({backgroundColor:''});
			
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
			if(!$(this).prop('disable'))
				$(this).css({backgroundColor:''});
		});


		direcciones(estados,municipios,'nEmp');
		<?php if($_POST['eleId'] != ''){ ?>
			var datC = <?php echo atj($datC); ?>;

			$('#tipo').trigger('change');
			$('#subtipo').val(datC.subtipo);

			var codigoPostal = $('#codigoPostal').val();
			$('#proyectosId').val(datC.proyectosId);
			if(codigoPostal != ''){
				$('#codigoPostal').trigger('blur');
				if( $('#coloniaSel option[value="'+datC.colonia+'"]').length > 0 ){
					$('#coloniaSel').val(datC.colonia).trigger('change');
				}else{
					$('#coloniaSel').val('-1');
					$('#colonia').val(datC.colonia);
				}
			}else{
				$('#estadosId').val(datC.estadosId).trigger('change');
				$('#municipiosId').val(datC.municipiosId).trigger('change');
				if( $('#coloniaSel option[value="'+datC.colonia+'"]').length > 0 ){
					$('#coloniaSel').val(datC.colonia).trigger('change');
				}else{
					$('#coloniaSel').val('-1');
					$('#colonia').val(datC.colonia);
				}

			}
		<?php } ?>

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.estadosId = $('#estadosId').val();
			dat.municipiosId = $('#municipiosId').val();
			dat.colonia = $('#colonia').val();
			var allOk = camposObligatorios('#nEmp');

			if(dat.accesoWeb == "on")
				dat.accesoWeb=1;
			else
				dat.accesoWeb=0;

			if(dat.restringirMontos == "on")
				dat.restringirMontos=1;
			else
				dat.restringirMontos=0;
			
			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>

			// console.log(dat); return;

			if(allOk){
				var rj = jsonF('admin/administracion/financiadores/json/json.php',{datos:dat,acc:acc,opt:1});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#financiadoresList').load(rz+'admin/administracion/financiadores/financiadoresList.php',{ajax:1});
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
			financiador
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
				<td colspan="3">
					<input type="text" value="<?php echo $datC['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
			</tr>
			<tr>
				<td>Tipo</td>
				<td colspan="3">
					<select id="tipo" name="tipo" class="form-control">
						<option value="">- - - Tipo - - -</option>
						<option value="Público" <?php echo $datC['tipo'] == "Público"?'selected':''; ?> >Público</option>
						<option value="Privado" <?php echo $datC['tipo'] == "Privado"?'selected':''; ?> >Privado</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Subtipo</td>
				<td colspan="3">
					<select id="subtipo" name="subtipo" class="form-control">
						<option value="">- - - Subtipo - - -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Responsable / Enlace</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['responsable']; ?>" name="responsable" id="responsable" class="form-control oblig" >
				</td>
			</tr>
			<tr>
				<td>Cargo</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['cargo']; ?>" name="cargo" id="cargo" class="form-control oblig" >
				</td>
			</tr>
			<tr>
				<td>Calle</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['calle']; ?>" name="calle" id="calle" class="form-control " >
				</td>
			</tr>
			<tr>
				<td>Numero exterior</td>
				<td>
					<input type="text" value="<?php echo $datC['numeroExt']; ?>" name="numeroExt" id="numeroExt" class="form-control " >
				</td>
				<td>Numero interior</td>
				<td>
					<input type="text" value="<?php echo $datC['numeroInt']; ?>" name="numeroInt" id="numeroInt" class="form-control " >
				</td>
			</tr>
			<tr>
				<td>CP</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['codigoPostal']; ?>" name="codigoPostal" id="codigoPostal" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Estado</td>
				<td>
					<select name="estadosId" id="estadosId" class="form-control ">
						<option value="">- Estado -</option>
						
					</select>
				</td>
				<td>Municipio</td>
				<td>
					<select name="municipiosId" id="municipiosId" class="form-control ">
						<option >- Municipio -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Colonia</td>
				<td colspan="3" id="tdColonia">
					<select id="coloniaSel" class="form-control " style="display: none;">
					<input type="text" value="<?php echo $datC['colonia']; ?>" name="colonia" id="colonia" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Teléfono</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['telefono']; ?>" name="telefono" id="telefono" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Celular</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['celular']; ?>" name="celular" id="celular" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Correo electrónico</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['email']; ?>" name="email" id="email" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Username</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['username']; ?>" name="username" id="username" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Clave de acceso</td>
				<td colspan="3">
					<input type="text" value="<?php echo $datC['pwd']; ?>" name="pwd" id="pwd" class="form-control" >
				</td>

			</tr>
			<tr>
				<td>Acceso Web</td>
				<td colspan="3">
					<input type="checkbox" <?php echo $datC['accesoWeb'] == 1 ? 'checked' : ''; ?> name="accesoWeb" id="accesoWeb" class="form-control" >
				</td>
			</tr>
			<tr>
				<td>Restingir visualización monetaria</td>
				<td colspan="3">
					<input type="checkbox" <?php echo $datC['restringirMontos'] == 1 ? 'checked' : ''; ?> name="restringirMontos" id="restringirMontos" class="form-control" >
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
