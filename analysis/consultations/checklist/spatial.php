<?php
include_once '../../../lib/j/j.func.php';
checaAcceso(5); // checaAcceso analysis;

if(!is_numeric($_POST['pId']) && !is_numeric($_POST['trgtChk'])){
	exit();
}

include 'spatialJS.php';

?>

<script type="text/javascript">
	$(document).ready(function() {
		var answers = <?php echo atj($answers); ?>;
		// console.log(answers);
		setTimeout(function(){
			initMap(<?php echo $_POST['pId']; ?>,answers);		
		},500);
		
	});
</script>

<div class="row" id="divAnswer_<?php echo $_POST['pId']; ?>">
	<div class="col-3">
		<div style="margin-top: 10px;" class="row">
			<div class="col-2">
				<div id="heat_<?php echo $_POST['pId']; ?>" class="manita" style="width: 40px;height: 40px;
					border:solid 1px;border-radius: 3px;margin-top: 10px;filter: grayscale(100%);">
					<img src="../img/ico/heatmap.svg" width="35px" style="margin-left: 2px;">
				</div>
			</div>
			<div class="col-2">
				<div  id="markers_<?php echo $_POST['pId']; ?>" class="manita" style="width: 40px;
					height: 40px;border:solid 1px;border-radius: 3px;margin-top: 10px;">
					<img src="../img/ico/marker.png" width="35px" style="margin-left: 2px;">
				</div>
			</div>
		</div>
	</div>
	<div class="col-9">
		<div id="map_<?php echo $_POST['pId']; ?>"style=" height: 500px;margin-top: 10px;" class="map"></div>
	</div>
</div>