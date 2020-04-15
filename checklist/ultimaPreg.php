<?php  
session_start();

@include_once '../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';

$vId = isset($_POST['vId'])?$_POST['vId']:14112;
// $vId = isset($_POST['vId'])?$_POST['vId']:5094;
$chk = new Checklist($vId);
// echo "vId:$vId";

$_SESSION['CM']['chk'][$vId] = array();
$_SESSION['CM']['chk'][$vId]['chkId'] = $chk->id;


if( empty( $_SESSION['CM']['chk'][$vId]['est'] ) ){
	$est = $chk->getEstructura();
	$_SESSION['CM']['chk'][$vId]['est'] = $est;
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

$areas = array();
$bloques = array();
$aIdU = null;
$bIdU = null;
$uPid = null;
foreach ($res as $r) {
	if($r['respuesta'] != ''){
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
		$('#pregunta').load(rz+'checklist/pregunta.php',{
			pId: '<?php echo $pIdU; ?>',
			aId: '<?php echo $aIdU; ?>',
			chkId:'<?php echo $chk->id; ?>',
			vId:'<?php echo $_POST['vId']; ?>',
			abId:'a_<?php echo $bIdU; ?>',
			direccion:'regresar'
		} ,function(){});
	});
</script>
