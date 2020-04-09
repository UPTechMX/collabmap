<?php  

	include_once '../../lib/j/j.func.php';
	// print2($_POST);
	checaAcceso(50);

	$chkId = $_POST['checklistId'];
	$resumen = $db->query("SELECT resumen FROM Checklist WHERE id = $chkId")->fetch(PDO::FETCH_NUM)[0];
	$chkInf = $db->query("SELECT * FROM Checklist WHERE id = $chkId")->fetchAll(PDO::FETCH_ASSOC);
	// print2($chkInf);

?>


<script type="text/javascript">
	$(document).ready(function() {
		$(".txAreaGral").jqte({
			source:false,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});

		subArch($('#addImg'),2,'<?php echo $_POST['checklistId'];?>_IMG_','jpg,png,svg',false,function(a){
			arch = a.prefijo+a.nombreArchivo;
			nombre = a.nombreArchivo
			console.log(arch,nombre);
			var dat = {};
			dat.archivo = arch;
			dat.nombre = nombre;
			dat.checklistId = <?php echo $_POST['checklistId']; ?>;
			var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:1,opt:10});
			console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('<tr>')
				.attr({
					id: 'img_'+r.nId,
				})
				.html(
					'<td><span class="manita verImg">'+nombre+'</span></td>'+
					'<td><i class="glyphicon glyphicon-trash manita rojo elimImg" ></i></td>'
				).appendTo('#tablaImg');
			}
		});

		$('#tablaImg').on('click', '.elimImg', function(event) {
			event.preventDefault();
			var imgId = $(this).closest('tr').attr('id').split('_')[1];
			conf('¿Está seguro que desea elilminar la imágen del checklist?',{imgId:imgId},function(e){
				var rj = jsonF('admin/checklist/json/json.php',{datos:{id:e.imgId},acc:7,opt:10});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#img_'+e.imgId).remove();
				}
			})
		});

		$('#tablaImg').on('click', '.verImg', function(event) {
			event.preventDefault();
			var imgId = $(this).closest('tr').attr('id').split('_')[1];
			popUp('admin/checklist/imgVer.php',{imgId:imgId},function(e){},{});

		});

		$('#gResumen').click(function(event) {
			var dat = {};
			dat.id = <?php echo $chkId; ?>;
			dat.resumen = $('#resumen').val();
			// console.log(dat);
			var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:2,opt:4});
			// console.log(rj);
		});

	});
</script>
<div class="col-md-12">
	<div class="nuevo">
		<?php echo $chkInf[0]['nombre']; ?>
	</div>
</div>
<div class="col-md-12">
	<div class="nuevo">Resumen</div>
	<textarea name="resumen" id="resumen" class="form-control txAreaGral"><?php echo $resumen ?></textarea>
	<div style="text-align: right;margin-top: 5px;">
		<span class="btn btn-sm btn-shop" style="" id="gResumen">Guardar resumen</span>
	</div>
</div>
<!-- <div class="col-md-6">
	<div class="nuevo">Imágenes</div>
	<table>
		<tr>
			<td valign="top" style="font-weight: bold;">Agregar imágen: </td>
			<td>&nbsp;&nbsp;&nbsp;</td>
			<td><span id="addImg"></span></td>
		</tr>
	</table>
	<table id="tablaImg" class="table">
		<?php 
			$imgs = $db->query("SELECT * FROM ChecklistImagenes WHERE checklistId = $chkId")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($imgs as $img) {
		?>
			<tr id="img_<?php echo $img['id']; ?>">
				<td><span class="manita verImg"><?php echo $img['nombre'] ?></span></td>
				<td><i class="glyphicon glyphicon-trash manita rojo elimImg" ></i></td>
			</tr>
		<?php
			}
		?>
	</table>
</div> -->






