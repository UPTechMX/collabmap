<?php

	// include_once '../../lib/j/j.func.php';

	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='Shoppers.xls'");

	$visitas = $db->query("SELECT 
		CONCAT(s.nombre,' ',s.aPat,' ',s.aMat) as nombre,
		s.username,
		s.genero,
		nac.respuesta as nac,
		TIMESTAMPDIFF(YEAR, nac.respuesta, NOW()) as edad,
		tel.respuesta as tel,
		cel.respuesta as cel,
		mail.respuesta as mail,
		mail2.respuesta as mail2,
		edo.nombre as edo,
		munc.nombre as munc,
		ocup.respuesta as ocup,
		emp.respuesta as emp,
		est.respuesta as est,
		civil.respuesta as civil,
		hijos.respuesta as hijos,
		TC.respuesta as TC,
		enc.respuesta as enc,
		banco.respuesta as banco,
		titular.respuesta as titular,
		cta.respuesta as cta,
		CLABE.respuesta as CLABE,
		edoOrig.nombre as edoOrig,
		muncOrig.nombre as muncOrig,
		tipoCta.respuesta as tipoCta,
		s.estrato as NSE

		FROM Shoppers s
		LEFT JOIN InfoShoppers tel ON tel.shoppersId = s.id AND tel.preguntaId = 8
		LEFT JOIN InfoShoppers cel ON cel.shoppersId = s.id AND cel.preguntaId = 12
		LEFT JOIN InfoShoppers mail ON mail.shoppersId = s.id AND mail.preguntaId = 13
		LEFT JOIN InfoShoppers mail2 ON mail2.shoppersId = s.id AND mail2.preguntaId = 14
		LEFT JOIN InfoShoppers edoResp ON edoResp.shoppersId = s.id AND edoResp.preguntaId = 6
		LEFT JOIN Estados edo ON edo.id = edoResp.respuesta
		LEFT JOIN InfoShoppers muncResp ON muncResp.shoppersId = s.id AND muncResp.preguntaId = 7
		LEFT JOIN Municipios munc ON munc.id = muncResp.respuesta
		LEFT JOIN InfoShoppers ocup ON ocup.shoppersId = s.id AND ocup.preguntaId = 20
		LEFT JOIN InfoShoppers emp ON emp.shoppersId = s.id AND emp.preguntaId = 21
		LEFT JOIN InfoShoppers est ON est.shoppersId = s.id AND est.preguntaId = 22
		LEFT JOIN InfoShoppers nac ON nac.shoppersId = s.id AND nac.preguntaId = 19
		LEFT JOIN InfoShoppers civil ON civil.shoppersId = s.id AND civil.preguntaId = 25
		LEFT JOIN InfoShoppers hijos ON hijos.shoppersId = s.id AND hijos.preguntaId = 26
		LEFT JOIN InfoShoppers TC ON TC.shoppersId = s.id AND TC.preguntaId = 30
		LEFT JOIN InfoShoppers enc ON enc.shoppersId = s.id AND enc.preguntaId = 80
		LEFT JOIN InfoShoppers banco ON banco.shoppersId = s.id AND banco.preguntaId = 41
		LEFT JOIN InfoShoppers titular ON titular.shoppersId = s.id AND titular.preguntaId = 42
		LEFT JOIN InfoShoppers cta ON cta.shoppersId = s.id AND cta.preguntaId = 43
		LEFT JOIN InfoShoppers CLABE ON CLABE.shoppersId = s.id AND CLABE.preguntaId = 44
		LEFT JOIN InfoShoppers edoOrigResp ON edoOrigResp.shoppersId = s.id AND edoOrigResp.preguntaId = 45
		LEFT JOIN Estados edoOrig ON edoOrig.id = edoOrigResp.respuesta
		LEFT JOIN InfoShoppers muncOrigResp ON muncOrigResp.shoppersId = s.id AND muncOrigResp.preguntaId = 7
		LEFT JOIN Municipios muncOrig ON muncOrig.id = muncOrigResp.respuesta
		LEFT JOIN InfoShoppers tipoCta ON tipoCta.shoppersId = s.id AND tipoCta.preguntaId = 46


		WHERE (s.username IS NOT NULL) AND s.confirmado = 1
		ORDER BY s.nombre")->fetchAll(PDO::FETCH_ASSOC);


	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";

	
	// $csv = '"Nombre","username","Teléfono de casa","Celular","Correo electrónico","Correo electrónico 2","Estado","Municipio","Ocupación","Empresa","Grado máximo de estudios","Estado civil","# Hijos","Tarjeta de crédito","¿Encuestador?","Insitucion bancaria","Titular","Número de cuenta","CLABE","Estado de origen","Municipio de origen","Tipo de tarjeta","NSE (Calculado)"'."\n";

	$csv.= '<ss:Row> '."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Nombre</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">username</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Género</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha de nacimiento</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Edad</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Teléfono de casa</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Celular</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Correo electrónico</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Correo electrónico 2</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estado</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Municipio</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Ocupación</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Empresa</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Grado máximo de estudios</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estado civil</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String"># Hijos</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Tarjeta de crédito</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">¿Encuestador?</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Insitucion bancaria</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Titular</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Número de cuenta</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">CLABE</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estado de origen</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Municipio de origen</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Tipo de tarjeta</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">NSE (Calculado)</ss:Data></ss:Cell>'."\n";
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