<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);

	if(!empty($_POST['eleId'])) {	
		$mediosPI = $db->query("SELECT *
			FROM ReconocimientoMediosPorImplementar pi
			WHERE pi.reconocimientosId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);

		// print2($mediosPI);
	}else{
		$mediosPI = array();
	}
		

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delMedPI').click(function(event) {
			var elemId = $(this).closest('.medPI').attr('id').split('_')[1];
			var recId = "<?php echo $_POST['eleId']; ?>";
			conf('¿Estás seguro que deseas eliminar este medio por implementar del reconocimiento?',{elemId:elemId,recId:recId},function(e){
				var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:5,opt:4});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#medPI_'+e.elemId).remove();
				}
			})

		});

	});
</script>

<table class="table">
	<thead>
		<tr>
			<th>Medio</th>
			<th>Observaciones</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($mediosPI as $m){ ?>
			<tr class="medPI" id="medPI_<?php echo $m['id']; ?>">
				<td><?php echo $m['medio']; ?></td>
				<td><?php echo $m['observaciones']; ?></td>
				<td><i class="glyphicon glyphicon-trash manita rojo delMedPI"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
