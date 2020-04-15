<?php  

	include_once '../j/j.func.php';

	$stm = $db -> prepare("SELECT id as val, nombre as nom, 'clase' as clase FROM Municipios WHERE estadosId = ? ORDER BY nombre");

	$stm->execute(array($_POST['edoId']));
	// $stm->execute(array($_POST['username']));


	$muncs = $stm ->fetchAll(PDO::FETCH_ASSOC);

	echo atj($muncs);


?>