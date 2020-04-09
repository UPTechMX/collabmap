<?php  

	include_once '../../lib/j/j.func.php';
	$img = $db->query("SELECT * FROM Multimedia WHERE id = $_POST[imgId]")->fetch(PDO::FETCH_ASSOC);
	// print2($_POST);

	$raiz = $_POST['div'] == 1 ? '../':'../../';

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#modal').css({width:'90%'});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Imágen</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;text-align: center;'>
	<img src="<?php echo $raiz; ?>campo/archivosCuest/<?php echo $img['archivo'];?>" style="max-width: 100%;">
</div>
