<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	$xml = simplexml_load_file(raiz().'externalFiles/'.$_POST['file']);
	$childs = $xml->Document->Folder->children();


	// print2($childs);
	$ok = true;
	$db->beginTransaction();
	foreach ($childs as $c) {

		if(empty($c->Polygon) && empty($c->MultiGeometry)){
			continue;
		}

		if(!empty($c->Polygon)){
			$rj = PolygonInsert($_POST['pregId'],$c->Polygon);
			$r = json_decode($rj,true);
			if($r['ok'] != 1){
				$ok = false;
			}
		}
		
	}

	if($ok){
		echo '{"ok":1}';
		$db->commit();
	}else{
		echo '{"ok":0,"err":"'.$err.'"}';
		$db->rollBack();

	}

	function PolygonInsert($pregId,$polygon){
		global $db;

		$pSA['tabla'] = 'Studyarea';
		$pSA['datos']['preguntasId'] = $pregId;
		$pSA['datos']['type'] = 'polygon';

		// print2($polygon);

		$coordinates = $polygon->outerBoundaryIs->LinearRing->coordinates;
		$coords = $coordinates->__toString();
		$cc = explode("\n", $coords);
		$latlngs = array();
		foreach ($cc as $k => $c) {

			if($k == count($cc)-1){
				continue;
			}
			$latlng = explode(',', $c);
			$lat = trim($latlng[1]);
			$lng = trim($latlng[0]);

			if(empty($lat) || empty($lng)){
				continue;
			}

			$tmp['lat'] = $lat;
			$tmp['lng'] = $lng;
			$latlngs[] = $tmp;

		}

		// print2($latlngs);
		$pSA['geo']['type'] = 'polygon';
		$pSA['geo']['field'] = 'geometry';
		$pSA['geo']['latlngs'] = atj([$latlngs]);

		// print2($pSA);

		return atj(inserta($pSA));


	}

?>
