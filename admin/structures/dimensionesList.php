<?php  

	if($_POST['ajax'] == 1){
		include_once '../../lib/j/j.func.php';
	}

	// print2($_POST);
	$dimensiones = $db->query("SELECT * FROM Dimensiones 
		WHERE elemId = $_POST[elemId] AND type = '$_POST[type]'
	")->fetchAll(PDO::FETCH_ASSOC);
	// $datC = $db-> query("SELECT * FROM Clientes WHERE id = $_POST[elemId]")->fetch(PDO::FETCH_ASSOC);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtDim').click(function(event) {
			var dimensionId = this.id.split('_')[1];
			var type = "<?php echo $_POST['type']; ?>";
			var elemId = <?php echo $_POST['elemId']; ?>;
			popUp('admin/structures/dimensionesAdd.php',
				{dimensionId:dimensionId,elemId:elemId,type:type},
				function(e){},{});
		});

	});
</script>
<table class="table">
	<thead>
		<tr>
			<th><?php echo TR('name'); ?></th>
			<th></th>
			<!-- <th></th> -->
			<!-- <th></th> -->
		</tr>
	</thead>
	<tbody id="tabDims">
		<?php foreach ($dimensiones as $d): ?>
			<tr>
				<td><span class="verDim" id="verDim_<?php echo $d['id'];?>"><?php echo $d['nombre']; ?></span></td>
				<td><i class="glyphicon glyphicon-pencil edtDim manita" id="edtDim_<?php echo $d['id'];?>"></i></td>
				<!-- <td><i class="glyphicon glyphicon-user usrDim manita" id="usrDim_<?php echo $d['id'];?>"></i></td> -->
				<!-- <td><i class="glyphicon glyphicon-th dimDim manita" id="dimDim_<?php echo $d['id'];?>"></i></td> -->
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
