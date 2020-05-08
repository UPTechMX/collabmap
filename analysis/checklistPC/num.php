<?php
include_once '../../lib/j/j.func.php';
checaAcceso(5); // checaAcceso analysis;

if(!is_numeric($_POST['pId']) && !is_numeric($_POST['trgtChk'])){
	exit();
}



?>

<script type="text/javascript">
	$(document).ready(function() {
		var answers = <?php echo atj($answers); ?>;
		var datos = [];

		for(var i = 0; i<answers.length;i++){
			var respuesta = parseFloat(answers[i].respuesta);
			var cuenta = parseInt(answers[i].cuenta);
			// console.log('respuesta: '+respuesta, 'cuenta: '+cuenta);
			if(!isNaN(respuesta)){
				for(var j = 0;j<cuenta;j++){
					datos.push(respuesta);
				}
			}
		}
		// console.log(datos);
		
		// console.log(answers);
		var datGr = arreglaDatos(datos);
		// console.log(datGr);
		numChart($("#grafica_<?php echo $_POST['pId']; ?>"), datGr,"<?php echo $preg['pregunta']; ?>");
		$("#divAnswer_<?php echo $_POST['pId']; ?> .min").text(datGr.xMin);
		$("#divAnswer_<?php echo $_POST['pId']; ?> .max").text(datGr.xMax);
		$("#divAnswer_<?php echo $_POST['pId']; ?> .average").text(datGr.avg.toFixed(2));
		$("#divAnswer_<?php echo $_POST['pId']; ?> .median").text(datGr.median);
		$("#divAnswer_<?php echo $_POST['pId']; ?> .Q1").text(datGr.Q1);
		$("#divAnswer_<?php echo $_POST['pId']; ?> .Q2").text(datGr.Q2);
		$("#divAnswer_<?php echo $_POST['pId']; ?> .Q3").text(datGr.Q3);
		
	});
</script>

<div class="row" id="divAnswer_<?php echo $_POST['pId']; ?>">
	<div class="col-3">
		<div>
			<table class="table" style="margin-top: 10px;">
				<tr>
					<td class="datNom"><?php echo TR('average'); ?></td class="datNom">
					<td class="average"></td>
				</tr>
				<tr>
					<td class="datNom"><?php echo TR('median'); ?></td class="datNom">
					<td class="median"></td>
				</tr>
				<tr>
					<td class="datNom"><?php echo TR('min'); ?></td class="datNom">
					<td class="min"></td>
				</tr>
				<tr>
					<td class="datNom"><?php echo TR('max'); ?></td class="datNom">
					<td class="max"></td>
				</tr>
				<tr>
					<td class="datNom">Q1</td class="datNom">
					<td class="Q1"></td>
				</tr>
				<tr>
					<td class="datNom">Q2</td class="datNom">
					<td class="Q2"></td>
				</tr>
				<tr>
					<td class="datNom">Q3</td class="datNom">
					<td class="Q3"></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-9">
		<div id="grafica_<?php echo $_POST['pId']; ?>"></div>
	</div>
</div>
