<?php  

	Include_once '../../../lib/j/j.func.php';
	checaAcceso(60);
	// print2($_POST);

	$uNivel = $db->query("SELECT nivel FROM usrAdmin WHERE id = $_POST[usrId]")->fetchAll(PDO::FETCH_NUM)[0][0];
	$proyectos = $db->query("SELECT *  FROM Proyectos ")->fetchAll(PDO::FETCH_ASSOC);

	// print2($proyectos);

?>

<script type="text/javascript">
	$(document).ready(function() {

		var proyectos = <?php echo atj($proyectos); ?>;
		// console.log(proyectos);

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		}).change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('.form-control').keydown(function(event) {
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});


		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.usradminId = <?php echo $_POST['usrId']; ?>;
			dat.rol = <?php echo $uNivel; ?>;
			var allOk = camposObligatorios('#nEmp');

			if(allOk){
				var existe = $('#tr_'+dat.proyectosId).length;
				if(existe > 0){
					allOk = false;
					alertar('El proyecto seleccionado ya está asignado a este usuario.',function(){},{});
				}
			}
			// console.log('a');
			if(allOk){
				// console.log(dat);
				var rj = jsonF('admin/administracion/usuarios/json/json.php',{datos:dat,acc:1,opt:2});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#proyectosList').load(rz+'admin/administracion/usuarios/proyectosList.php',{ajax:1,usrId:<?php echo $_POST['usrId']; ?>});
				}
			}
		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Agregar proyecto al usuario</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td style="vertical-align: middle;">Proyecto</td>
				<td>
					<select class="form-control oblig selOblig" name="proyectosId" id="proyectosId">
						<option value="">- - Selecciona un proyecto - -</option>
						<?php foreach ($proyectos as $p){ ?>
							<option value="<?php echo $p['id']; ?>"><?php echo $p['nombre']; ?></option>
						<?php } ?>
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
