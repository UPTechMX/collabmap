<?php
	
if($_POST['ajax'] == 1){
	@session_start();
	include_once '../../lib/j/j.func.php';
}else{
	// print2($_SESSION);
	$_POST['proyectoId'] = $_SESSION['pub']['priv'][0]['proyectosId'];
}

	$NPS = $db->query("SELECT nps.id, p.pregunta FROM PreguntasNPS nps
		LEFT JOIN Preguntas p ON p.id = nps.preguntasId
		WHERE proyectosId = $_POST[proyectoId] ORDER BY nps.orden")->fetchAll(PDO::FETCH_ASSOC);

// print2($_POST);
?>
<div class="row">
	<div class="col-md-3" style="text-align: center; height: 400px;padding-top: 40px;">
		<div style="">
			<span style="font-size: 3em;">Calificación Total</span><br/>
			<span id="califTot" style="font-size: 5em;"></span>
		</div>
	</div>
	<div class="col-md-9" style="text-align: center; height: 400px;padding-top: 40px;">
		<div id="pregImp"></div>
	</div>
</div>
<?php if (!isset($_POST['mId'])){ ?>
	<div class="nuevo">Totales por marca</div>
	<div class="row" id="totMarcas"></div>
<?php } ?>
<div class="nuevo">Calificaciones por estructura</div>
<div id="gBarrasSubs"></div>

<div class="nuevo">Datos clave</div>
<div class="row">
	<?php foreach ($NPS as $p){ ?>
		<div class="col-md-3" style="text-align: center;">
			<h4 style="height:100px; text-align: justify;" id="nomGraf_<?php echo $p['id'];?>">
				<?php echo $p['pregunta']; ?>
			</h4>
			<div id="NPS_<?php echo $p['id'];?>" class="dNPS"></div>
		</div>
	<?php } ?>
</div>

