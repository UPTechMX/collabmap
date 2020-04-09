<?php 
	include_once '../../lib/j/j.func.php';

	$stm = $db->prepare("SELECT COUNT(*) FROM Shoppers WHERE email = ?");

	$stm->execute(array($_POST['email']));
	$cuenta = $stm -> fetch(PDO::FETCH_NUM);

	echo '{"cuenta":"'.$cuenta[0].'"}';



 ?>