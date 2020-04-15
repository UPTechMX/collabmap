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
	$sql = "SELECT COUNT(*) FROM Checklist WHERE marcasId = $mId AND repeticionesId = $rId";
	// echo "$sql\n";
	$cuantosChk = $db->query($sql)->fetchAll(PDO::FETCH_NUM);

	$col = 6;
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

	$chkInfo = $db->query("SELECT * FROM Checklist WHERE id = $chkId")->fetch(PDO::FETCH_ASSOC);
	// print2($est);
	$db->beginTransaction();
	try {
		$ok = true;
		$row = 0;
		// echo raiz()."lib/archivos/$_POST[archChk] \n";
		if (($handle = fopen(raiz()."lib/archivos/$_POST[archChk]", "r")) !== FALSE) {
			while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {
				if($row == 0){
					$row++;
					continue;
				}


				// Crear rotación
				// usa repeticionesId, tiendasId, fecha, fechaLimite, estatus
				// echo "SELECT id FROM Tiendas WHERE POS = $col[0] <br/>";
				if($col[0] == ''){
					continue;
				}
				// echo $col[0]."\n";
				$sql ="SELECT id, tipo FROM Tiendas WHERE POS = '$col[0]' AND marcasId = $mId";

				$tienda = $db->query($sql)->fetchAll(PDO::FETCH_NUM);
				$tId = $tienda[0][0];
				$tTipo = $tienda[0][1];
				if(!isset($tId)){
					$ok = false;
					$err = "El POS $col[0] no fue encontrado.";
					break;
				}

				// echo "cTipo: $cTipo --- tTipo: $tTipo\n";
				if($cuantosChk[0][0] > 1 && $tienda[0][1] != $chkDat[0]['tipo']){
					$ok = false;
					$err = "El tipo del POS $col[0] no coincide con el tipo del checklist.";
					break;
				}

				// echo "bbb\n";
				// echo "$col[0] -=-=-=-=$tId \n";

				$sql = "SELECT r.id 
					FROM Rotaciones r
					LEFT JOIN Visitas v ON v.rotacionesId = r.id
					WHERE r.tiendasId = $tId AND r.repeticionesId = $chkInfo[repeticionesId] 
					AND r.fecha = '$col[1]' AND v.hora = '$col[2]'";

				// echo $sql."\n";
				$rotInf = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

				// print2($rotInf);


				if(!empty($rotInf['id'])){
					$db->query("DELETE FROM Rotaciones WHERE id = '$rotInf[id]'");
				}else{
					// $db->query("DELETE FROM Rotaciones 
					// 	WHERE tiendasId = $tId AND repeticionesId = $chkInfo[repeticionesId] AND fecha = '$col[1]'");
				}
				if($col[3] == 'eliminado'){
					continue;
				}

				$datRot = array();
				$datRot['repeticionesId'] = $chkInfo['repeticionesId'];
				$datRot['tiendasId'] = $tId;
				$datRot['fecha'] = $col[1];
				$datRot['fechaLimite'] = $col[1];
				$datRot['estatus'] = 100;

				$pr['tabla'] = 'Rotaciones';
				$pr['datos'] = $datRot;


				$rrj = inserta($pr);
				$rr = json_decode($rrj,true);

				if($rr['ok'] != 1){
					$ok = false;
					$err = $rr['e'];
					break;
				}
				
				$rotacionesId = $rr['nId'];

				// crear shopper
				// usa nombre
				$buscaShopper = $db->query("SELECT id FROM Shoppers WHERE nombre = '$col[4]'")->fetchAll(PDO::FETCH_NUM)[0];
				// print2($buscaShopper);
				$buscaShopper[0] = is_array($buscaShopper[0])?$buscaShopper[0]:array();
				if(count($buscaShopper[0]) > 0){
					$shoppersId = $buscaShopper[0];
				}else{
					$datShop = array();
					$datShop['nombre'] = $col[4];

					$ps['tabla'] = 'Shoppers';
					$ps['datos'] = $datShop;

					$rsj = inserta($ps);
					$rs = json_decode($rsj,true);

					if($rs['ok'] != 1){
						$ok = false;
						$err = $rr['e'];
						break;
					}

					$shoppersId = $rs['nId'];
				}

				// crear visita 
				// usa rotacionesId, shopperId, fecha, aceptada, hora

				$datVis = array();
				$datVis['rotacionesId'] = $rotacionesId;
				$datVis['shoppersId'] = $shoppersId;
				$datVis['fecha'] = $col[1];
				$datVis['aceptada'] = 100;
				$datVis['hora'] = $col[2];
				$datVis['horaSalida'] = $col[3];
				$datVis['resumen'] = $col[5];

				$pv['tabla'] = 'Visitas';
				$pv['datos'] = $datVis;

				$rvj = inserta($pv);
				$rv = json_decode($rvj,true);

				if($rv['ok'] != 1){
					$ok = false;
					$err = $rv['e'];
					break;
				}


				$visitasId = $rv['nId'];

				if($ok){
					$pr['tabla'] = 'Rotaciones';
					$pr['datos']['id'] = $rotacionesId;
					$pr['datos']['visitaAct'] = $visitasId;
					
					$rrj = upd($pr);

					$rr = json_decode($rrj);

					$ok = $rr->ok;
					$err = $rr->err;
				}

				// echo "$visitasId<br/>";

				$pp['tabla'] = 'RespuestasVisita';
				foreach ($est as $preg => $p) {
					// echo "$preg <br/>";
					$datR = array();
					$datR['preguntasId'] = $p['pId'];
					$datR['visitasId'] = $visitasId;
					switch ($p['tipo']) {
						case 'mult':
							if(!isset($p['respuestas'][$col[$preg]]) && $col[$preg] != ''){
								$ok = false;
								$pregunta = $est[$preg]['pregunta'];
								$respuesta = $col[$preg];
								$err = " Respuesta no encontrada fila: $row, POS: $col[0], ".
									"para la pregunta $pregunta. <br/>Se intentó ingresar '$respuesta' -- columna : $preg";
								break 3;

							}elseif ($col[$preg] == '') {
								$ok = false;
								$pregunta = $est[$preg]['pregunta'];
								$respuesta = $col[$preg];
								$err = " Respuesta vacía en la fila: $row, POS: $col[0], ".
									"para la pregunta $pregunta. <br/>Se intentó ingresar '' -- columna : $preg";
								break 3;
							}
							$datR['respuesta'] = $p['respuestas'][$col[$preg]];
							$datR['justificacion'] = $col[$p['justif']];
							// print2($datR);
							break;
						case 'num':
							$datR['respuesta'] = is_numeric($col[$preg])?$col[$preg]:'-';
							if(isset($p['justif'])){
								$datR['justificacion'] = $col[$p['justif']];
							}
							break;
						case 'ab':
							$datR['respuesta'] = $col[$preg];
						default:
							# code...
							break;
					}
					$pp['datos'] = $datR;
					$rpj = inserta($pp);
					$rp = json_decode($rpj,true);

					if($rp['ok'] != 1){

						$ok = false;
						$err = $rp['e'];
						break 2;
					}

				}
				$rc = insertaCacheVisita($visitasId);

				if($rc['ok'] != 1){
					$ok = false;
					$err = $rc['e'];
					break;
				}
				$row++;
			}
		}
		if($ok){
			$db->commit();
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