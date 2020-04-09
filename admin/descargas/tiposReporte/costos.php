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
	header("Content-Disposition: attachment; filename='$pry[cNom] - $pry[pNom] - costos y pagos.xls'");

	$visitas = $db->query("SELECT
		c.nombre as cNom,
		p.nombre as pNom,
		rep.nombre as rNom,
		t.POS,
		t.nombre as tNom,
		v.fecha as fecha,
		CASE
			WHEN (rpe.estatus = 100 AND rpe.costo > 0) THEN rpe.costo
			ELSE rep.pagoBase
		END as pagoVis,
		CASE
			WHEN (v.reembolsoTipo = 2) THEN LEAST(v.gasto, v.reembolsoMax)
			WHEN (v.reembolsoTipo = 1) THEN LEAST(v.gasto*(v.reembolso), v.reembolsoMax)
			ELSE 0
		END as pagoReebolso,
		pagoVis.estatus as estatusPagoVis,
		pagoReemb.estatus as estatusPagoReemb,
		CASE
			WHEN (v.reembolsoTipo = 2) THEN DATE(DATE_ADD(v.fecha, INTERVAL +21 DAY))
			WHEN (v.reembolsoTipo = 1) THEN DATE(DATE_ADD(v.fecha, INTERVAL +21 DAY))
			ELSE '- -'
		END as fechaProgPagoVis,
		CASE
			WHEN (v.reembolsoTipo = 2) THEN DATE(DATE_ADD(v.fecha, INTERVAL +14 DAY))
			WHEN (v.reembolsoTipo = 1) THEN DATE(DATE_ADD(v.fecha, INTERVAL +14 DAY))
			ELSE '- -'
		END as fechaProgReemb,
		CASE
			WHEN (pagoReemb.estatus = 30) THEN pagoReemb.fecha
			ELSE '- -'
		END as fechaPagoReemb,
		CASE
			WHEN (pagoVis.estatus = 30) THEN pagoVis.fecha
			ELSE '- -'
		END as fechaPagoVis

		FROM Visitas v
		LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
		LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
		LEFT JOIN Proyectos p ON p.id = rep.proyectosId
		LEFT JOIN Clientes c ON c.id = p.clientesId
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		LEFT JOIN Estatus e ON e.estatus = rot.estatus
		LEFT JOIN RotacionesPagoExt rpe ON rpe.rotacionesId = rot.id
		LEFT JOIN Pagos pagoReemb ON pagoReemb.visitasId = v.id AND pagoReemb.concepto = 1
		LEFT JOIN Pagos pagoVis ON pagoVis.visitasId = v.id AND pagoVis.concepto = 2
		LEFT JOIN Pagos pagoRev ON pagoRev.visitasId = v.id AND pagoRev.concepto = 3

		WHERE p.id = $pryId

		ORDER BY rep.fechaIni DESC, rot.fecha")->fetchAll(PDO::FETCH_ASSOC);
	
	// $csv = '"Cliente","Proyecto","Repeticion","POS",'.
	// 	'"Tienda","Fecha","Pago por visita","Reembolso","Estatus pago visita",'.
	// 	'"Estatus pago reembolso","Fecha programada pago reembolso","fecha programada pago visita",'.
	// 	'"Fecha de pago reembolso","Fecha de pago visita"'."\n";

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
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Pago por visita</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Reembolso</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estatus pago visita</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estatus pago reembolso</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha programada pago reembolso</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">fecha programada pago visita</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de pago reembolso</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de pago visita</ss:Data></ss:Cell>'."\n";
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