
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


$_SESSION['pub']['priv'] = is_array($_SESSION['pub']['priv'])?$_SESSION['pub']['priv']:array();
if(count($_SESSION['pub']['priv']) == 1){

}else{

}

// foreach ($_SESSION['pub']['priv'] as $k => $p) {
// 	if($p['proyectosId'] == $_POST['proyectoId'])
// 		break;
// }
// $priv = $_SESSION['pub']['priv'][$k]; 
// print2($priv);
$dimElemId = $priv['dimensionesElemId'];
$nivel = is_numeric($priv['nivel'])?$priv['nivel']:0;
$cId = $priv['cId'];
$pryId = $priv['proyectosId'];

$datDimElem = $db-> query("SELECT * FROM DimensionesElem 
	WHERE id = 0")->fetch(PDO::FETCH_ASSOC);
$nomDimElem = $datDimElem['nombre'];

// $dims = $db->query("SELECT * FROM Dimensiones WHERE clientesId = $cId ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

if($nivel != 0){
	$padres = array();
	buscaPadres($dimElemId,$padres);
	// print2($padres);
}else{
	$padres = array();
}

$prys = $db -> query("SELECT p.clientesId as cId, p.nombre as nom, p.id as val, p.clientesId as clase 
	FROM Proyectos p")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

$reps = $db->query("SELECT r.proyectosId as pId, r.nombre as nom, r.id as val, r.proyectosId as clase 
	FROM Repeticiones r WHERE (r.elim != 1 OR r.elim IS NULL)")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

// print2($reps)

?>

<script type="text/javascript">
	$(document).ready(function() {
		// var elemId = <?php echo $dimElemId; ?>;
		$('#repSelDesc').change(function(event){
			reps = [$(this).val()];
			var proyectoId = $('#prySelDesc').val();
			if($(this).val() != ''){
				// console.log(proyectoId);
				var chksj = jsonF('reportes/descargas/json/getSelectores.php',{proyectoId:proyectoId,elem:0,get:'chks',reps:reps});
				// console.log(chksj);
				var chks = $.parseJSON(chksj);
			}else{
				chks = [];
			}
			var opt0 = chks.lLength == 1?true:false;
			optsSel(chks,$('#chkSelDesc'),opt0,'- - Selecciona un checklist - -');
			$('#chkSelDesc').trigger('change');
		});

		$('#descConc').click(function(event) {
			var chkId = $('#chkSelDesc').val();
			// console.log('asas');
			if(chkId != ''){
				var chkNom = $('#chkSelDesc :selected').text();
				$('<form>')
				.attr({
					id: 'descForm',
					action: rz+'reportes/descargas/json/getDatosXLS.php',
					target:'_blank',
					method:'post'
				})
				.html(
					'<input type="text" name="chkId" value="'+chkId+'"\>'+
					'<input type="text" name="chkNom" value="'+chkNom+'"\>'
				)
				.appendTo(document.body)
				.submit()
				.remove();
			}
		});

		prys = <?php echo atj($prys); ?>;
		$('#cltSelDesc').change(function(event) {
			var cId = $(this).val();
			if(cId != ""){				
				optsSel(prys[cId],$('#prySelDesc'),false,'- - - Selecciona un proyecto - - - -');
			}else{
				optsSel([],$('#prySelDesc'),false,'- - - Selecciona un proyecto - - - -');
			}
			$('#prySelDesc').trigger('change')
		});

		repss = <?php echo atj($reps); ?>;
		$('#prySelDesc').change(function(event) {
			var pId = $(this).val();
			if(pId != ""){
				optsSel(repss[pId],$('#repSelDesc'),false,'- - - Selecciona un periodo - - - -');
			}else{
				optsSel([],$('#repSelDesc'),false,'- - - Selecciona un periodo - - - -');
			}
			$('#repSelDesc').trigger('change')
		});
		
	});
</script>

<div class="row">
	<div class="col-md-3" >
		<div class="nuevo">Cliente</div>
		<select id="cltSelDesc" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - - Selecciona un cliente - - - -</option>
			<?php 
				$clte = $db->query("SELECT * FROM Clientes")->fetchAll(PDO::FETCH_ASSOC);
				foreach ($clte as $r){ ?>
				<option value="<?php echo $r['id']; ?>"><?php echo "$r[nombre]"; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-3" >
		<div class="nuevo">Proyecto</div>
		<select id="prySelDesc" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - - Selecciona un proyecto - - - -</option>;
		</select>
	</div>
	<div class="col-md-3" >
		<div class="nuevo">Periodo</div>
		<select id="repSelDesc" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - - Selecciona un periodo - - - -</option>
		</select>
	</div>
	<div class="col-md-3" >
		<div class="nuevo">Checklist</div>
		<select id="chkSelDesc" class="" style="margin-bottom: 5px;width:100%;height: 30px;" height="10px">
			<option value="">- - Selecciona un checklist - -</option>
		</select>
		<div style="text-align: right;">
			<span class="btn btn-sm btn-shop" id="descConc">Descargar</span>			
		</div>
	</div>
</div>
