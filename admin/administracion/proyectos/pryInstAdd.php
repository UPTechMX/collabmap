<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);


$nivel = $_SESSION['IU']['admin']['nivel'];
if($nivel<50){
	exit('No tienes acceso a esta área');
}
if($_POST['eleId'] != ''){
	$datC = $db-> query("SELECT * FROM Instalaciones WHERE id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];
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



		$('#env').click(function(event) {

			var allOk = camposObligatorios('#nInst');
			var eleId = <?php echo $_POST['pryId']; ?>

			var dat = $('#nInst').serializeObject();
			dat.proyectosId = eleId;


			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>

			// console.log(dat);

			if(allOk){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:acc,opt:3});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#pryInstList').load(rz+'admin/administracion/proyectos/pryInstList.php',{eleId:eleId});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			Agregar tipo de instalación
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nInst">
		<table class="table" border="0">
			<tr>
				<td>Nombre</td>
				<td>
					<input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $datC['nombre']; ?>" />
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Costo</td>
				<td>
					<input type="text" name="costo" id="costo" class="form-control" value="<?php echo $datC['costo']; ?>" />
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Cantidad</td>
				<td>
					<input type="text" name="cantidad" id="cantidad" class="form-control" value="<?php echo $datC['cantidad']; ?>"  />
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
