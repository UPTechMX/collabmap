<?php  

	@session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(5); // checaAcceso analysis;

	// exit();

	// if( !is_numeric($_POST['nivelMax']) || !is_numeric($_POST['padre']) 
	// 	|| !is_numeric($_POST['targetsId']) || !is_numeric($_POST['chkId']) ){
	// 	exit('ERROR');
	// }
	// print2($_POST);
	
	$chkId = $_POST['chkId'];

	$JOINS = getLJTrgt($_POST['nivelMax'],$_POST['padre'],$_POST['targetsId'],'structure');
	// print2($JOINS);

	$info = $db->query("SELECT t.name as tName, p.name as pName
		FROM Targets t
		LEFT JOIN Projects p ON p.id = t.projectsId
		WHERE t.id = $_POST[targetsId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($info);
	// exit();


	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$info[pName] - $info[tName] - ".TR('answers').".kml");

	$name = $info[pName] - $info[tName] - ".TR('answers').";
	// include_once '../../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';


	$puntos = $db->query("SELECT v.id as vId,u.name as uName, v.finishDate, de.nombre as deNom, p.id as pId, p.pregunta,
		t.nombre as typeName, ST_AsGeoJSON(pr.geometry,2) as geom
		FROM Visitas v 
		LEFT JOIN TargetsElems te ON te.id = v.elemId
		LEFT JOIN Targets trg ON trg.id = te.targetsId
		LEFT JOIN DimensionesElem de ON de.id = te.dimensionesElemId
		LEFT JOIN Users u ON u.id = te.usersId
		LEFT JOIN RespuestasVisita rv ON rv.visitasId = v.id
		LEFT JOIN Preguntas p ON p.id = rv.preguntasId
		LEFT JOIN Problems pr ON pr.respuestasVisitaId = rv.id
		LEFT JOIN Tipos t ON t.id = p.tiposId
		WHERE p.tiposId >= 5 AND trg.id = $_POST[targetsId]
		ORDER BY v.id
		")-> fetchAll(PDO::FETCH_ASSOC);

	
	// print2($puntos);
	$strg = '';
	foreach ($puntos as $p) {
		if(empty($p['geom'])){
			continue;
		}

		$geom = json_decode($p['geom'],true);

		if($geom['type'] != 'Point'){
			continue;
		}

		// print2($geom);
		// print2($p['deNom']);
		$tmp ="
		<Placemark>
			<name>$p[deNom]</name>
			<description>
				Usuario : $p[uName]
				visitaId : $p[vId]
				Pregunta : ".strip_tags($p['pregunta'])."
			</description>
			<Point>
				<gx:drawOrder>1</gx:drawOrder>
				<coordinates>".$geom['coordinates'][0].",".$geom['coordinates'][1].",0</coordinates>
			</Point>
		</Placemark>
		";

		$strg .= $tmp;

		// break;


	}

	

	// print2($visitas);
	// exit();



	$csv='<?xml version="1.0" encoding="UTF-8"?>
		<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
		<Document>
			<name>'.$name.'</name>
			<Style id="s_ylw-pushpin">
				<IconStyle>
					<scale>1.1</scale>
					<Icon>
						<href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
					</Icon>
					<hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
				</IconStyle>
			</Style>
			<StyleMap id="m_ylw-pushpin">
				<Pair>
					<key>normal</key>
					<styleUrl>#s_ylw-pushpin</styleUrl>
				</Pair>
				<Pair>
					<key>highlight</key>
					<styleUrl>#s_ylw-pushpin_hl</styleUrl>
				</Pair>
			</StyleMap>
			<Style id="s_ylw-pushpin_hl">
				<IconStyle>
					<scale>1.3</scale>
					<Icon>
						<href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
					</Icon>
					<hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
				</IconStyle>
			</Style>
			<Folder>
				<name>prueba</name>
				<open>1</open>
				STRG

			</Folder>
		</Document>
		</kml>
';

	$csv = str_replace('STRG', $strg, $csv);
	// echo htmlentities($csv);
	echo $csv;



?>