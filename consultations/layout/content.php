
<script type="text/javascript">
	$(document).ready(function() {
	});
</script>
<?php

if(!function_exists('raiz')){
	include_once '../../lib/j/j.func.php';
}

// include_once '../seguridad/seguridad.php';

$nivel = $_SESSION['CM']['admin']['nivel'];
$Act = $_REQUEST['acc'];

switch ($Act) {
	case 'about':
		// if($nivel >= 50)
		include_once raiz().'consultations/home/about.php';
		break;
	default:
		include_once raiz().'consultations/home/consultationsHome.php';
		break;			
}





?>


