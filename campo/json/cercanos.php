<?php


	include_once '../../lib/j/j.func.php';

	// print2($_POST);

	$p = $_POST['datos'];

	 echo json_encode(getVisHoySinUsuario($p['distMax'], $p['lat'], $p['lng']));

	function getVisHoySinUsuario($distMax, $latUsr, $lngUsr){

		global $db;

		// echo "getVisHoy   $latUsr, $lngUsr   <br/> ";
		
		$hoy = date('Y-m-d');

		// echo "Fecha getVisHoy : $hoy<br/>";
		$visitas = array();

		switch($_POST['tipoV']){
			case "visitas":
			$visitas = $db->query("SELECT  CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
				CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as valor,
				c.calle, c.numeroExt, c.numeroInt, vv.id as visitaId, c.colonia, c.codigoPostal, c.estatus
				FROM Clientes c 
				LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'visita' 
				AND vv.id = (SELECT id FROM Visitas z 
				WHERE z.clientesId = vv.clientesId 
				AND z.etapa = 'visita' 
				ORDER BY z.fechaRealizacion DESC 
				LIMIT 1)
				WHERE (c.estatus >= 5 AND c.estatus< 38) AND LENGTH(c.lat) > 1 AND LENGTH(c.lng)>1
				")->fetchAll(PDO::FETCH_ASSOC);
			break;

			case "instalaciones":
			$visitas = $db->query("SELECT c.*, c.id as valor, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label,
			cv.bloqueCalif as avComp, vv.id as vvId, v.fecha, 
			v.id as vId, vInst.id as vInstId, vInst.finalizada as vInstFin,
			vrs.id as vrsId, vrs.finalizada as vrsFin
			FROM Clientes c
			LEFT JOIN Visitas v ON v.id = c.visitasId
			LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'visita' 
				AND vv.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vv.clientesId 
					AND z.etapa = 'visita' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vInst ON vInst.clientesId = c.id AND vInst.etapa = 'instalacion' 
				AND vInst.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vInst.clientesId 
					AND k.etapa = 'instalacion' AND (finalizada IS NULL OR finalizada != 1)
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vrs ON vrs.clientesId = c.id AND vrs.etapa = 'reparacion' 
				AND vrs.id = (SELECT id FROM Visitas l 
					WHERE l.clientesId = vrs.clientesId 
					AND l.etapa = 'reparacion' AND (finalizada IS NULL OR finalizada != 1)
					ORDER BY l.fechaRealizacion DESC 
					LIMIT 1)

			LEFT JOIN CalculosVisita cv ON cv.visitasId = vv.id AND cv.bloque = 'comp'
			WHERE ((c.estatus >= 38 AND c.estatus < 48) OR (c.estatus = 55) ) AND c.estatusDoc >= 10 AND LENGTH(c.lat) > 1 AND LENGTH(c.lng)>1
			GROUP BY c.id
		")->fetchAll(PDO::FETCH_ASSOC);
			break;

			case "seguimiento":
			$visitas = $db->query("SELECT  CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
				CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as valor,
				c.calle, c.numeroExt, c.numeroInt, vv.id as visitaId, c.colonia, c.codigoPostal, c.estatus
				FROM Clientes c 
				LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'seguimientoCampo' AND (vv.finalizada != 1 OR vv.finalizada IS NULL)
				AND vv.id = (SELECT id FROM Visitas z 
				WHERE z.clientesId = vv.clientesId 
				AND z.etapa = 'seguimientoCampo' 
				ORDER BY z.fechaRealizacion DESC 
				LIMIT 1) 
				WHERE (c.estatus >= 48 AND c.estatus<= 60) AND LENGTH(c.lat) > 1 AND LENGTH(c.lng)>1
				")->fetchAll(PDO::FETCH_ASSOC);
			break;

		}
		// print2($visitas);

		$ret = [];

		foreach($visitas as $k => $v ){
			$dist = distanciaCoords((float)$latUsr, (float)$lngUsr, (float)$v['lat'], (float)$v['lng']);
			// echo $dist."    ";
			if($dist <= $distMax){
				$v['distancia'] = $dist;
				$v['label'].=" <br>Distancia: ".round($dist, 1). "km";
				$ret[] = $v;
			}
		}

		return $ret;
	}


	function distanciaCoords($lat1, $lng1, $lat2, $lng2) {
		if (($lat1 == $lat2) && ($lng1 == $lng2)) {
			return 0;
		}
		else {
			$rad = 6371;  // el radio de la tierra

			$theta1 = deg2rad($lat1);
			$theta2 = deg2rad($lat2);
			$deltaLat = deg2rad($lat2-$lat1);
			$deltaLng = deg2rad($lng2-$lng1);

			$a = sin($deltaLat / 2) * sin($deltaLat / 2) +  
			     cos($theta1) * cos($theta2) * 
			     sin($deltaLng / 2) * sin($deltaLng / 2);

			$c = 2 * atan2(sqrt($a),sqrt(1-$a));
			$km = $rad * $c;

			return  $km;
		}
	}
