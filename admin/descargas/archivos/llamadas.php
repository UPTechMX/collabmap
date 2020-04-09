<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);

	$pry = $db->query("SELECT * FROM Proyectos WHERE id = $_POST[pryId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	
	$clientes = $db->query("
		SELECT c.id, c.token, lh.timestamp, lh.comentarios, lh.id as lhId
		FROM Clientes c
		LEFT JOIN LlamadasHist lh ON lh.clientesId = c.id
		WHERE proyectosId = $_POST[pryId] AND lh.timestamp IS NOT NULL
		ORDER BY c.token
	")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

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
	$csv .= '</ss:Row>'."\n";

	foreach ($clientes as $c) {

		$csv .= '<ss:Row> '."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c[0]['token'].'</ss:Data></ss:Cell>'."\n";
		

		foreach ($c as $lh) {
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$lh['timestamp'].'</ss:Data></ss:Cell>'."\n";	
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$lh['comentarios'].'</ss:Data></ss:Cell>'."\n";	
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.'</ss:Data></ss:Cell>'."\n";	
		}
		$csv .= "\n";
		$csv .= '</ss:Row>'."\n";

	}
	$csv .= "</ss:Table> "."\n";
	$csv .= "</ss:Worksheet> "."\n";
	$csv .= "</ss:Workbook> "."\n";

	echo $csv;


?>

