<?php 
	include_once '../../lib/j/j.func.php';

	$stm = $db->prepare("SELECT COUNT(*) FROM Shoppers WHERE username = ?");

	$stm->execute(array($_POST['username']));
	$cuenta = $stm -> fetch(PDO::FETCH_NUM);

	echo '{"cuenta":"'.$cuenta[0].'"}';



 ?>