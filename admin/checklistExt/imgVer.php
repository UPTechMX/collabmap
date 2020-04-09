<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50);

	$img = $db->query("SELECT * FROM ChecklistImagenes WHERE id = $_POST[imgId]")->fetch(PDO::FETCH_ASSOC);
	// print2($img);

?>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Imágen</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<img src="../img/checklistExt/<?php echo $img['archivo'];?>" style="max-width: 100%;">
</div>
