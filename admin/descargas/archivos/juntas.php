<?php
	session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);

	$pry = $db->query("SELECT * FROM Proyectos WHERE id = $_POST[pryId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	
	$juntas = $db->query("
		SELECT j.id as idJunta, j.webId, j.fecha, j.numAsistentes, j.nombreTallerista, j.talleristasApoyo, ubs.*
		FROM JuntasComunitarias j
		LEFT JOIN (SELECT u.*, edo.nombre as edoNom, munc.nombre as muncNom FROM Ubicaciones u  
			LEFT JOIN Estados edo ON edo.id = u.estadosId
			LEFT JOIN Municipios munc ON munc.id = u.municipiosId) ubs ON j.Ubicaciones_id = ubs.id
		WHERE Proyectos_id = $_POST[pryId]
		ORDER BY j.fecha DESC
	")->fetchAll(PDO::FETCH_ASSOC);


	foreach($juntas as $k=>$j){
		$count = $db->query("SELECT COUNT(*) as count FROM Clientes WHERE junta=$j[idJunta]")->fetch(PDO::FETCH_COLUMN);
		$juntas[$k]['registros'] = $count;
		$fonds = $db->query("SELECT GROUP_CONCAT(DISTINCT aa.nombre SEPARATOR ', ') FROM (SELECT jf.JuntasComunitarias_id, nombre FROM `JuntasComunitarias_x_Fondeadores` jf LEFT JOIN Fondeadores f ON jf.Fondeadores_id = f.id WHERE jf.JuntasComunitarias_id=$j[idJunta]) aa GROUP BY aa.JuntasComunitarias_id")->fetch(PDO::FETCH_COLUMN);
		// echo "<br>Con $j[idJunta] cuenta es $count y ...";
		$juntas[$k]['fondeadores'] = $count;
		// print2($fonds);
	}

	// print2($juntas);
	// return;



	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$pry[nombre] - juntas.xls");

	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";

	$csv.= '<ss:Row> '."\n";

	$csv .= '<ss:Cell><ss:Data ss:Type="String">ID JUNTA (web id)</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de la junta</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Num Asistentes</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Num Registros</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Tallerista</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Talleristas Apoyo</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Financiadores</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Nombre sitio</ss:Data></ss:Cell>'."\n";
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
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Celular</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Observaciones</ss:Data></ss:Cell>'."\n";
	$csv .= '</ss:Row>'."\n";

	foreach ($juntas as $c) {
		$csv .= '<ss:Row> '."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['webId'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['fecha'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['numAsistentes'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['registros'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['nombreTallerista'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['talleristasApoyo'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['fondeadores'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['nombre'].'</ss:Data></ss:Cell>'."\n";
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
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['celular'].'</ss:Data></ss:Cell>'."\n";
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$c['observaciones'].'</ss:Data></ss:Cell>'."\n";
		$csv .= "\n";
		$csv .= '</ss:Row>'."\n";

	}
	$csv .= "</ss:Table> "."\n";
	$csv .= "</ss:Worksheet> "."\n";
	$csv .= "</ss:Workbook> "."\n";

	echo $csv;


?>

