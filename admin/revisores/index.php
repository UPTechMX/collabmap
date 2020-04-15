<!-- <link href="http://vjs.zencdn.net/6.2.5/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
<script src="http://vjs.zencdn.net/6.2.5/video.js"></script>
 -->


<script src="../lib/js/audiojs/audiojs/audio.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>

<script src="../lib/js/graficas.js"></script>

<?php
	// print2($_SESSION);
	$nivel = $_SESSION['CM']['admin']['nivel'];
	$uId = $_SESSION['CM']['admin']['usrId'];
	if($nivel != 10){
		exit('No tienes acceso');
	}

	

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tabPorRevisar').click(function(event) {
			$('#porRevisar').load(rz+'admin/revisores/porRevisar.php',{ajax: 1});
		});
		$('#tabRevisadas').click(function(event) {
			$('#revisadas').load(rz+'admin/revisores/revisadas.php',{ajax: 1});
		});
	});
</script>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active">
		<a href="#porRevisar" aria-controls="porRevisar" role="tab" data-toggle="tab" id="tabPorRevisar">Visitas por revisar</a>
	</li>
	<li role="presentation" >
		<a href="#revisadas" aria-controls="revisadas" role="tab" data-toggle="tab" id="tabRevisadas">Estatus de revisión</a>
	</li>
</ul>


<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="porRevisar">
		<?php include raiz().'admin/revisores/porRevisar.php'; ?>
	</div>
	<div role="tabpanel" class="tab-pane " id="revisadas">
		<?php include raiz().'admin/revisores/revisadas.php'; ?>		
	</div>
</div>
