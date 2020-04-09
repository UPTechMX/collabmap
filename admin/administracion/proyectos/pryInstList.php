<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$instalaciones = $db->query("SELECT i.id, i.nombre, i.costo, i.cantidad
		FROM Instalaciones i 
		WHERE proyectosId = $_POST[eleId]
		ORDER BY i.nombre")->fetchAll(PDO::FETCH_ASSOC);
	
	// print2($instalaciones);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtPryInst').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			var eleId = $(this).closest('.inst').attr('id').split('_')[1];
			popUp('admin/administracion/proyectos/pryInstAdd.php',{pryId:pryId,eleId:eleId},function(){},{});
		});

		$('.edtEqInst').click(function(event) {
			var eleId = $(this).closest('.inst').attr('id').split('_')[1];
			popUp('admin/administracion/proyectos/instEq.php',{eleId:eleId},function(){},{});
		});

		$(".delPryInst").click(function(event) {
			var elemId = $(this).closest('.inst').attr('id').split('_')[1];
			var pryId = <?php echo $_POST['eleId']; ?>;
			conf('¿Estás seguro que deseas eliminar este tipo de instalación del proyecto?',{elemId:elemId,pryId:pryId},function(e){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:3,opt:3});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#inst_'+e.elemId).remove();
				}
			})
		});


	});
</script>

<table class="table" id="pryInst">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th>Tipo de instalacion</th>
			<th>Costo</th>
			<th>Meta mínima</th>
		</tr>
	</thead>
	<?php foreach ($instalaciones as $k => $f){ ?>
		<tr class="inst <?php echo "inst_$f[iId]"; ?>" id="<?php echo "inst_$f[id]"; ?>" >
			<td><i class="glyphicon glyphicon-pencil manita edtPryInst"></i></td>
			<td><i class="glyphicon glyphicon-th-large manita edtEqInst"></i></td>
			<td><?php echo $f['nombre']; ?></td>
			<td><?php echo "\$ $f[costo]"; ?></td>
			<td><?php echo "$f[cantidad]"; ?></td>
			<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delPryInst"></i></td>
		</tr>
	<?php } ?>
</table>
