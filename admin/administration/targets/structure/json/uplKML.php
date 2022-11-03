<?php

	if(!function_exists('raiz')){
		include_once '../../../../../lib/j/j.func.php';
	}

	// exit();
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
	$pKML['datos']['elemId'] = $_POST['elemId'];
	$pKML['datos']['type'] = $_POST['type'];
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

		$existe = $xml->Document->Schema;
		$childs = $xml->Document->Schema->children();
		// print2($childs);
		$attrs = array();
		$childs = !empty($existe)?$childs:array();
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

				
				$rj = PolygonInsert($KMLId,$c->Polygon,$atts['id'],true,$data);
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
				// print2($_POST);
				if($_POST['idAttr'] != -1){
					$data = $c->ExtendedData->SchemaData->children();
					$atts = attributes($data,'$geometriesId',$_POST['idAttr']);
				}else{
					$atts = array();
					$atts['id'] = -1;
				}
				// break;

				// print2($c->ExtendedData);

				$mg = $c->MultiGeometry;

				$wkt = 'Multipolygon(';
				foreach ($mg->Polygon as $g) {
					$rj = PolygonInsert($KMLId,$g,$atts['id'],false,$data);
					$latlngs = json_decode($rj,true);

					// print2($rj);
					foreach ($latlngs as $latlng) {
						$coordsL = "(";
						$lat1 = "";
						foreach($latlng as $k => $ll){
							// print2($ll);
							$lat = $ll['lat'];
							$lng = $ll['lng'];
							if(is_numeric($lat) && is_numeric($lng)){
								if($coordsL == "("){
									$lat1 = $lat;
									$lng1 = $lng;
									// echo ")))((((";
								}

								$coordsL .= "$lng $lat, ";

							}	
						}
						$coordsL .= ($lat1 != "" && ($lat1 != $lat || $lng1 != $lng) )?
							"$lng1 $lat1, ":"";
						$coordsL = trim($coordsL,', ');
						$coordsL .= ")";

					}

					$wkt .= "($coordsL),";
					
					// $r = json_decode($rj,true);
					// if($r['ok'] != 1){
					// 	$ok = false;
					// 	$err = 'Err: EIG:786833';
					// 	break 2;
					// }

					// if($ok){
					// 	if($_POST['idAttr'] == -1){
					// 		continue;
					// 	}
					// 	foreach ($atts['post'] as $k => $attr) {
					// 		$atts['post'][$k]['datos']['geometriesId'] = $r['nId'];
					// 		$rpj = inserta($atts['post'][$k]);
					// 		$rp = json_decode($rpj,true);
					// 		if($rp['ok'] != 1){
					// 			$ok = false;
					// 			$err = 'Err: EIA:3433';
					// 			break 3;
					// 		}
					// 	}
					// 	// print2($atts['post']);
					// }


				}

				$wkt = trim($wkt,',');
				$wkt .= ")";

				$pSA['tabla'] = 'KMLGeometries';
				$pSA['datos']['KMLId'] = $KMLId;
				$pSA['datos']['identifier'] = $atts['id'];
				$pSA['geo']['type'] = 'multipolygon';
				$pSA['geo']['field'] = 'geometry';
				$pSA['geo']['wkt'] = $wkt;
				$rrj = atj(inserta($pSA));
				$rr = json_decode($rrj,true);
				// print2($rr);
				if($rr['ok'] != 1){
					$ok = false;
					$err = 'Err: EIA:3433';
					break 1;
				}




				if($ok){
					if($_POST['idAttr'] == -1){
						continue;
					}
					foreach ($atts['post'] as $k => $attr) {
						$atts['post'][$k]['datos']['geometriesId'] = $rr['nId'];
						$rpj = inserta($atts['post'][$k]);
						$rp = json_decode($rpj,true);
						// print2($rpj);
						if($rp['ok'] != 1){
							$ok = false;
							$err = 'Err: EIA:3435';
							break 2;
						}
					}
					// print2($atts['post']);
				}


				// echo "---- $wkt ----\n\n";
			}

			
		}
	}

	if($ok){
		$db->query("UPDATE KML SET north = $n, south = $s, east = $e, west = $w WHERE id = $KMLId");
	}


	if($ok){
		try {
			$folder = '/usr/share/geoserver/data_dir/gwc/'.$geoserverWorkSpaceName.'_KMLGeometries';
			delDirContent($geoserverWorkSpaceName);
		} catch (Exception $e) {
			
		}
		try {
			$folder = '/usr/local/geoserver/data_dir/gwc/'.$geoserverWorkSpaceName.'_KMLGeometries';
			delDirContent($geoserverWorkSpaceName);
		} catch (Exception $e) {
			
		}
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



	function PolygonInsert($KMLId,$polygon,$identifier,$inserta,$data = array()){
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
		$coords22 = $coordinates->__toString();
		$coords = $coordinates->__toString();
		$coords = str_replace("\n", '', $coords);
		$coords = preg_replace("/ +/", " ", $coords);
		$coords = preg_replace("/\t+/", "", $coords);
		$coords = trim($coords);
		// if($data[0] == 'C2110_4' || $data[0] == 'C2011_1' ){
		// 	echo "====".$data[0]."====\n\n";
		// 	echo "\n\n=-=-=-=\n\n";
		// 	echo $coords;
		// 	echo "\n\n=-=-=-=\n\n";
		// 	echo $coords22;
		// 	echo "\n\n=-=-=-=\n\n";
		// }


		$cc = explode(" ", $coords);
		$latlngs = array();

		$i = 0;
		foreach ($cc as $k => $c) {
			// echo "$k - $c\n";
			// if($k == count($cc)-1){
			// 	echo "\nK_n-1:\n";
			// 	print2($c);
			// 	continue;
			// }
			$latlng = explode(',', $c);
			$lat = trim($latlng[1]);
			$lng = trim($latlng[0]);


			if($k == 0){
				$lat0 = $lat;
				$lng0 = $lng;
			}
			if($k == count($cc)-1){
				$latN = $lat;
				$lng0 = $lng;

			}

			if(empty($lat) || empty($lng)){
				continue;
			}

			$tmp['lat'] = $lat;
			$tmp['lng'] = $lng;
			$latlngs[] = $tmp;

			$n = max($n,$lat);
			$s = min($s,$lat);
			$e = max($e,$lng);
			$w = min($w,$lng);

		}

		if($lat0 != $latN || $lng0 != $lngN){
			$tmp['lat'] = $lat0;
			$tmp['lng'] = $lng0;
			$latlngs[] = $tmp;
		}

		// print2($latlngs);
		$pSA['geo']['type'] = 'polygon';
		$pSA['geo']['field'] = 'geometry';
		$pSA['geo']['latlngs'] = atj([$latlngs]);

		// print2($pSA);

		if($inserta){
			return atj(inserta($pSA));
		}else{
			return atj([$latlngs]);
		}

	}
