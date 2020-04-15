<?php  

	include_once '../../../lib/j/j.func.php';

	// $p = $_POST;
	// print2($_POST);

	if($_POST['dimensionId'] != ''){
		$datM = $db-> query("SELECT * FROM Dimensiones WHERE id = $_POST[dimensionId]")->fetch(PDO::FETCH_ASSOC);
	}
?>

<script type="text/javascript">
	
	var mapa;

	$(document).ready(function() {

		var lat = <?php echo $_POST['lat']; ?>;
		var lng = <?php echo $_POST['lng']; ?>;
		console.log(lat,lng);

		setTimeout(function() {
			mapa = new Mapa(null, "mapaDiv2", "panelDiv");

			mapa.setBubbles(false);
			
			mapa.creaMapa();

			mapa.addSingleCoord(lat, lng, null, "ubicacion", "blue", "red");
			mapa.dibuja();

		}, 100);
	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Ubicacion</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<div id="mapaDiv2" style="height: 300px;"></div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
	</div>
</div>
