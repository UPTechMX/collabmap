<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$ubicaciones = $db->query("SELECT pu.id, u.nombre, u.id as iId, u.tipoUbicacion 
		FROM ProyectosUbicaciones pu 
		LEFT JOIN Ubicaciones u ON u.id = pu.ubicacionesId
		WHERE proyectosId = $_POST[eleId]
		ORDER BY u.nombre")->fetchAll(PDO::FETCH_ASSOC);
	

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtPryUbic').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			var eleId = $(this).closest('.ubic').attr('id').split('_')[1];
			popUp('admin/administracion/proyectos/pryUbicAdd.php',{pryId:pryId,eleId:eleId},function(){},{});
		});

		$(".delPryUbic").click(function(event) {
			var elemId = $(this).closest('.ubic').attr('id').split('_')[1];
			var pryId = <?php echo $_POST['eleId']; ?>;
			// console.log(elemId,pryId);
			conf('¿Estás seguro que deseas eliminar este tipo de ubicalación del proyecto?',{elemId:elemId,pryId:pryId},function(e){

				var rj = jsonF('admin/administracion/proyectos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:3,opt:5});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#ubic_'+e.elemId).remove();
				}
			})
		});

	});
</script>

<table class="table" id="pryUbic">
	<thead>
		<tr>
			<th></th>
			<th>Nombre</th>
			<th>Tipo</th>
		</tr>
	</thead>
	<?php foreach ($ubicaciones as $k => $f){ ?>
		<tr class="ubic <?php echo "ubic_$f[iId]"; ?>" id="<?php echo "ubic_$f[id]"; ?>" >
			<td><i class="glyphicon glyphicon-pencil manita edtPryUbic"></i></td>
			<td><?php echo $f['nombre']; ?></td>
			<td><?php echo "$f[tipo]"; ?></td>
			<td><?php echo "$f[cantidad]"; ?></td>
			<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delPryUbic"></i></td>
		</tr>
	<?php } ?>
</table>
