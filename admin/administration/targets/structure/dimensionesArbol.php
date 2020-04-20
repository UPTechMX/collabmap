<?php  

	if($_POST['ajax'] == 1){
		include_once '../../../../lib/j/j.func.php';
	}

	$p = $_POST;
	// print2($_POST);
	$dimensiones = $db->query("SELECT * FROM Dimensiones 
		WHERE elemId = $_POST[targetsId] AND type = 'structure'
		ORDER BY id
	")->fetchAll(PDO::FETCH_ASSOC);
	// $datC = $db-> query("SELECT * FROM Clientes WHERE id = $_POST[areasId]")->fetch(PDO::FETCH_ASSOC);
?>

<!-- <script type="text/javascript">
	$(document).ready(function() {
		$('.addDimEle').click(function(event) {
			var dimensionId = this.id.split('_')[1];
			var padreId = this.id.split('_')[2];
			popUp('admin/administration/targets/structure/dimensionesElemAdd.php',{
				marcaId:<?php echo $_POST['marcaId']; ?>,
				dimensionId:dimensionId,
				padreId:padreId
			},function(e){},{});
		});
	});
</script>
 -->
<?php foreach ($dimensiones as $orden => $dim): ?>
	<?php if ($orden%4 == 0): ?>
		<div class="row">
	<?php endif ?>
	<div class="col-md-4">
		<div class="nuevo" id="dimCol_<?php echo $dim['id'];?>">
			<?php echo $dim['nombre']; ?>
		</div>
		<?php 
			if($orden == 0){$_POST['padreId'] = 0;}else{unset($_POST['padreId']);}
			$_POST['dimensionId'] = $dim['id'];

		?>
		<div id="dimensionesElems_<?php echo $_POST['dimensionId'];?>" class="dimensionesElems">
			<?php include_once 'dimensionesElems.php' ?>
		</div>
	</div>
	<?php if ($orden%4 == 3): ?>
		</div>
	<?php endif ?>
<?php endforeach ?>
<?php if ($orden%4 != 3): ?>
	</div>
<?php endif ?>


