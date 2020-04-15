<?php



	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='Facturaciones y pagos.xls'");

	$visitas = $db->query("SELECT 
		c.nombre as cNom, 
		p.nombre as pNom, 
		r.nombre as rNom,
		r.fechaMaxFact as fechaMaxFact,
		CASE
			WHEN r.facturado = 1 THEN 'Sí'
			WHEN r.facturado = 0 THEN 'No'
			WHEN r.facturado IS NULL THEN 'No'
			ELSE 'No'
		END as facturado,
		r.fechaFact as fechaFact,
		CASE
			WHEN r.pagado = 1 THEN 'Sí'
			WHEN r.pagado = 0 THEN 'No'
			WHEN r.pagado IS NULL THEN 'No'
			ELSE 'No'
		END as pagado,
		r.fechaPago as fechaPago

		FROM Repeticiones r
		LEFT JOIN Proyectos p ON p.id = r.proyectosId
		LEFT JOIN Clientes c ON c.id = p.clientesId


		WHERE (p.finalizado != 1 || p.finalizado IS NULL) 
		ORDER BY c.nombre, p.nombre,r.fechaIni DESC")->fetchAll(PDO::FETCH_ASSOC);


	
	// $csv = '"Cliente","Proyecto","Repeticion","Facturado","Pagado",'."\n";
	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";

	$csv.= '<ss:Row> '."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Cliente</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Proyecto</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Repeticion</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha máxima de facturación</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Facturado</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha facturación</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Pagado</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de pago</ss:Data></ss:Cell>'."\n";
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