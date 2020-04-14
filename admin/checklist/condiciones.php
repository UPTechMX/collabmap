<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	// print2($_POST);

	// print2($conds);
	switch ($_POST['aplicacion']) {
		case 'preg':
			$accs[1] = 'Valor pregunta';
			$accs[5] = 'Modificar tantos';
			$accs[2] = 'No presentar';
			// $accs[6] = 'Modificar respuesta';
			break;
		case 'chk':
			$accs[3] = 'Valor total';
			$accs[4] = 'Modificar valor';
			break;
		case 'bloque':
		case 'area':
			$accs[2] = 'No presentar';
			$accs[3] = 'Valor total';
			$accs[4] = 'Modificar valor';
			break;		
		default:
			break;
	}
	$_POST['accs'] = $accs;
?>

<script type="text/javascript">
	$(document).ready(function() {


		$('#modal').css({width:'80%'});
		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		});


		$('#env').click(function(event) {
			$('#popUp').modal('toggle');
		});

		$('#agregaCond').click(function(event) {
			var dat = {};
			dat.eleId = "<?php echo $_POST['eleId']; ?>";
			dat.aplicacion = "<?php echo $_POST['aplicacion']; ?>";
			dat.condicion = $('#condicion').val();
			dat.accion = $('#accion').val();
			dat.valor = $('#valor').val();
			dat.orden = $('#tablaConds tr').length;

			var validj = jsonF('admin/checklist/json/json.php',{acc:6,cond:dat.condicion,chkId:checklistId});
			console.log(validj);
			var valid = $.parseJSON(validj);

			if(valid.ok == 1 && dat.accion != '' && dat.valor != ''){
				var rj = jsonF('admin/checklist/json/json.php',{opt:9,acc:1,datos:dat});
				// console.log(rj);
				var r = $.parseJSON(rj);
				var accs = <?php echo atj($accs); ?>;
				if(r.ok == 1){
					$('#condicionesList').load(rz+'admin/checklist/condicionesList.php',{eleId:dat.eleId,aplicacion:dat.aplicacion,accs:accs});
				}
			}else{
				console.log(valid.Err)
				alertar(valid.Err,function(){},{});
			}

		});

	});
</script>
<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title"><?php echo TR('conditional'); ?></h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>



	<table class="table">
		<tr>
			<td><?php echo TR('conditional'); ?></td>
			<td><input type="text" id="condicion" class="form-control"></td>
		</tr>
		<tr>
			<td><?php echo TR("action"); ?></td>
			<td>
				<select class="form-control" id="accion">
					<option value="">- - - - - - - - -</option>
					<?php foreach ($accs as $acc => $nombre): ?>
						<option value="<?php echo $acc; ?>"><?php echo $nombre; ?></option>

					<?php endforeach ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php echo TR('value'); ?></td>
			<td>
				<input type="text" id="valor" class="form-control" style="display: inline;width:70%;"/>
				<div style="text-align: right;width: 30%;display: inline;">
					<span class="btn btn-sm btn-shop" id="agregaCond"><?php echo TR('add'); ?></span>
				</div>
			</td>
		</tr>
	</table>
	<hr/>
	<div style="text-align: center;" class="nuevo">
		<h4><?php echo TR('conditions'); ?></h4>
	</div>
	<div id="condicionesList"><?php include 'condicionesList.php'; ?></div>
</div>
<div class="modal-footer">

<!-- 	<div style="text-align: right;">
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div> -->

</div>
