

<script type="text/javascript">
	$(document).ready(function() {
		$('#addChk').click(function(event) {
			popUp('admin/checklistExt/checklistAdd.php',{},function(e){},{});
		});
	});
</script>
<div class="row">
	<div class="col-md-5" >
		<div class="nuevo">Encuesta</div>
		<span class="btn btn-shop btn-sm" id="addChk">
			<i class="glyphicon glyphicon-plus">&nbsp;</i>Agregar una encuesta
		</span>
		<div style="height: 150px;overflow-y: auto; margin-top: 15px" id="checklistList">
			<?php include 'checklistList.php'; ?>
		</div>
	</div>
	<div class="col-md-7" id="infoChk"></div>
</div>
<hr/>
<div class="row" id="general" style="margin-top:20px;margin-bottom: 20px;"></div>
<div class="row">
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="bloques"></div>
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="areas"></div>
</div>
<div id="preguntas"></div>
