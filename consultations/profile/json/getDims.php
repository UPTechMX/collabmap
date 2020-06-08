<?php
	session_start();

	include_once '../../../lib/j/j.func.php';
	checaAccesoConsult();// checaAcceso Consultations
	
	$elems = $db->query("SELECT id as val, nombre as nom, 'clase' as clase
		FROM DimensionesElem 
		WHERE padre = $_POST[padre]
		ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

	echo atj($elems);


?>