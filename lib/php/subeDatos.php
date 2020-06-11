<?php

 	include_once '../j/j.func.php';
 	include_once 'calcCuest.php';


 	// print2($_POST);
 	// exit;

 	// $_POST['archChk'] = 'test.csv';
 	$chkId = $_POST['chkId'];

 	
	$bloques = $db->query("SELECT * FROM Bloques 
		WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	$chkDat = $db->query("SELECT * FROM Checklist WHERE id = $chkId")->fetchAll(PDO::FETCH_ASSOC);

	$mId = $chkDat[0]['marcasId'];
	$rId = $chkDat[0]['repeticionesId'];
	$cTipo = $chkDat[0]['tipo'];
	$sql = "SELECT COUNT(*) FROM Checklist";
	$cuantosChk = $db->query($sql)->fetchAll(PDO::FETCH_NUM);

	$dimensiones = $db->query("SELECT * FROM Dimensiones WHERE type = 'structure' AND elemId = $_POST[targetsId]")->fetchAll(PDO::FETCH_ASSOC);
	// print2($dimensiones);
	$targetsId = $_POST['targetsId'];
	$col = 2;

	foreach ($dimensiones as $d) {
		$col++;
	}

	if(count($dimensiones) == 0){
		exit('{"ok":0,"err":"'.TR('dimFirst').'"}');
	}
	// echo "$sql\n";

	// echo "---- COL : $col ----\n\n";
	// $col = 6;
	$est = array();
	foreach ($bloques as $b) {
		$col = $col + 2;
		$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($areas as $a) {
			$col = $col + 2;
			$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
				t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($pregs as $p) {
				switch ($p['tsiglas']) {
					case 'sub':
						$col = $col+2;
						$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
							t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($subpregs as $sp) {
							$est[$col]['pId'] = $sp['id'];
							$est[$col]['tipo'] = $sp['tsiglas'];
							$est[$col]['preg'] = $sp['pregunta'];
							switch ($sp['tsiglas']) {
								case 'mult':
									$respDB = $db->query("SELECT id,respuesta FROM Respuestas 
										WHERE preguntasId = $sp[id] AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_NUM);
									$resp = array();
									foreach ($respDB as $r) {
										$resp[$r[1]] = $r[0];
									}
									$est[$col]['respuestas'] = $resp;
									$est[$col]['pregunta'] = $sp['pregunta'];
									$est[$col]['justif'] = $col+1;
									$col = $col+2;
									break;
								case 'num':
									if($sp['justif'] == 1){
										$est[$col]['justif'] = $col+1;
										$col++;
									}
									$col++;
									break;
								case 'spatial':
								case 'cm':
									$est[$col]['pId'] = $p['id'];
									$est[$col]['tipo'] = $p['tsiglas'];
									// echo "$col <br/>";
									$col++;
									break;
								case 'op':
									$est[$col]['pId'] = $sp['id'];
									$est[$col]['tipo'] = $sp['tsiglas'];
									$est[$col]['lat'] = $col+1;
									$est[$col]['lng'] = $col+2;
									// echo "$col <br/>";
									$col = $col+3;
									break;


								default:
									$col++;
									break;
							}
						}					
						break;
					case 'num':
						$est[$col]['pId'] = $p['id'];
						$est[$col]['tipo'] = $p['tsiglas'];
						if($p['justif'] == 1){
							$est[$col]['justif'] = $col+1;
							$col++;
						}
						$col++;
						break;
					case 'ab':
						$est[$col]['pId'] = $p['id'];
						$est[$col]['tipo'] = $p['tsiglas'];
						// echo "$col <br/>";
						$col++;
						break;
					case 'spatial':
					case 'cm':
						$est[$col]['pId'] = $p['id'];
						$est[$col]['tipo'] = $p['tsiglas'];
						// echo "$col <br/>";
						$col++;
						break;
					case 'op':
						$est[$col]['pId'] = $p['id'];
						$est[$col]['tipo'] = $p['tsiglas'];
						$est[$col]['lat'] = $col+1;
						$est[$col]['lng'] = $col+2;
						// echo "$col <br/>";
						$col = $col+3;
						break;
					case 'mult':
						// echo "$col <br/>";
						$est[$col]['pId'] = $p['id'];
						$est[$col]['tipo'] = $p['tsiglas'];
						$est[$col]['pregunta'] = $p['pregunta'];
						$respDB = $db->query("SELECT id,respuesta FROM Respuestas 
							WHERE preguntasId = $p[id] AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_NUM);
						// print2($respDB);
						$resp = array();
						foreach ($respDB as $r) {
							$resp[$r[1]] = $r[0];
						}
						// echo "------- $col ------ <br/>";
						// print2($resp);
						$est[$col]['respuestas'] = $resp;
						$est[$col]['justif'] = $col+1;
						
						$col = $col+2;
						break;
					default:
						break;
				}
			}
		}
	}


	$insDims = $db->prepare("INSERT INTO Dimensiones SET elemId = ?, nombre = ?,nivel = ?, type='structure'");
	$buscElemDim = $db->prepare("SELECT id FROM DimensionesElem WHERE nombre = ? AND dimensionesId = ?");
	$insDimElem = $db->prepare("INSERT INTO DimensionesElem SET  nombre = ?, dimensionesId = ?, padre = ?");

	$chkInfo = $db->query("SELECT * FROM Checklist WHERE id = $chkId")->fetch(PDO::FETCH_ASSOC);
	// print2($est);
	$db->beginTransaction();
	try {
		$ok = true;
		$row = 0;
		$numDims = 0;
		if (($handle = fopen(raiz()."externalFiles/$_POST[archChk]", "r")) !== FALSE) {
			while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {
				if($row == 0){
					
					for($i = 0;$i<count($col);$i++){
						if($col[$i] == '***EOS***'){
							$numDims = $i;
							break;
						}
					}

					$dims = array();
					$j = 0;
					for($i = 0;$i<$numDims;$i++){
						$dims[$j++] = $col[$i];
					}

					$buscaDims = $db->query("SELECT id FROM Dimensiones 
						WHERE elemId = $targetsId AND type = 'structure'
						ORDER BY nivel") -> fetchAll(PDO::FETCH_NUM);

					if(count($buscaDims) > 0){
						if(count($buscaDims) == $numDims){
							// print2($buscaDims);
							foreach ($buscaDims as $k => $d) {
								$dimsTarget[$k] = $d[0];
							}
						}else{
							$ok = false;
							$cuenta = count($buscaDims);
							$err = TR('dimNoMatch');
							exit('{"ok":0,"err":"'.$err.'"}');
						}
					}else{
						foreach ($dims as $k => $dim) {
							$insDims -> execute(array($targetsId,$dim,$k+1));
							$dimsTarget[$k] = $db->lastInsertId();
						}
					}
					
				}else{

					for($i = 0;$i<$numDims;$i++){
						if(empty($col[$i])){
							continue 2;
						}
						if( !isset($dimsElems[$i][$col[$i]]) ){
							$buscElemDim -> execute([$col[$i],$dimsTarget[$i]]);
							$elemDim = $buscElemDim -> fetchAll(PDO::FETCH_NUM);
							if(!empty($elemDim)){
								$dimsElems[$i][$col[$i]] = $elemDim[0][0];
							}else{
								// $insDimElem = $db->prepare("INSERT INTO DimensionesElem SET  nombre = ?, dimensionesId = ?, padre = ?");
								$padre = $i == 0?0:$dimsElems[$i-1][$col[$i-1]];
								$insDimElem -> execute([ $col[$i],$dimsTarget[$i], $padre]);
								$dimsElems[$i][$col[$i]] = $db->lastInsertId();
							}
						}

					}

					// print2($dimsElems);
					$dimensionesElemId = $dimsElems[$numDims-1][$col[$numDims-1]];
					$targetElemFind = $db->query("SELECT * FROM TargetsElems 
						WHERE dimensionesElemId = $dimensionesElemId")->fetchAll(PDO::FETCH_ASSOC)[0];

					if(empty($targetElemFind)){
						$pte['tabla'] = 'TargetsElems';
						$pte['datos']['targetsId'] = $targetsId;
						$pte['datos']['dimensionesElemId'] = $dimsElems[$numDims-1][$col[$numDims-1]];

						$rtj = atj(inserta($pte));
						$rt = json_decode($rtj,true);

						if($rt['ok'] != 1){
							// echo $rtj;
							
							$ok = false;
							$err = $rt['e'];
							break;
						}

						$teId = $rt['nId'];
					}else{
						$teId = $targetElemFind['id'];
					}




					$datVis = array();
					$datVis['finishDate'] = validateDate($col[$numDims+1])?$col[$numDims+1]." 00:00:00":date('Y-m-d H:m:s');;
					$datVis['finalizada'] = 1;
					$datVis['checklistId'] = $chkId;
					$datVis['type'] = 'trgt';
					$datVis['elemId'] = $teId;

					$pv['tabla'] = 'Visitas';
					$pv['timestamp'] = 'timestamp';
					$pv['datos'] = $datVis;

					// print2($pv);
					// continue;


					$rvj = inserta($pv);
					$rv = json_decode($rvj,true);

					if($rv['ok'] != 1){
						$ok = false;
						$err = $rv['e'];
						break;
					}


					$visitasId = $rv['nId'];

					// echo "$visitasId<br/>";

					$pp['tabla'] = 'RespuestasVisita';
					foreach ($est as $preg => $p) {
						// echo "$preg <br/>";
						// echo "\n\n\n";
						// print2($p);
						$datR = array();
						$datR['preguntasId'] = $p['pId'];
						$datR['visitasId'] = $visitasId;
						switch ($p['tipo']) {
							case 'mult':
								if(!isset($p['respuestas'][$col[$preg]]) && $col[$preg] != ''){
									$ok = false;
									$pregunta = $est[$preg]['pregunta'];
									$respuesta = $col[$preg];
									$err = TR('amswerNotFoundRow')." $row , ".
										TR('amswerNotFoundQuestion')." $pregunta. <br/>".TR("amswerNotFoundAnswer")." $respuesta column: $preg";
									break 3;

								}elseif ($col[$preg] == '') {
									$ok = false;
									$pregunta = $est[$preg]['pregunta'];
									$respuesta = $col[$preg];
									$err = TR('amswerNotFoundRow')." $row , ".
										TR('amswerNotFoundQuestion')." $pregunta. <br/>".TR("amswerNotFoundAnswer")." column: $preg";
									break 3;
								}
								$datR['respuesta'] = $p['respuestas'][$col[$preg]];
								$datR['justificacion'] = $col[$p['justif']];
								// print2($datR);
								break;
							case 'num':
								$datR['respuesta'] = is_numeric($col[$preg])?$col[$preg]:'-';
								// echo "respNum: ".$datR['respuesta']."\n";
								if(isset($p['justif'])){
									$datR['justificacion'] = $col[$p['justif']];
								}
								break;
							case 'spatial':
							case 'cm':
							case 'op':
								$datR['respuesta'] = $col[$preg];
								break;
							case 'ab':
								$datR['respuesta'] = $col[$preg];
								// echo "respOTRA: ".$datR['respuesta']."\n";
								break;

							default:
								# code...
								break;
						}
						$pp['datos'] = $datR;
						// print2($pp);
						$rpj = inserta($pp);
						$rp = json_decode($rpj,true);

						if($rp['ok'] != 1){

							$ok = false;
							$err = $rp['e'];
							break 2;
						}else{
							// echo "pTipo = $p[tipo] - $preg\n";

							$ppr = array();
							if($p['tipo'] == 'op'){
								$rvId = $rp['nId'];
								if( is_numeric($col[$preg+1]) && is_numeric($col[$preg+2]) ){
									// echo "ENTRA \n";
									$ppr['tabla'] = 'Problems';
									$ppr['datos']['type'] = 'marker';
									$ppr['datos']['respuestasVisitaId'] = $rvId;
									$ppr['geo']['type'] = 'marker';
									$ppr['geo']['field'] = 'geometry';
									$ppr['geo']['latlngs'] = '{"lat":'.$col[$preg+1].',"lng":'.$col[$preg+2].'}';
									// $ppr['geo']['wkt'] = null;

									// print2($ppr);
									$prj = atj(inserta($ppr));

								}
							}
							if($p['tipo'] == 'spatial'){
								$rvId = $rp['nId'];
								// echo "ENTRA ".$col[$preg]."\n";
								$ppr['tabla'] = 'Problems';
								$ppr['datos']['type'] = 'WKT';
								$ppr['datos']['respuestasVisitaId'] = $rvId;
								$ppr['geo']['type'] = 'marker';
								$ppr['geo']['field'] = 'geometry';
								$ppr['geo']['wkt'] = $col[$preg];

								$prj = atj(inserta($ppr));
								// echo "$prj\n";
								
							}
						}

					}
					$rc = insertaCacheVisita($visitasId);

					if($rc['ok'] != 1){
						$ok = false;
						$err = $rc['e'];
						break;
					}

				}

				$row++;
			}
		}
		if($ok){
			$db->commit();
			// $db->rollBack();
			echo '{"ok":"1"}';
		}else{
			$err = str_replace('"', '\\"', $err);
			echo '{"ok":"0","err":"'.$err.'"}';
			$db->rollBack();
		}
		
	} catch (PDOException $e) {
		$db->rollBack();
		echo '{"ok":"0","err":"'.$e->getMessage().' '.$e->getLine().'"}';

	}



?>