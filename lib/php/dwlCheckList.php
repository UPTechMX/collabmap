<?php

include_once '../j/j.func.php';
// print2($_POST);
$chkId = $_POST['chkId'];
$datChk = $db->query("SELECT CONCAT(c.nombre,' (',r.fechaIni,' al ',r.fechaFin,') ',m.nombre,' (',c.tipo,')'), m.id as mId, m.clientesId as cId 
	FROM Checklist c 
	LEFT JOIN Repeticiones r ON c.repeticionesId = r.id
	LEFT JOIN Marcas m ON c.marcasId = m.id
	WHERE c.id = $chkId")->fetch(PDO::FETCH_NUM);
// print2($datChk);

header('Content-Type: text/html; charset=utf-8'); 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename='$datChk[0].csv'");



$bloques = $db->query("SELECT * FROM Bloques 
	WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
$csv = '"POS","fecha","hora entrada","hora salida", "nombre","resumen",';
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
							
							default:
								$csv .= '"'.trim(str_replace('"', '', $sp['pregunta'])).'",';
								break;
						}
						
					}
					break;
				case 'mult':
					$csv .= '"'.trim(str_replace('"', '', $p['pregunta'])).'","justificacion",';
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

if(true){
	$tiendas = $db->query("SELECT t.POS FROM Tiendas t 
		LEFT JOIN Marcas m ON t.marcasId = m.id
		LEFT JOIN Clientes c ON m.clientesId = c.id
		WHERE c.id = $datChk[2] AND m.id = $datChk[1]")->fetchAll(PDO::FETCH_NUM);

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
	// exit;
	foreach ($tiendas as $t) {
		$lin = '"'.$t[0].'","2000-12-31","13:02","14:44", "SHOPPER DE PRUEBA","",';
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

									default:
										# code...
										break;
								}
							}
							break;
						case 'num':
							$a = rand(0,10);
							$lin .= '"'.$a.'",';
							if($p['justif'] == 1){
								$lin .= '"'.$pId.'",';
							}
							break;
						case 'ab':
							// print2($p);
							$lin .= '"'.$pId.'",';
							break;
						default:
							# code...
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





