<?php 
	$nvo = isset($_POST['nvo'])?$_POST['nvo']:0;
	$wId = isset($_POST['wId'])?$_POST['wId']:0;

	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {
		var nvo = <?php echo $nvo; ?>;
		var wId = <?php echo $wId; ?>;
		if(nvo == 1){
			$('#wid_'+wId).find('.bConf').trigger('click');
		}
		ajustaWidget(wId);
		// console.log(liH,bH);

	});
</script>
<div class="widgetBar" style="text-align: left;">
	<i class="glyphicon glyphicon-cog manita bConf"></i>
	&nbsp;
	<span class="nombre" style="font-weight: bold;font-size: 1.1em;"></span>
	<button type="button" class="close widClose" aria-hidden="true" style="color:black">×</button>
</div>
<div class="grafica" style="">&nbsp;</div>