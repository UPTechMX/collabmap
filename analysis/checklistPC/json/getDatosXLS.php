<?php  

	session_start();
	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(5); // checaAcceso analysis;

	// exit();

	if( !is_numeric($_POST['pcId']) || !is_numeric($_POST['chkId']) ){
		exit('ERROR');
	}
	// print2($_POST);
	
	$chkId = $_POST['chkId'];

	// print2($JOINS);

	$info = $db->query("SELECT pc.name as pcName, p.name as pName
		FROM PublicConsultations pc
		LEFT JOIN Projects p ON p.id = pc.projectsId
		WHERE pc.id = $_POST[pcId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($info);
	// exit();


	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$info[pName] - $info[tName] - ".TR('answers').".xls");


	// include_once '../../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';
	include_once raiz().'lib/php/checklist.php';


	$chkId = isset($chkId)?$chkId:23;

	$params['camposInt'] = "v.Id as vId, v.finishDate";
	$params['grpInt'] = "GROUP BY v.id";
	$params['camposExt'] = "*";
	$params['where'] = " AND v.finalizada = 1 AND v.type = 'pubC' AND pc.id = $_POST[pcId]";
	$params['orderExt'] = "ORDER BY vId";
	$params['JOINS'] = "
		LEFT JOIN PublicConsultationsUsers pcu ON pcu.id = v.elemId
		LEFT JOIN PublicConsultations pc ON pc.id = pcu.publicConsultationsId
	";

	$visitas = promTotComp([$_POST['pryId']],$params,$_POST['etapa']);

	// print2($visitas);
	// exit();


	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";
	$csv.= '<ss:Row> '."\n";


	$bloques = $db->query("SELECT * FROM Bloques 
		WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	// $csv .= ' "POS","fecha","hora entrada","hora salida", "nombre",'.$csvDims;
	
		$csv .= '<ss:Cell><ss:Data ss:Type="String">email</ss:Data></ss:Cell>'."\n";	
	
	$col = array();
	$i = 1;
	foreach ($bloques as $bId=> $b) {
		$csv .= '<ss:Cell><ss:Data ss:Type="String">BLOQUE</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.strip_tags($b['nombre']).'</ss:Data></ss:Cell>'."\n"; 
			$col[$i++] = array('tipo'=>"bloque",'identificador'=>$b['identificador']);
		$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($areas as $a) {
			$csv .= '<ss:Cell><ss:Data ss:Type="String">AREA</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.strip_tags($a['nombre']).'</ss:Data></ss:Cell>'."\n"; 
				$col[$i++] = array('tipo'=>"area",'identificador'=>$a['identificador'],'bId'=>$b['identificador']);;
			$csv .= '<ss:Cell><ss:Data ss:Type="String">PREGUNTAS</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");

			$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
				t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($pregs as $p) {
				// print2($p);
				switch ($p['tsiglas']) {
					case 'sub':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">SUBAREA</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
							$col[$i++] = array('tipo'=>'subArea','id'=>$p['id'],'campo'=>'valor','identificador'=>$p['identificador']);;
						$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
							t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($subpregs as $sp) {
							switch ($sp['tsiglas']) {
								case 'mult':
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'</ss:Data></ss:Cell>'."\n";
									// $csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";
									$csv .= '<ss:Cell><ss:Data ss:Type="String">justificacion</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									break;
								case 'num':
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'</ss:Data></ss:Cell>'."\n";
									// $csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id']."_val";
									if($p['justif'] == 1){
										$csv .= '<ss:Cell><ss:Data ss:Type="String">justificacion</ss:Data></ss:Cell>'."\n";
										$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
												'identificador'=>$sp['identificador']);
									}
									break;
								case 'spatial':
								case 'cm':
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);

									break;
								case 'op':
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'</ss:Data></ss:Cell>'."\n";
									$csv .= '<ss:Cell><ss:Data ss:Type="String">lat</ss:Data></ss:Cell>'."\n";
									$csv .= '<ss:Cell><ss:Data ss:Type="String">lng</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$i = $i+2;

									break;

								
								default:
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($sp['pregunta']))).'</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									break;
							}
							
						}
						break;
					case 'mult':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
						// $csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";
						$csv .= '<ss:Cell><ss:Data ss:Type="String">justificacion</ss:Data></ss:Cell>'."\n";
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id'];
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_val";
						$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_justif";

						break;
					case 'num':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
						// $csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";

						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
								'identificador'=>$p['identificador']);
						if($p['justif'] == 1){
							$csv .= '<ss:Cell><ss:Data ss:Type="String">justificacion</ss:Data></ss:Cell>'."\n";
							$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
								'identificador'=>$p['identificador']);//$p['id']."_justif";
						}
						break;
					case 'spatial':
					case 'cm':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
						$col[$i++] = array('tipo'=>'spatial','id'=>$p['id'],'identificador'=>$p['identificador']);
						break;
					case 'op':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
						$csv .= '<ss:Cell><ss:Data ss:Type="String">lat</ss:Data></ss:Cell>'."\n";
						$csv .= '<ss:Cell><ss:Data ss:Type="String">lng</ss:Data></ss:Cell>'."\n";
						$col[$i++] = array('tipo'=>'op','id'=>$p['id'], 'identificador'=>$p['identificador']);
						$i = $i+2;

						break;
					default:
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', strip_tags($p['pregunta']))).'</ss:Data></ss:Cell>'."\n";
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);

						break;
				}
			}
		}
	}


	// $csv .= '<ss:Cell><ss:Data ss:Type="String">Shopper</ss:Data></ss:Cell>'."\n";

	$csv .= '</ss:Row>'."\n";

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
	// $csv = rtrim($csv,',');
	// $csv .= "\n";


	$estructura = estructura($chkId);
	// print2($estructura);
	foreach ($visitas as $v) {
		
		// if($v['POS'] != 'Mont-PR-02'){continue;} // BORRAR;
		// print2($v);

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




		$csvL = '<ss:Row> '."\n";

		$pcuElem = $db->query("SELECT pcu.* 
			FROM Visitas v
			LEFT JOIN PublicConsultationsUsers pcu ON v.elemId = pcu.id
			WHERE v.id = $vId
		")->fetchAll(PDO::FETCH_ASSOC)[0];
		// print2($targetElem);

		
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$pcuElem['email'].'</ss:Data></ss:Cell>'."\n";


		$pregs = resultados($vId,$estructura);
		$calc = calculos($pregs,$estructura);
		// print2($calc);
		// print2($pregs);


		// if(is_numeric($calc['chk']['prom'])){
		// 	$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.($calc['chk']['prom']*100).'</ss:Data></ss:Cell>'."\n";
		// }else{
		// 	$csvL .= '<ss:Cell><ss:Data ss:Type="String">-</ss:Data></ss:Cell>'."\n";
		// }

		// $csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['resumen'].'</ss:Data></ss:Cell>'."\n";
		// print2($pregs);
		$pond = $calc['chk']['max'] != 0 ? 100/$calc['chk']['max'] : 0;

		foreach ($col as $c) {
			// echo "$c[tipo]<br/>";
			// print2($c);
			switch ($c['tipo']) {
				case "nada":
					// echo "nada<br/>";
					$csvL .= '<ss:Cell><ss:Data ss:Type="String"></ss:Data></ss:Cell>'."\n";
					break;
				case 'bloque':
					if( is_numeric($calc['bloques'][$c['identificador']]['prom']) ){
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							// ($calc['bloques'][$c['identificador']]['prom']*100).
							'</ss:Data></ss:Cell>'."\n"; 
					}else{
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							// $calc['bloques'][$c['identificador']]['prom'].
						'</ss:Data></ss:Cell>'."\n";
					}
					break;
				case 'area':
					if(is_numeric($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'])){
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							// ($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom']*100).
							'</ss:Data></ss:Cell>'."\n";
					}else{
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							// $calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'].
							'</ss:Data></ss:Cell>'."\n";
					}
					break;
				case 'subArea':
					$pond = $calc['bloques'][$pregs[$c['identificador']]['bloque']]['areas'][$pregs[$c['identificador']]['area']]['pond'];
					if(is_numeric($pond)){					
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							// ($pregs[$c['identificador']]['influyeValor'] == 1 ? 
							// 	is_numeric($pregs[$c['identificador']]['valPreg']?$pregs[$c['identificador']]['valPreg']:0)*$pond:'-').
							'</ss:Data></ss:Cell>'."\n";
					}else{
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							// ($pregs[$c['identificador']]['influyeValor'] == 1 ? '-':'-').
							'</ss:Data></ss:Cell>'."\n";
					}
					// $csvL .= '<ss:Cell><ss:Data ss:Type="String">SUBAREA</ss:Data></ss:Cell>'."\n";

					break;
				case 'pregResp':
					// print2($pregs[$c['identificador']]);
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$pregs[$c['identificador']]['nomResp'].'</ss:Data></ss:Cell>'."\n";
					break;
				case 'spatial':
					// print2($pregs[$c['identificador']]);
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">spatial</ss:Data></ss:Cell>'."\n";
					break;
				case 'op':
					// print2($pregs[$c['identificador']]);
					$rvId = $pregs[$c['identificador']]['respuestasVisitaId'];
					$pointJ = $db->query("SELECT ST_AsGeoJSON(geometry) as geometry 
							FROM Problems WHERE RespuestasVisitaId = $rvId")->fetchAll(PDO::FETCH_ASSOC)[0];
					$point = json_decode($pointJ['geometry'],true);
					$coordinates = $point['coordinates'];
					// print2($coordinates);

					$csvL .= '<ss:Cell><ss:Data ss:Type="String"></ss:Data></ss:Cell>'."\n";
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$coordinates[1].'</ss:Data></ss:Cell>'."\n";
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$coordinates[0].'</ss:Data></ss:Cell>'."\n";
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
					// 	'</ss:Data></ss:Cell>'."\n";
					// print2($c);
					// break 2;
					break;
				case 'pregJustif':
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$pregs[$c['identificador']]['justificacion'].'</ss:Data></ss:Cell>'."\n";
					break;
				default:
					break;
			}
		}



		// $csvL .= '<ss:Cell><ss:Data ss:Type="String">'."$shopper[nombre] $shopper[aPat] $shopper[aMat]".'</ss:Data></ss:Cell>'."\n";
		$csvL .= '</ss:Row>'."\n";

		$csvL = str_replace('<p>', ' ', $csvL);
		$csvL = str_replace('</p>', ' ', $csvL);
		$csvL = str_replace('<b>', ' ', $csvL);
		$csvL = str_replace('</b>', ' ', $csvL);
		$csvL = str_replace('<br>', ' ', $csvL);
		$csvL = str_replace('<br/>', ' ', $csvL);
		$csvL = str_replace('<u>', ' ', $csvL);
		$csvL = str_replace('</u>', ' ', $csvL);
		$csvL = str_replace('&nbsp;', ' ', $csvL);
		// $csvL = rtrim($csvL,',');
		// $csvL .= "\n";

		$csv .= $csvL;
		// echo "$csvL<br/>";
		// break;
	}

	$csv .= "</ss:Table> "."\n";
	$csv .= "</ss:Worksheet> "."\n";
	$csv .= "</ss:Workbook> "."\n";


	// print2($visitas);

	echo $csv;



?>