<?php  

	if($_POST['ajax'] == 1){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);
	$proyectos = $db->query("SELECT c.*, m.nombre as mNom FROM Checklist c 
		LEFT JOIN Marcas m ON m.id = c.marcasId
		WHERE repeticionesId = $_POST[repId] ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

?>
<br/>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtcheckList').click(function(event) {
			var checklistId = this.id.split('_')[1];
			var repId = <?php echo $_POST['repId']; ?>;
			popUp('admin/checklist/checklistAdd.php',{repId:repId,checklistId:checklistId},function(e){},{});

		});
		$('.verChecklist').click(function(event) {
			var checklistId = this.id.split('_')[1];
			$('#bloques').load(rz+'admin/checklist/bloques.php',{checklistId:checklistId});
			$('#areas').empty();
		});

	});
</script>
<table class="table">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Siglas</th>
			<th>Tipo</th>
			<th>Marca</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($proyectos as $p): ?>
			<tr>
				<td><?php echo $p['nombre']; ?></td>
				<td><?php echo $p['siglas']; ?></td>
				<td><?php echo $p['tipo']; ?></td>
				<td><?php echo $p['mNom']; ?></td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-pencil manita edtcheckList" id="edtcheckList_<?php echo $p['id'];?>"></i>
				</td>
				<td style="text-align: center;">
					<i id="verChecklist_<?php echo $p['id'];?>" class="glyphicon glyphicon-chevron-right manita verChecklist"></i>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>