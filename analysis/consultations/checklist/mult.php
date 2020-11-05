<?php
include_once '../../../lib/j/j.func.php';
checaAcceso(5); // checaAcceso analysis;

if(!is_numeric($_POST['pId']) && !is_numeric($_POST['trgtChk'])){
	exit();
}



?>

<script type="text/javascript">
	$(document).ready(function() {
		var answers = <?php echo atj($answers); ?>;
		console.log(answers);
		var pieData = [];
		for(var i = 0; i<answers.length; i++){
			var tmp = {};
			tmp.name = answers[i].respuesta;
			tmp.y = answers[i].cuenta;
			pieData.push(tmp);
		}

		var cats = [];
		var barData = [];
		for(var i = 0; i<answers.length; i++){
			cats.push(answers[i].respuesta);
			barData.push(answers[i].cuenta);
			// var tmp = {};
			// tmp.name = answers[i].respuesta;
			// tmp.y = answers[i].cuenta;
			// pieData.push(tmp);
		}



		// console.log(answers);
		
		pieChart($("#grafica_<?php echo $_POST['pId']; ?>"), pieData,"<?php echo $preg['pregunta']; ?>");

		$("#divAnswer_<?php echo $_POST['pId']; ?>").on('click', '.pieChart', function(event) {
			event.preventDefault();
			$("#grafica_<?php echo $_POST['pId']; ?>").empty();
			pieChart($("#grafica_<?php echo $_POST['pId']; ?>"), pieData,"<?php echo $preg['pregunta']; ?>");
		});
		$("#divAnswer_<?php echo $_POST['pId']; ?>").on('click', '.barChart', function(event) {
			event.preventDefault();
			$("#grafica_<?php echo $_POST['pId']; ?>").empty();
			barChart($("#grafica_<?php echo $_POST['pId']; ?>"), barData, cats, "<?php echo $preg['pregunta']; ?>");
		});

		
	});
</script>

<div class="row" id="divAnswer_<?php echo $_POST['pId']; ?>">
	<div class="col-3">
		<div>
			<div style="font-weight: bold;font-size: 1.1em;margin-top: 10px;margin-bottom: 10px;">
				
				<span ><?php echo TR('selectChart'); ?></span>
			</div>
			<table class="table">
				<tr>
					<td>
						<span class="pieChart manita">
							<img src="../img/ico/pie-chart.svg" width="30px" style="filter: grayscale(100%);" />&nbsp;&nbsp;
							<?php echo TR('piechart'); ?>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<span class="barChart manita">
							<img src="../img/ico/bar-chart.svg" width="30px" style="filter: grayscale(100%);" />&nbsp;&nbsp;
							<?php echo TR('barchart'); ?>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-9">
		<div id="grafica_<?php echo $_POST['pId']; ?>"></div>
	</div>
</div>