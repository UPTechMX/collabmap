<?php  
session_start();

@include_once '../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';

$vId = isset($_POST['vId'])?$_POST['vId']:14112;
// $vId = isset($_POST['vId'])?$_POST['vId']:5094;

$chk = new Checklist($vId);
if(empty($_SESSION['CM']['chk'][$vId])){
	$_SESSION['CM']['chk'][$vId] = array();
	$_SESSION['CM']['chk'][$vId]['chkId'] = $chk->id;
}

$vInfo = $chk->getVisita();



if( empty($_SESSION['CM']['chks'][$chk->id]) ){
	$_SESSION['CM']['chks'][$chk->id] = array();
	$_SESSION['CM']['chks'][$chk->id]['est'] = $chk->getEstructura();
}

if( empty( $_SESSION['CM']['chk'][$vId]['est'] ) ){
	$est = $_SESSION['CM']['chks'][$chk->id]['est'];
	$_SESSION['CM']['chk'][$vId]['est'] = $est;
	// print2($_SESSION['CM']['chk'][$vId]);
}else{
	$est =  $_SESSION['CM']['chk'][$vId]['est'];
}

if( empty( $_SESSION['CM']['chk'][$vId]['res'] ) ){
	$res = $chk->getResultados($vId);
	// print2($res);
	$_SESSION['CM']['chk'][$vId]['res']  = $res;
}else{
	$res =  $_SESSION['CM']['chk'][$vId]['res'];
}

// print2($res);

$areas = array();
$bloques = array();
$aIdU = null;
$bIdU = null;
$uPid = null;
foreach ($res as $r) {
	if($r['respuesta'] != ''){
		// print2($r);
		$areas[$r['area']] = $r['area'];
		$bloques[$r['bloque']] = $r['bloque'];
		$aIdU = $r['area'];
		$bIdU = $r['bloque'];
		$pIdU = $r['identificador'];
	}
}

?>

<script type="text/javascript">
	$(function() {

		var est = <?php echo atj($est); ?>;
		var chkId = <?php echo $chk->id; ?>;
		var vId = <?php echo $vId; ?>;
		// console.log(est);

		<?php foreach ($bloques as $bIdDes){ ?>
			$('#<?php echo $bIdDes; ?>').removeClass('disabled');
		<?php } ?>
		<?php foreach ($areas as $aIdDes){ ?>
			$('#<?php echo $aIdDes; ?>').removeClass('disabled');
		<?php } ?>



		$('.bloqueBtn').click(function(event) {
			var bId = this.id;
			if( !$(this).hasClass('disabled') ){			
				$('.areasBloque').hide()
				$('#a_'+bId).show();
				$('.bloqueBtn').removeClass('active');
				$(this).addClass('active');
				$('.areaBtn').removeClass('active');
				$('#pregunta').empty();

				// for(var i in $('#a_'+bId+' .areaBtn')){
				// 	console.log(i);
				// }
				$.each($('#a_'+bId+' .areaBtn'), function(index, val) {
					var id = this.id;
					if(!$(this).hasClass('disabled')){
						$(this).trigger('click');
						return false;
					}
				});
				
			}
		});

		$('.areaBtn').click(function(event) {
			var aId = this.id;
			if( !$(this).hasClass('disabled') ){			
				$('.areaBtn').removeClass('active');
				$(this).addClass('active');
				$('#pregunta').empty();
				if(aId == 'area_general'){
					$('#pregunta').load(rz+'checklist/areaGeneral.php',{
						chkId:chkId,
						vId:vId
					},function(){});
				}else if(aId == 'area_archivos'){
					// console.log('aa');
					$('#pregunta').load(rz+'checklist/archivos.php',{
						vId:vId
					} ,function(){});
				}else if(aId == 'area_vistaGral'){
					// $('#pregunta').empty();
					$('#pregunta').load(rz+'checklist/divVisita.php',{
						vId:vId,
						div:1
					} ,function(){});
				}else{
					$('#pregunta').load(rz+'checklist/pregunta.php',{
						aId: aId,
						chkId:chkId,
						vId:vId,
						abId:$(this).closest('div').attr('id')
					} ,function(){});
				}
			}
		});
		// $('#area_general').trigger('click');





	});
</script>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<!-- Brand and toggle get grouped for better mobile display -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" 
			data-target="#bloquesNav" aria-controls="bloquesNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="bloquesNav">

			<ul class="navbar-nav mr-auto" id="bloques">
					<!-- <li class="bloqueBtn active nav-item" id="general"><a class="nav-link"  href="#"><?php echo TR('general'); ?></a></li> -->
				<?php foreach ($est['bloques'] as $bId => $b){ ?>
					<li class="bloqueBtn disabled nav-item" id="<?php echo $bId; ?>">
						<a class="nav-link" href="#" ><?php echo $b['nombre']; ?></a>
					</li>
				<?php } ?>
					<li class="bloqueBtn nav-item" id="finalizar"><a class="nav-link" href="#"><?php echo TR('finalize') ?></a></li>
			</ul>
		</div>
</nav>
<div class="row">
	<div class="col-md-3 areas">
		<div id="a_general" class="areasBloque">
			<ul class="list-group">
				<li class="list-group-item manita areaBtn" id="area_general">
					<?php echo TR('generaldata'); ?>
				</li>
			</ul>
		</div>
		<?php 
			$i = 0;
			foreach ($est['bloques'] as $bId => $b){ 
		?>
					
			<div id="a_<?php echo $bId;?>" style="display: none;" class="areasBloque">
				<ul class="list-group">
					<?php 
						foreach ($b['areas'] as $aId => $a){
							$i++;
							if($i == 1){
								$primerArea = $aId;
								$primerbloque = $bId;
							}
					?>
						<li class="list-group-item manita areaBtn disabled" id="<?php echo $aId; ?>">
							<?php echo $a['nombre']; ?>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<div id="a_finalizar" class="areasBloque" style="display: none;">
			<ul class="list-group">
				<li class="list-group-item manita areaBtn" id="area_archivos">
					<?php echo TR('uploadFiles'); ?>
				</li>
				<li class="list-group-item manita areaBtn" id="area_vistaGral">
					<?php echo TR('preview'); ?>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-9">
		<div id="pregunta"></div>
	</div>
</div>


<script type="text/javascript">
	$(function() {


		<?php if(!empty($pIdU)){ ?>
			$('#pregunta').load(rz+'checklist/pregunta.php',{
				pId: '<?php echo $pIdU; ?>',
				aId: '<?php echo $aIdU; ?>',
				chkId:'<?php echo $chk->id; ?>',
				vId:'<?php echo $_POST['vId']; ?>',
				abId:'a_<?php echo $bIdU; ?>',
				direccion:'siguiente'
			});
		<?php }else{ ?>
			$('#pregunta').load(rz+'checklist/pregunta.php',{
				aId: '<?php echo $primerArea; ?>',
				chkId:'<?php echo $chk->id; ?>',
				vId:'<?php echo $vId; ?>',
				abId:'a_<?php echo $primerbloque;?>'
			} ,function(){});

		<?php } ?>



		$('#pregunta').on('click', '#siguienteGral', function(event) {
			event.preventDefault
			var allOk = camposObligatorios('#tablaGral');
			var hora = $('#hora').val();
			var hora2 = $('#horaSalida').val();
			// console.log(hora < hora2);

			if(hora >= hora2){
				allOk = false;
				alert('La hora de salida no puede ser menor o igual a la de entrada');
			}

				// console.log(datos);
			if(allOk){
				var datos = $('#tablaGral').serializeObject();
				var rj = jsonF('checklist/json/json.php',{acc:2,datos:datos,vId:<?php echo $_POST['vId'];?>});
				// console.log(rj);
				var r = $.parseJSON(rj);
				var primerArea = '<?php echo $primerArea; ?>';
				if(r.ok == 1){
					$('#pregunta').load(rz+'checklist/pregunta.php',{
						aId: '<?php echo $primerArea; ?>',
						chkId:'<?php echo $chk->id; ?>',
						vId:'<?php echo $vId; ?>',
						abId:'a_<?php echo $primerbloque;?>'
					} ,function(){});
				}else{
					console.log(r);
				}
			}
		});
	});
</script>









