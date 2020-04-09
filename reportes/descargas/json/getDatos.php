<?php  
	session_start();
	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename='$_POST[chkNom].csv'");


	include_once '../../../lib/j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';
	include_once raiz().'lib/php/calcCuest.php';


	$chkId = isset($_POST['chkId'])?$_POST['chkId']:23;

	$dat = $db->query("SELECT r.id as rId, r.proyectosId as pId, c.marcasId as mId
		FROM Checklist c
		LEFT JOIN Repeticiones r ON r.id = c.repeticionesId
		WHERE c.id = $chkId")->fetchAll(PDO::FETCH_ASSOC);
	$mId = $dat[0]['mId'];
	$pId = $dat[0]['pId'];

	$elem = -1;
	foreach ($_SESSION['pub']['priv'] as $p) {
		if($p['proyectosId'] == $pId){
			$elem = $p['dimensionesElemId'];
		}
	}
	$reps = [$dat[0]['rId']];

	$params['camposInt'] = "v.Id as vId, v.fecha, v.hora, v.horaSalida, t.POS, t.nombre,m.clientesId";
	$params['grpInt'] = "GROUP BY vId";
	$params['camposExt'] = "*";
	$params['where'] = "AND m.id = $mId";
	$params['orderExt'] = "ORDER BY vId";
	$visitas = promTotComp($elem,$reps,$pId,$params);

	$cId = $visitas[0]['clientesId'];
	$nD = $db->query("SELECT nombre FROM Dimensiones WHERE clientesId = $cId")->fetchAll(PDO::FETCH_NUM);
	$numDim = count($nD);

	$csvDims = '';
	foreach ($nD as $d) {
		$csvDims .= '"'.$d[0].'",';
	}
	// echo "numDim: $numDim<br/>";




	$bloques = $db->query("SELECT * FROM Bloques 
		WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	$csv = ' "POS","fecha","hora entrada","hora salida", "nombre",'.$csvDims;
	$col = array();
	$i = 1;
	foreach ($bloques as $bId=> $b) {
		$csv .= '"BLOQUE",'; $col[$i++] = array('tipo'=>"nada");
		$csv .= '"'.$b['nombre'].'",'; $col[$i++] = array('tipo'=>"bloque",'identificador'=>$b['identificador']);
		$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($areas as $a) {
			$csv .= '"AREA",'; $col[$i++] = array('tipo'=>"nada");
			$csv .= '"'.$a['nombre'].'",'; $col[$i++] = array('tipo'=>"area",'identificador'=>$a['identificador'],'bId'=>$b['identificador']);;
			$csv .= '"PREGUNTAS",'; $col[$i++] = array('tipo'=>"nada");

			$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
				t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($pregs as $p) {
				// print2($p);
				switch ($p['tsiglas']) {
					case 'sub':
						$csv .= '"SUBAREA",'; $col[$i++] = array('tipo'=>"nada");
						$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'",'; $col[$i++] = 
							array('tipo'=>'subArea','id'=>$p['id'],'campo'=>'valor','identificador'=>$p['identificador']);;
						$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
							t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($subpregs as $sp) {
							switch ($sp['tsiglas']) {
								case 'mult':
									$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'","valor","justificacion",';
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
											'identificador'=>$sp['identificador']);
									break;
								case 'num':
									$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'","valor",';
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									$col[$i++] = array('tipo'=>'pregVal','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id']."_val";
									if($p['justif'] == 1){
										$csv .= '"justificacion",';
										$col[$i++] = array('tipo'=>'pregJustif','id'=>$sp['id'],
												'identificador'=>$sp['identificador']);
									}
									break;
								
								default:
									$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'",';
									$col[$i++] = array('tipo'=>'pregResp','id'=>$sp['id'],
										'identificador'=>$sp['identificador']);//$sp['id'];
									break;
							}
							
						}
						break;
					case 'mult':
						$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'","valor","justificacion",';
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id'];
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_val";
						$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
							'identificador'=>$p['identificador']);//$p['id']."_justif";

						break;
					case 'num':
						$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'","valor",';
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);
						$col[$i++] = array('tipo'=>'pregVal','id'=>$p['id'],
								'identificador'=>$p['identificador']);
						if($p['justif'] == 1){
							$csv .= '"justificacion",';
							$col[$i++] = array('tipo'=>'pregJustif','id'=>$p['id'],
								'identificador'=>$p['identificador']);//$p['id']."_justif";
						}
						break;
					default:
						$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'",';
						$col[$i++] = array('tipo'=>'pregResp','id'=>$p['id'],
								'identificador'=>$p['identificador']);

						break;
				}
			}
		}
	}

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
	$csv .= "\n";


	$estructura = estructura($chkId);
	// print2($estructura);
	foreach ($visitas as $v) {
		$vId = $v['vId'];

		$LJ = '';
		$fields = '';
		for ($i=0; $i <$numDim ; $i++) {
			if($i == 0){
				$LJ .= " LEFT JOIN DimensionesElem de0 ON de0.id = t.dimensionesElemId 
						 LEFT JOIN Dimensiones d0 ON d0.id = de0.dimensionesId ";
				$fields .= "d0.nombre as d0, de0.nombrePub as de0";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre
						 LEFT JOIN Dimensiones d$i ON d$i.id = de$i.dimensionesId";
				$fields .= ",d$i.nombre as d$i, de$i.nombrePub as de$i";
			}
		}

		$sql = "SELECT $fields  FROM Visitas v 
			LEFT JOIN Rotaciones rot ON v.rotacionesId = rot.id 
			LEFT JOIN Tiendas t ON t.id = rot.tiendasId
			$LJ
			WHERE v.id = $vId";

		// echo $sql;
		$dims = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
		

		// print2($v);
		// print2($dims);

		$csvDims = "";
		for($i = $numDim;$i>0; $i--){
			$csvDims .= '"'.$dims['de'.($i-1)].'",';
		}
		$csvL = '"'.$v['POS'].'","'.$v['fecha'].'","'.$v['hora'].'","'.$v['horaSalida'].'","'.$v['nombre'].'",'.$csvDims;

		$pregs = resultados($vId,$estructura);
		$calc = calculos($pregs,$estructura);
		// print2($pregs);
		$pond = $calc['chk']['max'] != 0 ? 100/$calc['chk']['max'] : 0;

		foreach ($col as $c) {
			// echo "$c[tipo]<br/>";
			// print2($c);
			switch ($c['tipo']) {
				case "nada":
					// echo "nada<br/>";
					$csvL .= '"",';
					break;
				case 'bloque':
					if( is_numeric($calc['bloques'][$c['identificador']]['prom']) ){
						$csvL .= '"'.($calc['bloques'][$c['identificador']]['prom']*100).'",';
					}else{
						$csvL .= '"'.$calc['bloques'][$c['identificador']]['prom'].'",';
					}
					break;
				case 'area':
					if(is_numeric($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'])){
						$csvL .= '"'.($calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom']*100).'",';
					}else{
						$csvL .= '"'.$calc['bloques'][$c['bId']]['areas'][$c['identificador']]['prom'].'",';
					}
					break;
				case 'subArea':
					$csvL .= '"'.($pregs[$c['identificador']]['influyeValor'] == 1 ? $pregs[$c['identificador']]['valPreg']*$pond:'-').'",';
					// $csvL .= '"SUBAREA",';

					break;
				case 'pregResp':
					$csvL .= '"'.$pregs[$c['identificador']]['nomResp'].'",';
					break;
				case 'pregVal':
					$csvL .= '"'.($pregs[$c['identificador']]['influyeValor'] == 1 ? $pregs[$c['identificador']]['valPreg']*$pond:'-').'",';
					// print2($c);
					// break 2;
					break;
				case 'pregJustif':
					$csvL .= '"'.$pregs[$c['identificador']]['justificacion'].'",';
					break;
				default:
					break;
			}
		}


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