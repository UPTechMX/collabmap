<?php


	$pry = $db->query("SELECT p.nombre as pNom, c.nombre as cNom
		FROM Proyectos p
		LEFT JOIN Clientes c ON c.id = p.clientesId
		WHERE p.id = $pryId")->fetchAll(PDO::FETCH_ASSOC)[0];

	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='$pry[cNom] - $pry[pNom].xls'");

	$visitas = $db->query("SELECT 

		c.nombre as cNom,
		p.nombre as pNom,
		rep.nombre as rNom,
		v.fecha,
		v.fechaIngreso,
		t.POS,
		t.nombre as tNom,
		CONCAT(s.nombre,' ',s.aPat,' ',s.aMat) as shopper,
		v.shopperCalif,
		s.calificacion,
		e.nombre as estatus

		FROM Visitas v
		LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
		LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
		LEFT JOIN Proyectos p ON p.id = rep.proyectosId
		LEFT JOIN Clientes c ON c.id = p.clientesId
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		LEFT JOIN Shoppers s ON s.id = v.shoppersId
		LEFT JOIN Estatus e ON e.estatus = v.aceptada
		WHERE (s.username IS NOT NULL) AND v.shopperCalif IS NOT NULL 
		AND rep.proyectosId = $pryId")->fetchAll(PDO::FETCH_ASSOC);


	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";

	// $csv = '"Cliente","Proyecto","Repeticion","Fecha de la visita","Fecha de ingreso","POS",'.
	// 	'"Tienda","Shopper","Calificacion del shopper en la visita","Calificacion general del shopper","Estatus"'."\n";
	$csv.= '<ss:Row> '."\n";

	$csv .= '<ss:Cell><ss:Data ss:Type="String">Cliente</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Proyecto</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Repeticion</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de la visita</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de ingreso</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">POS</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Tienda</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Shopper</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Calificacion del shopper en la visita</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Calificacion general del shopper</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estatus</ss:Data></ss:Cell>'."\n";
	$csv .= '</ss:Row>'."\n";

	// print2($visitas);

	foreach ($visitas as $datos) {
		$csv .= '<ss:Row> '."\n";
		foreach ($datos as $dato) {
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$dato.'</ss:Data></ss:Cell>'."\n";
		}
		$csv .= "\n";
		$csv .= '</ss:Row>'."\n";
	}

	$csv .= "</ss:Table> "."\n";
	$csv .= "</ss:Worksheet> "."\n";
	$csv .= "</ss:Workbook> "."\n";


	echo $csv;

	// print2($csv);

?>