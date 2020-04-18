<script type="text/javascript">
	$(document).ready(function() {
		$('#prbBar').click(function(event) {
			// console.log('aa');
			$('#problemList').toggle();
			if($('#problemList').is(':visible')){
				$('#problemsIco').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
			}else{
				$('#problemsIco').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
			}
		});
	});
</script>
<div id="prbBar">
	<div style="font-size: 1.2em;font-weight: bold;" class="manita">
		<span>Problems</span>&nbsp;
		<i class="glyphicon glyphicon-chevron-right" id="problemsIco"></i>
	</div>
</div>
<div id="problemList" style="display: none;"><?php include 'problemList.php' ?></div>