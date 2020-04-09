<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$colonias = $db->query("SELECT pc.id, pc.colonia, m.nombre as mNom, e.nombre as eNom 
		FROM ProyectosColonias pc
		LEFT JOIN Estados e ON pc.estadosId = e.id
		LEFT JOIN Municipios m ON pc.municipiosId = m.id
		WHERE proyectosId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);
	
	// print2($colonias);

	// $db->query("DELETE FROM ProyectosColonias");

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delPryCol').click(function(event) {
			var elemId = $(this).closest('.col').attr('id').split('_')[1];
			var pryId = <?php echo $_POST['eleId']; ?>;
			conf('¿Estás seguro que deseas eliminar esta colonia del proyecto?',{elemId:elemId,pryId:pryId},function(e){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:3,opt:4});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#col_'+e.elemId).remove();
				}
			})

		});
	});
</script>

<table class="table" id="pryCols">
	<thead>
		<tr>
			<th>Estado</th>
			<th>Municipio</th>
			<th>Colonia</th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($colonias as $k => $f){ ?>
			<tr class="col <?php echo "col_$f[fId]"; ?>" id="<?php echo "col_$f[id]"; ?>">
				<td ><?php echo $f['eNom']; ?></td>
				<td ><?php echo $f['mNom']; ?></td>
				<td class="colNom" ><?php echo $f['colonia']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delPryCol"></i></td>
			</tr>
		<?php } ?>

	</tbody>
</table>