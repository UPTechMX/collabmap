<?php
include_once '../../lib/j/j.func.php';
checaAcceso(50); // checaAcceso analysis;

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
	<div class="col-3"></div>
	<div class="col-9">
		<div id="map_<?php echo $_POST['pId']; ?>"style=" height: 500px;margin-top: 10px;" class="map"></div>
	</div>
</div>