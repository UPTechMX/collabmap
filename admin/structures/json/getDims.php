<?php  

	include_once '../../lib/j/j.func.php';

	// print2($_POST);
	// echo "SELECT id as val, nombre as nom, 'clase' as clase FROM DimensionesElem WHERE padre = ? ";
	$stm = $db -> prepare("SELECT id as val, nombre as nom, 'clase' as clase FROM DimensionesElem WHERE padre = ? ");

	$stm->execute(array($_POST['padre']));
	// $stm->execute(array($_POST['username']));


	$muncs = $stm ->fetchAll(PDO::FETCH_ASSOC);

	echo atj($muncs);


?>