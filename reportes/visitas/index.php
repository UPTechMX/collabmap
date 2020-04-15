
<?php

if($_POST['ajax'] == 1){
	@session_start();
	include_once '../../lib/j/j.func.php';

}else{
	// print2($_SESSION);
	$_POST['proyectoId'] = $_SESSION['pub']['priv'][0]['proyectosId'];
}
	include_once raiz().'lib/php/calcCache.php';
// print2($_GET);
// print2($_POST);

$Act = $_REQUEST['Act'];
$usrId = $_SESSION['pub']['usrId'];
$mId = isset($_POST['mId'])?$_POST['mId']:0;

if(count($_SESSION['pub']['priv']) == 1){

}else{

}

foreach ($_SESSION['pub']['priv'] as $k => $p) {
	if($p['proyectosId'] == $_POST['proyectoId'])
		break;
}
$priv = $_SESSION['pub']['priv'][$k]; 
// print2($priv);
$dimElemId = $priv['dimensionesElemId'];
$nivel = is_numeric($priv['nivel'])?$priv['nivel']:0;
$cId = $priv['cId'];
$pryId = $priv['proyectosId'];

$datDimElem = $db-> query("SELECT * FROM DimensionesElem 
	WHERE id = $dimElemId")->fetch(PDO::FETCH_ASSOC);
$nomDimElem = $datDimElem['nombre'];

$dims = $db->query("SELECT * FROM Dimensiones WHERE clientesId = $cId ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

if($nivel != 0){
	$padres = array();
	buscaPadres($dimElemId,$padres);
	// print2($padres);
}else{
	$padres = array();
}


// $marcas = getMarcas($dimElemId,$pryId);
// print2($marcas);

?>

<script type="text/javascript">
	$(document).ready(function() {
		var proyectoId = <?php echo $pryId; ?>;
		var elemId = <?php echo $dimElemId; ?>;
		$('#repSelVis').change(function(event){
			reps = [$(this).val()];
			if($(this).val() != ''){			
				var marcasj = jsonF('reportes/visitas/json/getSelectores.php',{proyectoId:proyectoId,elem:elemId,get:'marcas',reps:reps});
				var marcas = $.parseJSON(marcasj);
			}else{
				marcas = [];
			}
			var opt0 = marcas.length == 1?true:false;
			optsSel(marcas,$('#marcasSelVis'),opt0,'- - Selecciona un socio comercial - -');
			$('#marcasSelVis').trigger('change');

		});
		$('#marcasSelVis').change(function(event){
			reps = [$('#repSelVis').val()];
			mId = $(this).val();
			if(mId != ''){
				var tiendasj = jsonF('reportes/visitas/json/getSelectores.php',{proyectoId:proyectoId,elem:elemId,get:'tiendas',reps:reps,mId:mId});
				var tiendas = $.parseJSON(tiendasj);
			}else{
				tiendas = [];
			}
			var opt0 = tiendas.length == 1?true:false;
			optsSel(tiendas,$('#tiendasSelVis'),opt0,'- - - Selecciona una tienda - - - -');
			$('#tiendasSelVis').trigger('change');
			$('#tiendasSelVis').selectpicker('refresh');
		});
		$('#tiendasSelVis').change(function(event){
			reps = [$('#repSelVis').val()];
			tId = $(this).val();
			if(tId != ''){
				var visitasj = jsonF('reportes/visitas/json/getSelectores.php',{proyectoId:proyectoId,elem:elemId,get:'visitas',reps:reps,tId:tId});
				var visitas = $.parseJSON(visitasj);
			}else{
				visitas = [];
			}
			var opt0 = visitas.length == 1?true:false;
			optsSel(visitas,$('#visitasSelVis'),opt0,'- - - Selecciona una visita - - - -');
			$('#visitasSelVis').trigger('change');
		});

		$('#verVisita').click(function(event) {
			var vId = $('#visitasSelVis').val();
			if(vId != ""){
				// console.log(vId);
				$('#divVisita').load(rz+'reportes/visitas/visita.php',{vId: vId, div: 1});
			}
		});

		$("#prySel").change(function(event) {
			var pryId = $(this).val();
			if(pryId != ''){
				var periodosj = jsonF('reportes/visitas/json/getSelectores.php',{proyectoId:pryId,elem:elemId,get:'repeticiones'});
				var periodos = $.parseJSON(periodosj);
				console.log(periodos);
			}else{
				periodos = [];
			}
			var opt0 = periodos.length == 1?true:false;
			optsSel(periodos,$('#repSelVis'),opt0,'- - Selecciona un periodo - -');
			$('#repSelVis').trigger('change');


		});
		
	});
</script>

<div class="row">
	<div class="col-md-3" >
		<div class="nuevo">Periodo</div>
		<!-- <select id="repSelVis" class="selectpicker" data-width="100%" data-live-search="true" title='Elige uno...'> -->

		<select id="repSelVis" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - - Selecciona un periodo - - - -</option>
			<?php 
				$reps = $db->query("SELECT * FROM Repeticiones WHERE proyectosId = $pryId AND elim IS NULL
					ORDER BY fechaIni DESC")->fetchAll(PDO::FETCH_ASSOC);
				foreach ($reps as $r){ ?>
				<option value="<?php echo $r['id']; ?>"><?php echo "$r[nombre]"; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-3" >
		<div class="nuevo">Socio comercial</div>
		<select id="marcasSelVis" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - Selecciona un socio comercial - -</option>
		</select>
	</div>
	<div class="col-md-3" >
		<div class="nuevo">Tienda</div>
		<select id="tiendasSelVis" class="selectpicker" 
			data-live-search="true" style="margin-bottom: 5px;" data-width="100%" height="10px">
			<option value="">- - - Selecciona una tienda - - - -</option>
		</select>
	</div>
	<div class="col-md-3" >
		<div class="nuevo">Visita</div>
		<select id="visitasSelVis" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - - Selecciona una visita - - - -</option>
		</select>
		<div style="text-align: right;">
			<span class="btn btn-sm btn-shop" id="verVisita">Ver visita</span>			
		</div>
	</div>
</div>
<div id="divVisita">
	
</div>
