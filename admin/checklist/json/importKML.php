<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	$xml = simplexml_load_file(raiz().'externalFiles/'.$_POST['file']);
	$childs = $xml->Document->Folder->children();


	print2($childs->MultiGeometry);
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
		if(!empty($c->MultiGeometry)){
			// print2($c->MultiGeometry);
			$mg = $c->MultiGeometry;

			foreach ($mg as $g) {
				// print2($mg);
				$rj = PolygonInsert($_POST['pregId'],$g->Polygon);
				$r = json_decode($rj,true);
				if($r['ok'] != 1){
					$ok = false;
				}
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
		// print2($coordinates);
		$coords = $coordinates->__toString();
		$coords = str_replace("\n", '', $coords);
		$coords = preg_replace("/ +/", " ", $coords);
		$coords = preg_replace("/\t+/", "", $coords);
		$coords = trim($coords);
		// echo "\n\n=-=-=-=\n\n";
		// echo $coords;
		// echo "\n\n=-=-=-=\n\n";

		$cc = explode(" ", $coords);
		$latlngs = array();
		foreach ($cc as $k => $c) {
			// echo "$k - $c\n";
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
