<?php

	if(!function_exists('raiz')){
		include_once '../../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Projects

	// print2($_POST);
	$ok = true;
	$db->beginTransaction();

	$n = -90;
	$s = 90;
	$e = -180;
	$w = 180;


	$pKML = array();
	$pKML['tabla'] = 'KML';
	$pKML['datos']['projectsId'] = $_POST['prjId'];
	$pKML['datos']['name'] = $_POST['KMLName'];
	$pKML['datos']['idField'] = $_POST['idAttr'];

	$rKMLj = atj(inserta($pKML));
	// print($rKMLj);
	$rKML = json_decode($rKMLj,true);

	if($rKML['ok'] != 1){
		$ok = false;
		$err = 'Err: EIK: 77446';
	}

	$xml = simplexml_load_file(raiz().'externalFiles/'.$_POST['file']);
	
	if($ok){

		$KMLId = $rKML['nId'];


		$childs = $xml->Document->Schema->children();
		// print2($childs);
		$attrs = array();
		foreach ($childs as $c) {
			$pa = array();
			$pa['tabla'] = 'KMLAttributes';

			$pa['datos']['KMLId'] = $KMLId;
			$pa['datos']['name'] = $c['name']->__toString();
			$pa['datos']['type'] = $c['type']->__toString();
			
			$rpaj = inserta($pa);
			$rpa = json_decode($rpaj,true);

			if($rpa['ok'] != 1){
				$ok = false;
				$err = 'Err: EIA:5545';
				break;
			}
			$attrs[$pa['datos']['name']] = $rpa['nId'];
			// echo "-----\n";
			// echo $c['name']->__toString()."\n";
			// echo $c['type']->__toString()."\n";
			// echo "-----\n";
		}
	}

	// print2($attrs);


	if($ok){
		
		$childs = $xml->Document->Folder->children();

		// print2($childs);
		
		foreach ($childs as $c) {
			if(empty($c->Polygon) && empty($c->MultiGeometry)){
				continue;
			}


			if(!empty($c->Polygon)){
				// print2($c->ExtendedData);
				// polygon($c->polygon);
				$data = $c->ExtendedData->SchemaData->children();
				$atts = attributes($data,'$geometriesId',$_POST['idAttr']);

				
				$rj = PolygonInsert($KMLId,$c->Polygon,$atts['id']);
				$r = json_decode($rj,true);
				if($r['ok'] != 1){
					$ok = false;
					$err = 'Err: EIG:786844';
					break 1;
				}

				if($ok){

					foreach ($atts['post'] as $k => $attr) {
						$atts['post'][$k]['datos']['geometriesId'] = $r['nId'];
						$rpj = inserta($atts['post'][$k]);
						$rp = json_decode($rpj,true);
						if($rp['ok'] != 1){
							$ok = false;
							$err = 'Err: EIA:3444';
							break 2;
						}
					}
					// print2($atts['post']);
				}

				
				// $r = json_decode($rj,true);
				// if($r['ok'] != 1){
				// 	$ok = false;
				// }
			}


			if(!empty($c->MultiGeometry)){
				// print2($c->MultiGeometry);
				$data = $c->ExtendedData->SchemaData->children();
				$atts = attributes($data,'$geometriesId',$_POST['idAttr']);
				// break;

				// print2($c->ExtendedData);

				$mg = $c->MultiGeometry;

				foreach ($mg as $g) {
					
					$rj = PolygonInsert($KMLId,$g->Polygon,$atts['id']);
					$r = json_decode($rj,true);
					if($r['ok'] != 1){
						$ok = false;
						$err = 'Err: EIG:786833';
						break 2;
					}

					if($ok){

						foreach ($atts['post'] as $k => $attr) {
							$atts['post'][$k]['datos']['geometriesId'] = $r['nId'];
							$rpj = inserta($atts['post'][$k]);
							$rp = json_decode($rpj,true);
							if($rp['ok'] != 1){
								$ok = false;
								$err = 'Err: EIA:3433';
								break 3;
							}
						}
						// print2($atts['post']);
					}


				}
			}

			
		}
	}

	if($ok){
		$db->query("UPDATE KML SET north = $n, south = $s, east = $e, west = $w WHERE id = $KMLId");
	}


	if($ok){
		echo '{"ok":1}';
		$db->commit();
		// $db->rollBack();
	}else{
		echo '{"ok":0,"err":"'.$err.'"}';
		$db->rollBack();

	}



	function attributes($data,$geometriesId,$idAttr){
		global $attrs;
		$datos = array();
		foreach($data as $d){
			// print2();
			$pa = array();
			$pa['name'] = $d['name']->__toString();
			$pa['tabla'] = 'GeometriesAttributes';
			$pa['datos']['attributeId'] = $attrs[$d['name']->__toString()];
			$pa['datos']['value'] = $d->__toString();
			$pa['datos']['geometriesId'] = $geometriesId;
			$datos[] = $pa;
			if($d['name']->__toString() == $idAttr){
				$id = $d->__toString();
			}
			// print2($pa);
		}

		$r['post'] = $datos;
		$r['id'] = $id;
		// print2($r);
		return $r;

	}



	function PolygonInsert($KMLId,$polygon,$identifier){
		global $db;

		global $n;
		global $s;
		global $e;
		global $w;

		$pSA['tabla'] = 'KMLGeometries';
		$pSA['datos']['KMLId'] = $KMLId;
		$pSA['datos']['identifier'] = $identifier;

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

			$n = max($n,$lat);
			$s = min($n,$lat);
			$e = max($e,$lng);
			$w = min($w,$lng);

		}

		// print2($latlngs);
		$pSA['geo']['type'] = 'polygon';
		$pSA['geo']['field'] = 'geometry';
		$pSA['geo']['latlngs'] = atj([$latlngs]);

		// print2($pSA);

		return atj(inserta($pSA));

	}

