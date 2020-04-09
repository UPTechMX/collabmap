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
	header("Content-Disposition: attachment; filename='$pry[cNom] - $pry[pNom] - visitas faltantes.xls'");

	$visitas = $db->query("SELECT
		c.nombre as cNom,
		p.nombre as pNom,
		rep.nombre as rNom,
		t.POS,
		t.nombre as tNom,
		edo.nombre as edo,
		munc.nombre as munc, 
		rot.fecha as fecha,
		rot.fechaLimite as fechaLimite,
		e.nombre as estatus

		FROM Rotaciones rot
		LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
		LEFT JOIN Proyectos p ON p.id = rep.proyectosId
		LEFT JOIN Clientes c ON c.id = p.clientesId
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		LEFT JOIN Estados edo ON edo.id = t.estado
		LEFT JOIN Municipios munc ON munc.id = t.municipio
		LEFT JOIN Estatus e ON e.estatus = rot.estatus

		WHERE (rot.estatus >= 0 AND rot.estatus < 20 ) AND rot.estatus != 4
		AND p.id = $pryId

		ORDER BY rep.fechaIni DESC, rot.fecha")->fetchAll(PDO::FETCH_ASSOC);


	
	// $csv = '"Cliente","Proyecto","Repeticion","POS",'.
	// 	'"Tienda","Estado","Municipio","Fecha","Fecha límite","Estatus"'."\n";

	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";

	$csv.= '<ss:Row> '."\n";

	$csv .= '<ss:Cell><ss:Data ss:Type="String">Cliente</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Proyecto</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Repeticion</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">POS</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Tienda</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estado</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Municipio</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha límite</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estatus</ss:Data></ss:Cell>'."\n";

	$csv .= '</ss:Row>'."\n";

	// print2($visitas);

	foreach ($visitas as $datos) {
		$csv .= '<ss:Row> '."\n";
		foreach ($datos as $dato) {
			// $csv .= '"'.$dato.'",';
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$dato.'</ss:Data></ss:Cell>'."\n";

			// $csv .= '"'.$dato.'",';
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