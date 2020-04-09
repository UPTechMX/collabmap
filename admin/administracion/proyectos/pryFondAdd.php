<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$financiadores = $db->query("SELECT * FROM Fondeadores ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM ProyectosFondeadores WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
	}

	// print2($datC);
?>

<?php

$nivel = $_SESSION['IU']['admin']['nivel'];
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


		$('#fondeadoresId').change(function(event) {
			var fondId = $(this).val();

			if($('.fond_'+fondId).length != 0){
				alertar('Ya existe este financiador en el proyecto',function(){
					$('#fondeadoresId').val('');
				},{});
			}
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
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:acc,opt:2});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#pryFondList').load(rz+'admin/administracion/proyectos/pryFondList.php',{eleId:eleId});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			Agregar financiador
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
				<td>Financiadores</td>
				<td>
					<select class="form-control oblig selOblig" id="fondeadoresId" name="fondeadoresId">
						<option value="">- - - Selecciona un financiador - - -</option>
						<?php foreach ($financiadores as $f){ ?>
							<option value="<?php echo $f['id']; ?>" <?php echo $f['id'] == $datC['fondeadoresId']?'selected':''; ?> >
								<?php echo $f['nombre']; ?>
							</option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Presupuesto</td>
				<td>
					<input class="form-control" type="text" name="presupuesto" id="presupuesto" value="<?php echo $datC['presupuesto']; ?>" />
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
