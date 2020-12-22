<?php  
	ini_set('memory_limit', '512M');

	session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	// checaAcceso(5); // checaAcceso analysis;

	// exit();

	if( !is_numeric($_REQUEST['nivelMax']) || !is_numeric($_REQUEST['padre']) 
		|| !is_numeric($_REQUEST['targetsId']) || !is_numeric($_REQUEST['chkId']) ){
		exit('ERROR');
	}

	$usrId = $_REQUEST['u'];
	$h = '$2y$10$'.$_REQUEST['h'];

	
	$v = password_verify("CM_$usrId",$h);

	if(!$v){
		exit('BAD_USER');
	}

	// print2($_REQUEST);
	
	$chkId = $_REQUEST['chkId'];

	$JOINS = getLJTrgt($_REQUEST['nivelMax'],$_REQUEST['padre'],$_REQUEST['targetsId']);
	// print2($JOINS);

	$info = $db->query("SELECT t.name as tName, p.name as pName
		FROM Targets t
		LEFT JOIN Projects p ON p.id = t.projectsId
		WHERE t.id = $_REQUEST[targetsId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($info);
	// exit();


	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$info[pName] - $info[tName] - ".TR('answers').".csv");


	// include_once '../../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';


	$chkId = isset($chkId)?$chkId:23;

	$params['camposInt'] = "v.Id as vId, v.finishDate";
	$params['grpInt'] = "GROUP BY v.id";
	$params['camposExt'] = "*";
	$params['where'] = " AND v.finalizada = 1 AND type = 'trgt' AND $JOINS[wDE]";
	$params['orderExt'] = "ORDER BY vId";
	$params['JOINS'] = "
		LEFT JOIN TargetsElems te ON te.id = v.elemId
		$JOINS[LJ]";

	$visitas = promTotComp([$_REQUEST['pryId']],$params,$_REQUEST['etapa']);

	// print2($visitas);
	// exit();


	$csv = '';

	$dims = $db->query("SELECT * FROM Dimensiones 
		WHERE elemId = $_REQUEST[targetsId] AND type='structure' ")->fetchAll(PDO::FETCH_ASSOC);



	$bloques = $db->query("SELECT * FROM Bloques 
		WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	// $csv .= ' "POS","fecha","hora entrada","hora salida", "nombre",'.$csvDims;
	foreach ($dims as $d) {
		$csv .= '"'.$d['nombre'].'"'.",";	
	}
	
	$col = array();
	$i = count($dims);
	foreach ($bloques as $bId=> $b) {
		$csv .= '"BLOQUE"'.","; $col[$i++] = array('tipo'=>"nada");
		$csv .= '"'.strip_tags($b['nombre']).'"'.","; 
			$col[$i++] = array('tipo'=>"bloque",'identificador'=>$b['identificador']);
		$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($areas as $a) {
			$csv .= '"AREA"'.","; $col[$i++] = array('tipo'=>"nada");
			$csv .= '"'.strip_tags($a['nombre']).'"'.","; 
				$col[$i++] = array('tipo'=>"area",'identificador'=>$a['identificador'],'bId'=>$b['identificador']);;
			$csv .= '"PREGUNTAS"'.","; $col[$i++] = array('tipo'=>"nada");

			$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
				t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($pregs as $p) {
				// print2($p);
				switch ($p['tsiglas']) {
					case 'sub':
						$csv .= '"SUBAREA"'.","; $col[$i++] = array('tipo'=>"nada");
						$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
							$col[$i++] = array('tipo'=>'subArea','id'=>$p['id'],'campo'=>'valor','identificador'=>$p['identificador']);;
						$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
							t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($subpregs as $sp) {
							switch ($sp['tsiglas']) {
								case 'mult':
									$csv .= '"'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'"'.",";
									// $csv .= '"valor"'.",";
									$csv .= '"justificacion"'.",";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									break;
								case 'num':
									$csv .= '"'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'"'.",";
									// $csv .= '"valor"'.",";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id']."_val";
									if($p['justif'] == 1){
										$csv .= '"justificacion"'.",";
										$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
												'identificador'=>$sp['identificador']);
									}
									break;
								case 'spatial':
								case 'cm':
									$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);

									break;
								case 'op':
									$csv .= '"'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'"'.",";
									$csv .= '"lat"'.",";
									$csv .= '"lng"'.",";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$i = $i+2;

									break;

								
								default:
									$csv .= '"'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'"'.",";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									break;
							}
							
						}
						break;
					case 'mult':
						$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
						// $csv .= '"valor"'.",";
						$csv .= '"justificacion"'.",";
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id'];
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_val";
						$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_justif";

						break;
					case 'num':
						$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
						// $csv .= '"valor"'.",";

						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
								'identificador'=>$p['identificador']);
						if($p['justif'] == 1){
							$csv .= '"justificacion"'.",";
							$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
								'identificador'=>$p['identificador']);//$p['id']."_justif";
						}
						break;
					case 'spatial':
					case 'cm':
						$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
						$col[$i++] = array('tipo'=>'spatial','id'=>$p['id'],'identificador'=>$p['identificador']);
						break;
					case 'op':
						$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
						$csv .= '"lat"'.",";
						$csv .= '"lng"'.",";
						$col[$i++] = array('tipo'=>'op','id'=>$p['id'], 'identificador'=>$p['identificador']);
						$i = $i+2;

						break;
					default:
						$csv .= '"'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'"'.",";
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);

						break;
				}
			}
		}
	}


	// $csv .= '"Shopper"'.",";


	// print2($col);
	$csv = str_replace('<p>', ' ', $csv);
	$csv = str_replace('</p>', ' ', $csv);
	$csv = str_replace('<b>', ' ', $csv);
	$csv = str_replace('</b>', ' ', $csv);
	$csv = str_replace('<br>', ' ', $csv);
	$csv = str_replace('<br/>', ' ', $csv);
	$csv = str_replace('<u>', ' ', $csv);
	$csv = str_replace('</u>', ' ', $csv);
	$csv = str_replace('&nbsp;', ' ', $csv);
	$csv = rtrim($csv,',');
	// $csv .="\n";
	$csv .= "\n";


	$estructura = estructura($chkId);
	// print2($estructura);
	foreach ($visitas as $k =>$v) {
		
		// if($v['POS'] != 'Mont-PR-02'){continue;} // BORRAR;
		// print2($v);

		// if($k == 20){
		// 	break;
		// }

		$chk2 = new CheckList($v['vId']);
		// print2($chk2->id);
		if($chk2->id != $chkId){continue;}

		// $shopper = $db->query("SELECT s.*, v.resumen as vRes
		// 	FROM Visitas v 
		// 	LEFT JOIN Shoppers s ON s.id = v.shoppersId
		// 	WHERE v.id = $v[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		// print2($shopper);

		$vId = $v['vId'];		

		// print2($v);
		// print2($dims);





		$targetElem = $db->query("SELECT te.* 
			FROM Visitas v
			LEFT JOIN TargetsElems te ON v.elemId = te.id
			WHERE v.id = $vId
		")->fetchAll(PDO::FETCH_ASSOC)[0];
		// print2($targetElem);

		$elem = $targetElem['dimensionesElemId'];
		$struct = [];
		for ($i=count($dims); $i > 0 ; $i--) { 
			if(!empty($elem)){
				$dimensionesElem = $db->query("SELECT * FROM DimensionesElem WHERE id = $elem")->fetchAll(PDO::FETCH_ASSOC)[0];
				$elem = $dimensionesElem['padre'];
				$struct[$i] = $dimensionesElem['nombre'];
				// print2($dimensionesElem);
			}else{
				break;
			}
		}
		ksort($struct);
		
		foreach ($struct as $s) {
			# code...
			$csvL .= '"'.$s.'"'.",";
		}


		$pregs = resultados($vId,$estructura);
		$calc = calculos($pregs,$estructura);
		// print2($calc);
		// print2($pregs);


		// if(is_numeric($calc['chk']['prom'])){
		// 	$csvL .= '"'.($calc['chk']['prom']*100).'"'.",";
		// }else{
		// 	$csvL .= '"-"'.",";
		// }

		// $csvL .= '"'.$v['resumen'].'"'.",";
		// print2($pregs);
		$pond = $calc['chk']['max'] != 0 ? 100/$calc['chk']['max'] : 0;

		foreach ($col as $c) {
			// echo "$c[tipo]<br/>";
			// print2($c);
			switch ($c['tipo']) {
				case "nada":
					// echo "nada<br/>";
					$csvL .= '""'.",";
					break;
				case 'bloque':
					if( is_numeric($calc['bloques'][$c['identificador']]['prom']) ){
						$csvL .= '""'.","; 
					}else{
						$csvL .= '""'.",";
					}
					break;
				case 'area':
					if(is_numeric($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'])){
						$csvL .= '""'.",";
					}else{
						$csvL .= '""'.",";
					}
					break;
				case 'subArea':
					$pond = $calc['bloques'][$pregs[$c['identificador']]['bloque']]['areas'][$pregs[$c['identificador']]['area']]['pond'];
					if(is_numeric($pond)){					
						$csvL .= '""'.",";
					}else{
						$csvL .= '""'.",";
					}
					// $csvL .= '"SUBAREA"'.",";

					break;
				case 'pregResp':
					// print2($pregs[$c['identificador']]);
					$csvL .= '"'.$pregs[$c['identificador']]['nomResp'].'"'.",";
					break;
				case 'spatial':
					// print2($pregs[$c['identificador']]);
					$csvL .= '"spatial"'.",";
					break;
				case 'op':
					// print2($pregs[$c['identificador']]);
					$rvId = $pregs[$c['identificador']]['respuestasVisitaId'];
					$sql = "SELECT ST_AsGeoJSON(geometry) as geometry FROM Problems WHERE RespuestasVisitaId = '$rvId'";

					$pointJ = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
					$point = json_decode($pointJ['geometry'],true);
					$coordinates = $point['coordinates'];
					// print2($coordinates);

					$csvL .= '""'.",";
					$csvL .= '"'.$coordinates[1].'"'.",";
					$csvL .= '"'.$coordinates[0].'"'.",";
					// print2($coordinates);
					// echo "<br/>=====$rvId=====<br/>";
					// echo '""'."<br/>";
					// echo '"'.$coordinates[1].'"'."<br/>";
					// echo '"'.$coordinates[0].'"'."<br/>";
					break;
				case 'pregVal':
					$pond = $calc['bloques'][$pregs[$c['identificador']]['bloque']]['areas'][$pregs[$c['identificador']]['area']]['pond'];
					$tipo = $pregs[$c['identificador']]['influyeValor'] == 1 && is_numeric($pregs[$c['identificador']]['valPreg'])?
						'Number':
						'String';
					if(is_numeric($pond)){
						$valPond = is_numeric($pregs[$c['identificador']]['valPreg'])?$pregs[$c['identificador']]['valPreg']*$pond:'NA';
					}else{
						$valPond = 'NA';
					}
					// print2($pregs[$c['identificador']]);
					// print2($pond);
					if($tipo == 'Number'){
						$tipo = is_numeric($valPond)?'Number':'String';
					}
					// $csvL .= '<ss:Cell><ss:Data ss:Type="'.$tipo.'">'.
					// 	($pregs[$c['identificador']]['influyeValor'] == 1 ? $valPond:'-').
					// 	'"'.",";
					// print2($c);
					// break 2;
					break;
				case 'pregJustif':
					$csvL .= '"'.$pregs[$c['identificador']]['justificacion'].'"'.",";
					break;
				default:
					break;
			}
		}



		// $csvL .= '"'."$shopper[nombre] $shopper[aPat] $shopper[aMat]".'"'.",";

		$csvL = str_replace('<p>', ' ', $csvL);
		$csvL = str_replace('</p>', ' ', $csvL);
		$csvL = str_replace('<b>', ' ', $csvL);
		$csvL = str_replace('</b>', ' ', $csvL);
		$csvL = str_replace('<br>', ' ', $csvL);
		$csvL = str_replace('<br/>', ' ', $csvL);
		$csvL = str_replace('<u>', ' ', $csvL);
		$csvL = str_replace('</u>', ' ', $csvL);
		$csvL = str_replace('&nbsp;', ' ', $csvL);
		$csvL = rtrim($csvL,',');
		$csvL .= "\n";

		$csv .= $csvL;
		// echo "$csvL<br/>";
		// break;
	}


	// print2($visitas);

	echo $csv;



?>