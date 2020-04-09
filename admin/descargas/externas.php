<?php  
	session_start();
	include_once '../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCacheExt.php';
	include_once raiz().'lib/php/calcCuestExt.php';

	$chkId = isset($_POST['chkId'])?$_POST['chkId']:23;
	$chkInfo = $db->query("SELECT * FROM ChecklistExt WHERE ID = $chkId")->fetchAll(PDO::FETCH_ASSOC)[0];
	$hoy = getdate();
	$fechaHoy = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'];

	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='$chkInfo[nombre]_$fechaHoy.xls'");

	// include_once raiz().'lib/php/checklistExt.php';



	// $chk = new Checklist($chkId);
	// print2($elem);
	// print2($reps);
	// print2($pId);
	// print2($params);
	// echo "numDim: $numDim<br/>";
	// $visitas = promTotComp(0,$reps,$pId,$params);
	$visitas = $db->query("SELECT v.Id as vId, v.*, e.nombre as estado, m.nombre as municipio
		
		FROM VisitasExt v
		LEFT JOIN Estados e ON e.id = v.estado
		LEFT JOIN Municipios m ON m.id = v.municipio
		WHERE checklistId = $chkId")->fetchAll(PDO::FETCH_ASSOC);


	$csv = '';
	$csv.= '<?xml version="1.0"?>'."\n";
	$csv.= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
	$csv.= '<ss:Worksheet ss:Name="Sheet1">'."\n";
	$csv.= '<ss:Table>'."\n";
	$csv.= '<ss:Row> '."\n";



	$bloques = $db->query("SELECT * FROM BloquesExt 
		WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	// $csv .= ' "POS","fecha","hora entrada","hora salida", "nombre",'.$csvDims;
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Nombre</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Correo electrónico</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Fecha</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">IP</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Estado</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Municipio</ss:Data></ss:Cell>'."\n";
	$csv .= '<ss:Cell><ss:Data ss:Type="String">Calificación final</ss:Data></ss:Cell>'."\n";
	$col = array();
	$i = 1;
	foreach ($bloques as $bId=> $b) {
		$csv .= '<ss:Cell><ss:Data ss:Type="String">BLOQUE</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");
		$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$b['nombre'].'</ss:Data></ss:Cell>'."\n"; 
			$col[$i++] = array('tipo'=>"bloque",'identificador'=>$b['identificador']);
		$areas = $db->query("SELECT * FROM AreasExt WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($areas as $a) {
			$csv .= '<ss:Cell><ss:Data ss:Type="String">AREA</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");
			$csv .= '<ss:Cell><ss:Data ss:Type="String">'.$a['nombre'].'</ss:Data></ss:Cell>'."\n"; 
				$col[$i++] = array('tipo'=>"area",'identificador'=>$a['identificador'],'bId'=>$b['identificador']);;
			$csv .= '<ss:Cell><ss:Data ss:Type="String">PREGUNTAS</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");

			$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
				t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM PreguntasExt p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($pregs as $p) {
				// print2($p);
				switch ($p['tsiglas']) {
					case 'sub':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">SUBAREA</ss:Data></ss:Cell>'."\n"; $col[$i++] = array('tipo'=>"nada");
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $p['pregunta'])).'</ss:Data></ss:Cell>'."\n";
							$col[$i++] = array('tipo'=>'subArea','id'=>$p['id'],'campo'=>'valor','identificador'=>$p['identificador']);;
						$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
							t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM PreguntasExt p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($subpregs as $sp) {
							switch ($sp['tsiglas']) {
								case 'mult':
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $sp['pregunta'])).'</ss:Data></ss:Cell>'."\n";
									$csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";
									$csv .= '<ss:Cell><ss:Data ss:Type="String">justificacion</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									break;
								case 'num':
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $sp['pregunta'])).'</ss:Data></ss:Cell>'."\n";
									$csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";
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
								
								default:
									$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $sp['pregunta'])).'</ss:Data></ss:Cell>'."\n";
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									break;
							}
							
						}
						break;
					case 'mult':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $p['pregunta'])).'</ss:Data></ss:Cell>'."\n";
						$csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";
						$csv .= '<ss:Cell><ss:Data ss:Type="String">justificacion</ss:Data></ss:Cell>'."\n";
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id'];
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_val";
						$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_justif";

						break;
					case 'num':
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $p['pregunta'])).'</ss:Data></ss:Cell>'."\n";
						$csv .= '<ss:Cell><ss:Data ss:Type="String">valor</ss:Data></ss:Cell>'."\n";

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
					default:
						$csv .= '<ss:Cell><ss:Data ss:Type="String">'.trim(str_replace('"', '', $p['pregunta'])).'</ss:Data></ss:Cell>'."\n";
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);

						break;
				}
			}
		}
	}
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
		$vId = $v['vId'];

		$csvL = '<ss:Row> '."\n";
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['nombre'].'</ss:Data></ss:Cell>'."\n";
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['mail'].'</ss:Data></ss:Cell>'."\n";
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['timestamp'].'</ss:Data></ss:Cell>'."\n";
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['IP'].'</ss:Data></ss:Cell>'."\n";
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['estado'].'</ss:Data></ss:Cell>'."\n";
		$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$v['municipio'].'</ss:Data></ss:Cell>'."\n";
		$csvL .= $csvDims;

		// $res = $chk->getResultados($vId);
		// print2($res);
		$pregs = resultadosDB($vId,$estructura);
		$calc = calculos($pregs,$estructura);

		if(is_numeric($calc['chk']['prom'])){
			$csvL .= '<ss:Cell><ss:Data ss:Type="Number">'.($calc['chk']['prom']*100).'</ss:Data></ss:Cell>'."\n";
		}else{
			$csvL .= '<ss:Cell><ss:Data ss:Type="String">-</ss:Data></ss:Cell>'."\n";
		}
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
						$csvL .= '<ss:Cell><ss:Data ss:Type="Number">'.
							($calc['bloques'][$c['identificador']]['prom']*100).
							'</ss:Data></ss:Cell>'."\n"; 
					}else{
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$calc['bloques'][$c['identificador']]['prom'].'</ss:Data></ss:Cell>'."\n";
					}
					break;
				case 'area':
					if(is_numeric($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'])){
						$csvL .= '<ss:Cell><ss:Data ss:Type="Number">'.
							($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom']*100).
							'</ss:Data></ss:Cell>'."\n";
					}else{
						$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
							$calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'].
							'</ss:Data></ss:Cell>'."\n";
					}
					break;
				case 'subArea':
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.
						($pregs[$c['identificador']]['influyeValor'] == 1 ? $pregs[$c['identificador']]['valPreg']*$pond:'-').
						'</ss:Data></ss:Cell>'."\n";
					// $csvL .= '<ss:Cell><ss:Data ss:Type="String">SUBAREA</ss:Data></ss:Cell>'."\n";

					break;
				case 'pregResp':
					$csvL .= '<ss:Cell><ss:Data ss:Type="String">'.$pregs[$c['identificador']]['nomResp'].'</ss:Data></ss:Cell>'."\n";
					break;
				case 'pregVal':
					$tipo = $pregs[$c['identificador']]['influyeValor'] == 1 && is_numeric($pregs[$c['identificador']]['valPreg']*$pond)?
						'Number':
						'String';
					$csvL .= '<ss:Cell><ss:Data ss:Type="'.$tipo.'">'.
						($pregs[$c['identificador']]['influyeValor'] == 1 ? $pregs[$c['identificador']]['valPreg']*$pond:'-').
						'</ss:Data></ss:Cell>'."\n";
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