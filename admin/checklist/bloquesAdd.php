<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist	$p = $_POST;
	// print2($_POST);

	if($_POST['bloqueId'] != ''){
		$datM = $db-> query("SELECT * FROM Bloques WHERE id = $_POST[bloqueId]")->fetch(PDO::FETCH_ASSOC);
	}
	// print2($datM);

	$identificador = "b_$_POST[checklistId]_";

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#modal').css({width:''});
		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#nombre').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				event.preventDefault();
			}
		});

		<?php if(isset($_POST['bloqueId'])): ?>
			$('#chkIdentificador').click(function(event) {
				if($('#identificador').is(':visible')){
					$('#identificador').val("<?php echo $datM['identificador'];?>")
					$('#identificador').toggle();
				}else{
					conf('Cambiar el identificador modifica las asociaciones entre bloques de distintos checklist',{},function(){
						$('#identificador').toggle();
					});
				}
			});
		<?php endif; ?>

		// console.log(checklistId);
		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.encabezado = $('#encabezado').is(':checked')?1:0;
			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['bloqueId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[bloqueId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.checklistId = $_POST[checklistId];";
					echo 'dat.orden = $("#bloquesSort li").length+1;';
					echo 'dat.identificador = "'.$identificador.'";';
				}
			?>

			if(allOk){
				var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:acc,opt:5,chkId:checklistId});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#bloquesList').load(rz+'admin/checklist/bloquesList.php',
						{ajax:1,checklistId:<?php echo $_POST['checklistId']; ?>});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['bloqueId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			bloque
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
					<input type="text" value="<?php echo $datM['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Tipo del promedio</td>
				<td>
					<select class="form-control" id="tipoProm" name="tipoProm">
						<option value="1" <?php echo $datM['tipoProm'] == 1 ? 'selected':''; ?> >Por preguntas</option>
						<option value="2" <?php echo $datM['tipoProm'] == 2 ? 'selected':''; ?> >Suma de promedios de las áreas</option>
						<option value="3" <?php echo $datM['tipoProm'] == 3 ? 'selected':''; ?> >Promedio de la suma de promedios de las áreas</option>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Ponderador del bloque</td>
				<td>
					<input type="text" value="<?php echo $datM['valMax'] != ''?$datM['valMax']:100; ?>" 
						name="valMax" id="valMax" class="form-control" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Encabezado</td>
				<td>
					<input type="checkbox" value="1" name="encabezado" id="encabezado"  <?php echo $datM['encabezado'] == 1?'checked':''; ?>>
				</td>
				<td></td>
			</tr>
			<?php if(isset($_POST['bloqueId'])): ?>
				<tr>
					<td><span class="btn btn-sm btn-default" id="chkIdentificador">Cambiar identificador</span></td>
					<td>
						<input type="text" value="<?php echo $datM['identificador']; ?>" 
						name="identificador" id="identificador" class="form-control oblig" style="display: none;" />
					</td>
					<td></td>
				</tr>
			<?php endif; ?>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>


