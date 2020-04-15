<?php

	// include_once '../../lib/j/j.func.php';

	// $pryId = 8;
	$sql = "SELECT p.nombre as pNom, c.nombre as cNom
		FROM Proyectos p
		LEFT JOIN Clientes c ON c.id = p.clientesId
		WHERE p.id = $pryId";
	// echo $sql;
	$pry = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='$pry[cNom] - $pry[pNom] - tipo de asignacion.csv'");

	$visitas = $db->query("SELECT 

		c.nombre as cNom,
		p.nombre as pNom,
		rep.nombre as rNom,
		t.POS,
		t.nombre as tNom,
		CONCAT(s.nombre,' ',s.aPat,' ',s.aMat) as shopper,
		s.calificacion,
		e.nombre as estatus,
		CASE
			WHEN vh.estatus = 21 THEN 'Asignada por personal de shoppers'
			WHEN vh.estatus = 22 THEN 'Pedida por el shopper'
			ELSE 'Asignada por personal de shoppers'
		END as tipoCanc

		FROM Visitas v
		WHERE (s.username IS NOT NULL) AND (vh.estatus = 21 OR vh.estatus = 22)
		AND rep.proyectosId = $pryId")->fetchAll(PDO::FETCH_ASSOC);


	
	$csv = '"Cliente","Proyecto","Repeticion","POS",'.
		'"Tienda","Shopper","Calificacion general del shopper","Estatus actual de la visita","Tipo de asignacion"'."\n";

	// print2($visitas);

	foreach ($visitas as $datos) {
		foreach ($datos as $dato) {
			$csv .= '"'.$dato.'",';
		}
		$csv .= "\n";
	}

	echo $csv;

	// print2($csv);

?>