<?php  

	include_once '../../../../lib/j/j.func.php';

	$p = $_POST;
	// print2($_POST);

	if($_POST['dimensionElemId'] != ''){
		$datM = $db-> query("SELECT * FROM DimensionesElem WHERE id = $_POST[dimensionElemId]")->fetch(PDO::FETCH_ASSOC);
	}

	$dimensionesId = $_POST['dimensionElemId'] != ''?$datM['dimensionesId']:$_POST['dimensionId'];
	// print2($dimensionesId);
	$dimension = $db->query("SELECT * FROM Dimensiones WHERE id = $dimensionesId")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($dimension);
	$numDims = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE areasId = $dimension[areasId] ")->fetchAll(PDO::FETCH_NUM)[0][0];
	// print2($numDims);
	$ultimo = $numDims == $dimension['nivel'];

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
			$(this).css({backgroundColor:'white'});
		});


		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');
			<?php if ($ultimo){ ?>			
				dat.variables = $('#variables').is(':checked')?1:0;

			<?php } ?>

			<?php 
				if(isset($_POST['dimensionElemId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[dimensionElemId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.padre = $_POST[padreId];";
					echo "dat.dimensionesId = $_POST[dimensionId];";
				}
			?>

			if(allOk){

				var rj = jsonF('admin/administracion/targets/structure/json/json.php',{datos:dat,acc:acc,opt:5});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#dimensionesElemList_'+<?php echo $_POST['dimensionId']; ?>)
					.load(rz+'admin/administracion/targets/structure/dimensionesElemList.php',{
						ajax:1,
						areasId:<?php echo $_POST['areasId']; ?>,
						padreId:<?php echo $_POST['padreId']; ?>,
						dimensionId:<?php echo $_POST['dimensionId']; ?>
					});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['dimensionElemId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			elemento
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
			<?php if ($ultimo){ ?>			
				<tr>
					<td>Unidad</td>
					<td>
						<input type="text" value="<?php echo $datM['unidad']; ?>" name="unidad" id="unidad" class="form-control oblig" >
					</td>
					<td></td>
				</tr>
				<tr>
					<td>Se permite instalar varios</td>
					<td>
						<input type="checkbox" name="variables" id="variables" <?php echo $datM['variables'] == 1?'checked':''; ?> >
					</td>
					<td></td>
				</tr>
			<?php } ?>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
