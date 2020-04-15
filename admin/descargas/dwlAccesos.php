<?php

	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='accesos.csv'");



	include_once '../../lib/j/j.func.php';

	if(!is_numeric($_POST[cteId])){
		exit("NO TIENES ACCESO");
	}

	$accesos = $db->query("SELECT u.nombre, u.aPat, u.aMat,u.username, l.timestamp AS timestamp, c.nombre as cNom
		FROM LogAccesos l
		LEFT JOIN Usuarios u ON u.id = l.usuariosId
		LEFT JOIN Clientes c ON c.id = u.clientesId
		WHERE u.clientesId = $_POST[cteId] ORDER BY timestamp DESC")->fetchAll(PDO::FETCH_ASSOC);

	// print2($accesos);
	
	$csv = '"Cliente","Usuario","Nombre","Apellido paterno","Acceso"'."\n";

	foreach ($accesos as $a) {
		$csv .= '"'.$a['cNom'].'",';
		$csv .= '"'.$a['username'].'",';
		$csv .= '"'.$a['nombre'].'",';
		$csv .= '"'.$a['aPat'].'",';
		$csv .= '"'.$a['timestamp'].'"';
		$csv .= "\n";
	}

	echo $csv;

	// print2($csv);

?>