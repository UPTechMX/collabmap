<?php

include_once '../j/j.func.php';
// print2($_POST);
$chkId = $_POST['chkId'];
$datChk = $db->query("SELECT c.nombre
	FROM Checklist c 
	WHERE c.id = $chkId")->fetch(PDO::FETCH_NUM);
// print2($_POST);

$datTarget = $db->query("SELECT * FROM Targets WHERE id = $_POST[targetsId]")->fetchAll(PDO::FETCH_ASSOC)[0];

header('Content-Type: text/html; charset=utf-8'); 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$datTarget[name] - $datChk[0].csv");
$dims = $db->query("SELECT * FROM Dimensiones 
	WHERE type = 'structure' AND elemId = $_POST[targetsId] 
	ORDER BY nivel
")->fetchAll(PDO::FETCH_ASSOC);

// print2($dims);


$bloques = $db->query("SELECT * FROM Bloques 
	WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
$csv = '';
foreach ($dims as $d) {
	$csv .= '"'.$d['nombre'].'", ';
}
$csv.= '"***EOS***",';
$csv.= '"'.TR("date").'",';
// $csv = '"POS","fecha","hora entrada","hora salida", "nombre","resumen",';
foreach ($bloques as $b) {
	$csv .= '"BLOQUE",';
	$csv .= '"'.$b['nombre'].'",';
	$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	foreach ($areas as $a) {
		$csv .= '"AREA",';
		$csv .= '"'.$a['nombre'].'",';
		$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
			t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
			LEFT JOIN Tipos t ON t.id = p.tiposId
			WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
			ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($pregs as $p) {
			// print2($p);
			switch ($p['tsiglas']) {
				case 'sub':
					$csv .= '"SUBAREA",';
					$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'",';
					$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
						t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
						LEFT JOIN Tipos t ON t.id = p.tiposId
						WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
						ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
					foreach ($subpregs as $sp) {
						switch ($sp['tsiglas']) {
							case 'mult':
								$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'","justificacion",';
								break;
							case 'num':
								$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'",';
								if($p['justif'] == 1){
									$csv .= '"justificacion",';
								}
								break;
							case 'op':
								$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'","lat","lng",';

								break;
							
							default:
								$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'",';
								break;
						}
						
					}
					break;
				case 'mult':
					$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'","justificacion",';
					break;
				case 'op':
					$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'","lat","lng",';
					break;
				case 'num':
					$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'",';
					if($p['justif'] == 1){
						$csv .= '"justificacion",';
					}
					break;
				default:
					$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'",';
					break;
			}
		}
	}
}

$csv = str_replace('<p>', ' ', $csv);
$csv = str_replace('</p>', ' ', $csv);
$csv = str_replace('<b>', ' ', $csv);
$csv = str_replace('</b>', ' ', $csv);
$csv = str_replace('<u>', ' ', $csv);
$csv = str_replace('</u>', ' ', $csv);
$csv = str_replace('&nbsp;', ' ', $csv);
$csv = rtrim($csv,',');
$csv .= "\n";
// 
if(true){

	$est = array();
	foreach ($bloques as $b) {
		$est[$b['identificador']] = array();
		$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($areas as $a) {
			$est[$b['identificador']][$a['identificador']] = array();
			$pregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos, 
				t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($pregs as $p) {
				// print2($p);
				$est[$b['identificador']][$a['identificador']][$p['id']] = array();
				$est[$b['identificador']][$a['identificador']][$p['id']]['tipo'] = $p['tsiglas'];
				$est[$b['identificador']][$a['identificador']][$p['id']]['justif'] = $p['justif'];
				switch ($p['tsiglas']) {
					case 'sub':
						$subpregs = $db->query("SELECT p.id,p.identificador,p.pregunta,p.influyeValor,p.subareasId,p.orden,p.puntos,
							t.siglas as tsiglas, t.nombre as nTipo, p.justif FROM Preguntas p
							LEFT JOIN Tipos t ON t.id = p.tiposId
							WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

						$est[$b['identificador']][$a['identificador']][$p['id']]['subpregs'] = array();

						foreach ($subpregs as $sp) {
							$est[$b['identificador']][$a['identificador']][$p['id']]['subpregs']
							[$sp['id']]['tipo'] = $sp['tsiglas'];

							$est[$b['identificador']][$a['identificador']][$p['id']]['subpregs']
							[$sp['id']]['justif'] = $sp['justif'];

							switch ($sp['tsiglas']) {
								case 'mult':
									$resp = $db->query("SELECT respuesta FROM Respuestas 
										WHERE preguntasId = $sp[id] AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_NUM);
									$est[$b['identificador']][$a['identificador']][$p['id']]
									['subpregs'][$sp['id']]['respuestas'] = $resp;
									break;
								default:
									break;
							}
						}					
						break;
					case 'num':
					case 'ab':
						break;
					case 'mult':
						$resp = $db->query("SELECT respuesta FROM Respuestas 
							WHERE preguntasId = $p[id] AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_NUM);
						$est[$b['identificador']][$a['identificador']][$p['id']]['respuestas'] = $resp;
						break;
					default:
						break;
				}
			}
		}
	}
	// echo $csv;

	$LJ = '';
	$nivelMax = 0;
	$fields = "";
	$numDim = count($dims);
	for ($i=$nivelMax; $i <$numDim ; $i++) { 
		if($i == $nivelMax){
			$LJ .= " LEFT JOIN DimensionesElem de$i ON te.dimensionesElemId = de$i.id";
			$fields .= ", de$i.nombre as de$i"."_"."nombre";
		}else{
			$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			$fields .= ", de$i.nombre as de$i"."_"."nombre";
		}
		if($i == $numDim - 2){
		}
		if($i == $numDim - 1){
			
			$wDE = " de$i.padre = 0";
		}
	}

	$sql = "
		SELECT 1 as num $fields
		FROM TargetsElems te
		$LJ
		WHERE te.targetsId = $_POST[targetsId] AND $wDE
		
	";
	// echo $sql."<br/>";
	if($numDim > 0){
		$allDims = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}else{
		$allDims = [];
	}
	// print2($allDims);
	// foreach ($allDims as $d) {
	// 	for($i = $numDim-1;$i>=0;$i--){
	// 		$nom = $d['de'.$i.'_nombre'];
	// 		echo "$nom<br/>";
	// 	}
	// }
	


	// exit;


	foreach ($allDims as $d) {
		$lin = '';
		for($i = $numDim-1;$i>=0;$i--){
			$nom = $d['de'.$i.'_nombre'];
			$lin .= '"'.$nom.'",';
		}
		// $lin = '"'.$t[0].'","2000-12-31","13:02","14:44", "SHOPPER DE PRUEBA","",';
		$lin .= '"",';
		$lin .= '"AAAA-MM-DD",';
		foreach ($est as $b => $areas) {
			$lin .= '"",';
			$lin .= '"",';
			foreach ($areas as $a => $pregs) {
				$lin .= '"",';
				$lin .= '"",';
				foreach ($pregs as $pId => $p) {
					switch ($p['tipo']) {
						case 'mult':
							$items = is_array($p['respuestas'])?$p['respuestas']:array();
							if(!empty($items)){
								$lin .= '"'.$items[array_rand($items)][0].'",';
							}else{
								$lin .= '"",';
							}
							if(rand(0,15)>13){
								$lin .= '"'.$pId.'",';
							}else{
								$lin .= '"",';
							}
							break;
						case 'sub':
							$lin .= '"",';
							$lin .= '"",';
							foreach ($p['subpregs'] as $spId => $sp) {
								switch ($sp['tipo']) {
									case 'mult':
										$items = is_array($sp['respuestas'])?$sp['respuestas']:array();
										if(!empty($items)){
											$lin .= '"'.$items[array_rand($items)][0].'",';
										}else{
											$lin .= '"",';
										}
										if(rand(0,15)>13){
											$lin .= '"'.$spId.'",';
										}else{
											$lin .= '"",';
										}
										break;
									case 'num':
										$lin .= '"'.rand(0,10).'",';
										if($sp['justif'] == 1){
											$lin .= '"'.$spId.'",';
										}

										break;
									case 'ab':
										$lin .= '"'.$spId.'",';
										break;
									case 'spatial':
									case 'cm':
										$lin .= '"spatial",';
										break;

									case 'op':
										// print2($p);
										$lin .= '"spatial","lat","lng",';
										break;
									default:
										$lin .= '"",';
										break;
								}
							}
							break;
						case 'num':
							$a = rand(0,10);
							$lin .= '"'.$a.'",';
							if($p['justif'] == 1){
								$lin .= '"TEXT-'.$pId.'",';
							}
							break;
						case 'ab':
							// print2($p);
							$lin .= '"TEXT-'.$pId.'",';
							break;
						case 'spatial':
						case 'cm':
							$lin .= '"spatial",';
							break;
						case 'op':
							// print2($p);
							$lin .= '"spatial","lat","lng",';
							break;
						default:
							$lin .= '"",';
							break;
					}

				}
			}

		}
		$lin = str_replace('<p>', ' ', $lin);
		$lin = str_replace('</p>', ' ', $lin);
		$lin = str_replace('<b>', ' ', $lin);
		$lin = str_replace('</b>', ' ', $lin);
		$lin = str_replace('<u>', ' ', $lin);
		$lin = str_replace('</u>', ' ', $lin);
		$lin = str_replace('&nbsp;', ' ', $lin);
		$lin = rtrim($lin,',');
		$lin .= "\n";
		$csv .= $lin;

	}

}
// print2($est);

echo ($csv);
?>





