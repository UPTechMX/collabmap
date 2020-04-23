<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	$xml = simplexml_load_file(raiz().'externalFiles/'.$_POST['file']);
	$childs = $xml->Document->Folder->children();

	// print2($childs);
	$ok = true;
	$db->beginTransaction();
	foreach ($childs as $c) {

		if(empty($c->Polygon)){
			continue;
		}
		
		
		$pSA['tabla'] = 'Studyarea';
		$pSA['datos']['preguntasId'] = $_POST['pregId'];
		$pSA['datos']['type'] = 'polygon';

		$rSAj = atj(inserta($pSA));
		$rSA = json_decode($rSAj,true);
		if($rSA['ok'] != 1){
			$ok = false;
			$err = 'ERR: RISA 4434';
		}

		if($ok){
			$saId = $rSA['nId'];
			$coordinates = $c->Polygon->outerBoundaryIs->LinearRing->coordinates;
			$coords = $coordinates->__toString();
			$cc = explode("\n", $coords);
			$pp['tabla'] = 'StudyareaPoints';
			$pp['datos']['studyareaId'] = $saId;
				// print2($cc);

			foreach ($cc as $k => $c) {

				if($k == count($cc)-1){
					continue;
				}
				$latlng = explode(',', $c);
				$lat = trim($latlng[1]);
				$lng = trim($latlng[0]);
				$pp['datos']['lat'] = $lat;
				$pp['datos']['lng'] = $lng;
				if(empty($lat) || empty($lng)){
					continue;
				}

				// print2($pp);
				$rppj = atj(inserta($pp));
				$rpp = json_decode($rppj,true);
				if($rpp['ok'] != 1){
					// print2($rppj);
					$ok = false;
					$err = "ERR: IPSA 7679";
					break 2;
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


?>
