<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);

	$pry = $db->query("SELECT * FROM Proyectos WHERE id = $_POST[pryId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	
	$clientes = $db->query("
		SELECT c.*, jc.webId, edo.nombre as edoNom, munc.nombre as muncNom, e.nombre as estatusNom
		FROM Clientes c
		LEFT JOIN JuntasComunitarias jc ON jc.id =c.junta
		LEFT JOIN Estatus e ON e.id = c.estatus
		LEFT JOIN Estados edo ON edo.id = c.estadosId
		LEFT JOIN Municipios munc ON munc.id = c.municipiosId
		WHERE proyectosId = $_POST[pryId]
		ORDER BY c.nombre,c.aPat,c.aMat
	")->fetchAll(PDO::FETCH_ASSOC);

	// print2($clientes);



	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$pry[nombre] - clientes.xls");

	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";

	$csv.= '<ss:Row> '."\n";

	$csv .= '<ss:Cell><ss:Data ss:Type="String">ID USUARIO</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Nombre</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Apellido paterno</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Apellido materno</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estatus</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Calle</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Manzana</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Lote</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Número exterior</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Número interior</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estado</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Municipios</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Entre calles</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Referencia</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Teléfono</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Celular 1</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Celular 2</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Celular 3</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Correo electrónico</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Junta comunitaria</ss:Data></ss:Cell>'."\n";
	$csv .= '</ss:Row>'."\n";

	foreach ($clientes as $c) {
		$csv .= '<ss:Row> '."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['token'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['nombre'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['aPat'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['aMat'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['estatusNom'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['calle'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['manzana'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['lote'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['numeroExt'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['numeroInt'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['edoNom'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['muncNom'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['entreCalles'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['referencia'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['telefono'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['celular1'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['celular2'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['celular3'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['mail'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['webId'].'</ss:Data></ss:Cell>'."\n";
		$csv .= "\n";
		$csv .= '</ss:Row>'."\n";

	}
	$csv .= "</ss:Table> "."\n";
	$csv .= "</ss:Worksheet> "."\n";
	$csv .= "</ss:Workbook> "."\n";

	echo $csv;


?>

