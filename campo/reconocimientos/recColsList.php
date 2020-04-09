<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);

	if(!empty($_POST['eleId'])) {	
		$ubicsRec = $db->query("SELECT ru.id, u.nombre, u.id as uId 
			FROM ReconocimientosUbicaciones ru 
			LEFT JOIN Ubicaciones u ON u.id = ru.ubicacionesId
			WHERE reconocimientosId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);
	}else{
		$ubicsRec = array();
	}
	

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delRecUbic').click(function(event) {
			var elemId = $(this).closest('.col').attr('id').split('_')[1];
			var recId = "<?php echo $_POST['eleId']; ?>";
			conf('¿Estás seguro que deseas eliminar esta ubicacion del reconocimiento?',{elemId:elemId,recId:recId},function(e){
				var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:6,opt:5});
				console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#col_'+e.elemId).remove();
				}
			})

		});
	});
</script>

<table class="table" id="pryCols" style="font-size: small;">
	<thead>
		<tr>
			<th>Estado</th>
			<th>Municipio</th>
			<th>Colonia</th>
			<th>Territorial</th>
			<th>Barrio</th>
			<th>Precariedad</th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($colonias as $k => $f){ ?>
			<tr class="col <?php echo "col_$f[fId]"; ?>" id="<?php echo "col_$f[id]"; ?>">
				<td ><?php echo $f['eNom']; ?></td>
				<td ><?php echo $f['mNom']; ?></td>
				<td class="" ><?php echo $f['colonia']; ?></td>
				<td ><?php echo $f['territorial']; ?></td>
				<td ><?php echo $f['barrio']; ?></td>
				<td ><?php echo $f['precariedad']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delRecCol"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>