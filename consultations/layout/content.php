<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
?>

<div id="dAlerta"></div>

<?php

// print2(atj($_REQUEST));
$location = empty($location)?'consultations':$location;
$htmlRoot = aRaizHtml($location);

?>

<script type="text/javascript">
	$(document).ready(function() {

	});
</script>
<!-- <?php include 'header.php'; ?> -->

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
	case 'facilitator':
		include_once raiz().'questionnaires/targets/index.php';
		break;
	case 'edtProfile':
		include_once raiz().'consultations/profile/index.php';
		break;
	case 'news':
		include_once raiz().'consultations/news/index.php';
		break;
	case 'participate':
		include_once raiz().'consultations/home/consultationsHome.php';
		break;
	case 'sign_up':
		include_once raiz().'consultations/register/signupForm.php';
		break;
	case 'forgotPwd':
		include_once raiz().'consultations/register/recoverForm.php';
		break;
	case 'req':
		include_once raiz().'consultations/register/chPwdForm.php';
		break;
	default:
		include_once raiz().'consultations/news/index.php';
		// include_once raiz().'consultations/home/consultationsHome.php';
		break;			
}





?>


