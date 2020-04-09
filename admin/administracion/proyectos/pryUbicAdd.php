<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);




	$tipos = $db->query("SELECT DISTINCT(tipoUbicacion) as tipo FROM Ubicaciones ORDER BY FIELD(tipo, 'Otro'), tipo")->fetchAll(PDO::FETCH_ASSOC);
	$ubicaciones = $db->query("SELECT tipoUbicacion as tu, nombre as nom, id as val, 'clase' as clase 
		FROM Ubicaciones ORDER BY nombre ")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT pu.tipoUbicacion, pu.* 
			FROM ProyectosUbicaciones pu WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	}

	// print2($tipos);
	// print2($ubicaciones);

?>

<?php

$nivel = $_SESSION['IU']['admin']['nivel'];
if($nivel<50){
	exit('No tienes acceso a esta área');
}

?>

<script type="text/javascript">
	$(document).ready(function() {


		var ubicaciones = <?php echo atj($ubicaciones); ?>;

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


		$('#tipoUbicacion').change(function(event) {
			var tipo = $(this).val();
			optsSel(ubicaciones[tipo],$('#ubicacionesId'),false,'- - - Selecciona una ubicacion - - -',false);
			$('#trUbic').show();
		});



		$('#env').click(function(event) {

			var allOk = camposObligatorios('#nEmp');
			var eleId = <?php echo $_POST['pryId']; ?>

			var dat = $('#nEmp').serializeObject();
			dat.proyectosId = eleId;

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>


			if(allOk){
				// para guardar a ProyectosUbicaciones
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:acc,opt:5});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#pryUbicList').load(rz+'admin/administracion/proyectos/pryUbicList.php',{eleId:eleId});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			Agregar ubicación
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
				<td>Tipo</td>
				<td>
					<select class="form-control" id="tipoUbicacion" >
						<option value="">- - - Selecciona un tipo de ubicacion - - -</option>
						<?php foreach ($tipos as $t){ ?>
							<option value="<?php echo $t['tipo']; ?>"><?php echo $t['tipo']; ?></option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr id="trUbic" style="display:none;">
				<td>Ubicaciones</td>
				<td>
					<select class="form-control oblig selOblig" id="ubicacionesId" name="ubicacionesId">
						<option value="">- - - Selecciona una ubicacion - - -</option>
					</select>
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
