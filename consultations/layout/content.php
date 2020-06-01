
<?php

if(!function_exists('raiz')){
	include_once '../../lib/j/j.func.php';
}
// print2(atj($_REQUEST));
?>

<script type="text/javascript">
	$(document).ready(function() {

	});
</script>


<?php 
// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];
$Act = $_REQUEST['acc'];

switch ($Act) {
	case 'about':
		include_once raiz().'consultations/home/about.php';
		break;
	case 'consultation':
		include_once raiz().'consultations/consultation/index.php';
		break;
	case 'edtProfile':
		include_once raiz().'consultations/profile/index.php';
		break;
	default:
		include_once raiz().'consultations/home/consultationsHome.php';
		break;			
}





?>


