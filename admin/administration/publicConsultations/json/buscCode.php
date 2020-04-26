<?php 
	include_once '../../../../lib/j/j.func.php';

	$stm = $db->prepare("SELECT COUNT(*) FROM publicConsultations WHERE code = ? AND id != ?");

	$stm->execute(array($_POST['code'], $_POST['pcId']));
	$cuenta = $stm -> fetch(PDO::FETCH_NUM);

	echo '{"cuenta":"'.$cuenta[0].'"}';



 ?>