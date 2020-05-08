<?php 
	include_once '../../../../lib/j/j.func.php';

	$stm = $db->prepare("SELECT COUNT(*) FROM Targets WHERE code = ?");

	$stm->execute(array($_POST['code']));
	$cuenta = $stm -> fetch(PDO::FETCH_NUM);

	echo '{"cuenta":"'.$cuenta[0].'"}';



 ?>