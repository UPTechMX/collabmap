<?php
// echo "AAAA";
// @include '../j/j.func.php';
function getChkId($visita){
	global $db;

	$sql = "SELECT v.*
		FROM Visitas v
		WHERE v.id = $visita ";
	// echo "$sql<br/>";
	$datVis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($datVis);

	return $datVis['checklistId'];
}

function estructura($chkId){
	global $db;
	$chk = array();

	// echo $chkId;
	$tipoProm = $db->query("SELECT tipoProm, tipoAnalisis FROM Checklist WHERE id = $chkId")->fetchAll(PDO::FETCH_NUM)[0];
	$chk['tipoProm'] = $tipoProm[0] != ''? $tipoProm[0] : 1;
	$chk['tipoAnalisis'] = $tipoProm[1] != ''? $tipoProm[1] : 1;

	$chk['conds'] = $db->query("SELECT * 
		FROM Condicionales WHERE aplicacion = 'chk' AND eleId = $chkId ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	$bloques = $db->query("SELECT * FROM Bloques 
		WHERE checklistId = $chkId AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	$chk['bloques'] = array();
	foreach ($bloques as $b) {
		$chk['bloques'][$b['identificador']] = array();
		$chk['bloques'][$b['identificador']]['max'] = 0;
		$chk['bloques'][$b['identificador']]['valor'] = 0;
		$chk['bloques'][$b['identificador']]['muestra'] = 1;
		$chk['bloques'][$b['identificador']]['nombre'] = $b['nombre'];
		$chk['bloques'][$b['identificador']]['tipoProm'] = $b['tipoProm'] != "" ? $b['tipoProm'] : 1 ;
		$chk['bloques'][$b['identificador']]['valMax'] = $b['valMax'] != "" ? $b['valMax'] : 100 ;
		$chk['bloques'][$b['identificador']]['encabezado'] = $b['encabezado'];
		$chk['bloques'][$b['identificador']]['areas'] = array();
		$chk['bloques'][$b['identificador']]['conds'] = $db->query("SELECT * 
			FROM Condicionales WHERE aplicacion = 'bloque' AND eleId = $b[id] ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

		$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL) ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
		// print2($areas);
		foreach ($areas as $a) {
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']] = array();
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['max'] = 0;
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['valor'] = 0;
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['muestra'] = 1;
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['nombre'] = $a['nombre'];
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['valMax'] = $a['valMax'] != ""?$a['valMax']: 100 ;
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas'] = array();
			$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['conds'] = $db->query("SELECT * 
				FROM Condicionales WHERE aplicacion = 'area' AND eleId = $a[id] ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

			$condsB = $chk['bloques'][$b['identificador']]['conds'];
			$conds = $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['conds'];

			foreach ($condsB as $c) {
				if($c['accion'] == 2){
					$c['accion'] = 2;
					$c['valor'] = '-';
					$c['orden'] = count($conds)+1;
					$c['eleId'] = $sp['id'];
					$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['conds'][] = $c;
				}
			}


			$pregs = $db->query("SELECT p.id,p.identificador,p.influyeValor,p.subareasId,p.orden,p.puntos, p.pregunta,
				t.siglas as tsiglas, t.nombre as nTipo FROM Preguntas p
				LEFT JOIN Tipos t ON t.id = p.tiposId
				WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

			foreach ($pregs as $p) {

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']] = array();

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['puntos'] = $p['puntos'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['orden'] = $p['orden'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['muestra'] = 1;

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['identificador'] = $p['identificador'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['influyeValor'] = $p['influyeValor'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['tipo'] = $p['tsiglas'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['id'] = $p['id'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['pregunta'] = $p['pregunta'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['respuestas'] = $db -> query("SELECT r.valor, r.*
					FROM Respuestas r WHERE preguntasId = $p[id]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

				// $resp = $db->query("SELECT * FROM RespuestasVisita WHERE visitasId = $visita AND preguntasId = $p[id]")->fetch(PDO::FETCH_ASSOC);
				// $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				// [$p['identificador']]['respuesta'] = $respuestas[$p['identificador']][0]['respuesta'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['conds'] = $db->query("SELECT * 
					FROM Condicionales WHERE aplicacion = 'preg' AND eleId = $p[id] ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

				$condsA = $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['conds'];
				$conds = $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['conds'];

				foreach ($condsA as $c) {
					if($c['accion'] == 2){
						$c['accion'] = 2;
						$c['valor'] = '-';
						$c['orden'] = count($conds)+1;
						$c['eleId'] = $sp['id'];
						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas'][$p['identificador']]['conds'][] = $c;
					}
				}


				if($p['tsiglas'] == 'sub'){
					$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas'][$p['identificador']]['subpregs'] = array();
					$subpregs = $db->query("SELECT p.id,p.identificador,p.influyeValor,p.subareasId,p.orden,p.puntos,p.pregunta,
						t.siglas as tsiglas, t.nombre as nTipo FROM Preguntas p
						LEFT JOIN Tipos t ON t.id = p.tiposId
						WHERE subareasId = $p[id] AND (elim != 1 OR elim IS NULL)
						ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
					foreach ($subpregs as $sp) {
						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']] = array();

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['puntos'] = $sp['puntos'];

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['muestra'] = 1;

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['identificador'] = $sp['identificador'];

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['influyeValor'] = $sp['influyeValor'];

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['tipo'] = $sp['tsiglas'];

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['id'] = $sp['id'];

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['pregunta'] = $sp['pregunta'];

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['respuestas'] = $db -> query("SELECT r.valor, r.*
							FROM Respuestas r WHERE preguntasId = $sp[id]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['stipo'] = 'subPregs';

						$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['conds'] = $db->query("SELECT * 
							FROM Condicionales WHERE aplicacion = 'preg' AND eleId = $sp[id] ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

						$condsSA = $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
						[$p['identificador']]['conds'];

						$conds = $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['conds'];

						foreach ($condsSA as $c) {
							if($c['accion'] == 2){
								$c['accion'] = 2;
								$c['valor'] = '-';
								$c['orden'] = count($conds)+1;
								$c['eleId'] = $sp['id'];
								$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]
								['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['conds'][] = $c;
							}
						}

					}
				}
			}
		}
	}
	return $chk;
}

function resultados($visita,$chk){
	global $db;
	// print2($chk);

	$sql = "SELECT p.identificador as pId,p.identificador, rv.*, p.orden, r.id as rId,
		CASE 
			WHEN t.siglas = 'mult' THEN r.valor
			ELSE rv.respuesta
		END as valResp,

		CASE 
			WHEN t.siglas = 'mult' THEN r.respuesta
			ELSE rv.respuesta
		END as rNom

		FROM RespuestasVisita rv
		LEFT JOIN Preguntas p ON rv.preguntasId = p.id
		LEFT JOIN Tipos t ON p.tiposId = t.id
		LEFT JOIN Respuestas r ON rv.respuesta = r.id 
		WHERE visitasId = $visita " ;

	// echo $sql."<br/>";

	$respuestas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	// print2($respuestas);
	// $posiblesResp = $db->query("SELECT * ")

	foreach ($chk['bloques'] as $bId => $b) {
		foreach ($b['areas'] as $aId => $a) {
			foreach ($a['preguntas'] as $pId => $p) {
				// print2($p);
				switch ($p['tipo']) {
					case 'sub':
						foreach ($p['subpregs'] as $spId => $sp) {
							$chk['bloques'][$bId]['areas'][$aId]['preguntas'][$pId]['subpregs']
								[$spId]['respuesta'] = $respuestas[$spId][0]['respuesta'];
						}
						break;
					case 'mult':
						$chk['bloques'][$bId]['areas'][$aId]['preguntas'][$pId]['respuesta'] = $respuestas[$pId][0]['respuesta'];

					default:
						# code...
						break;
				}
			}
		}
	}
	// echo '-=-=-=-=-=-=-=-=-=-';
	// print2($chk);
	// echo '-=-=-=-=-=-=-=-=-=-';

	$preguntas = array();
	foreach ($chk['bloques'] as $bidentif => $b) {
		foreach ($b['areas'] as $aidentif => $a) {
			foreach ($a['preguntas'] as $pidentif => $p) {
				// print2($p);
				$preguntas[$pidentif] = $p;
				$preguntas[$pidentif]['bloque'] = $bidentif;
				$preguntas[$pidentif]['area'] = $aidentif;
				// valPreg
				switch ($p['tipo']) {
					case 'mult':
						if(!empty($p['respuesta'])){
							// $resp = $db->query("SELECT * FROM Respuestas WHERE id = $p[respuesta]")->fetch(PDO::FETCH_ASSOC);
							$preguntas[$pidentif]['respuesta'] = $respuestas[$pidentif][0]['respuesta'];//$resp['valor'];
							$preguntas[$pidentif]['respuestas'] = $p['respuestas'];//$resp['valor'];
							$preguntas[$pidentif]['valResp'] = $respuestas[$pidentif][0]['valResp'];//$resp['valor'];
							$preguntas[$pidentif]['nomResp'] = $respuestas[$pidentif][0]['rNom'];//$resp['respuesta'];
							$preguntas[$pidentif]['justificacion'] = $respuestas[$pidentif][0]['justificacion'];//$resp['respuesta'];


						}else{
							$preguntas[$pidentif]['respuesta'] = '';
							$preguntas[$pidentif]['valResp'] = '-';
							$preguntas[$pidentif]['nomResp'] = '';
							$preguntas[$pidentif]['justificacion'] = $respuestas[$pidentif][0]['justificacion'];//$resp['respuesta'];
						}
						break;
					case 'num';
						$preguntas[$pidentif]['respuesta'] = $respuestas[$pidentif][0]['respuesta'];
						$preguntas[$pidentif]['valResp'] = $respuestas[$pidentif][0]['valResp'];
						$preguntas[$pidentif]['nomResp'] = $respuestas[$pidentif][0]['valResp'];
						$preguntas[$pidentif]['justificacion'] = $respuestas[$pidentif][0]['justificacion'];//$resp['respuesta'];

						// $preguntas[$pidentif]['pId'] = $p['id'];
						break;
					case 'sub':
						$cont = count($p['conds']);
						if($cont == 0){
							$preguntas[$pidentif]['valResp'] = '-';
						}
						break;
					case 'ab':
						$preguntas[$pidentif]['respuesta'] = $respuestas[$pidentif][0]['respuesta'];
						$preguntas[$pidentif]['valResp'] = '-';
						$preguntas[$pidentif]['valPreg'] = '-';
						$preguntas[$pidentif]['nomResp'] = $respuestas[$pidentif][0]['valResp'];
						$preguntas[$pidentif]['justificacion'] = $respuestas[$pidentif][0]['justificacion'];//$resp['respuesta'];
						break;
					default:

						break;
				}

				if($p['tipo'] == 'sub'){
					foreach ($p['subpregs'] as $spidentif => $sp) {
						$preguntas[$spidentif] = $sp;
						$preguntas[$spidentif]['bloque'] = $bidentif;
						$preguntas[$spidentif]['area'] = $aidentif;
						$preguntas[$spidentif]['subarea'] = $pidentif;

						switch ($sp['tipo']) {
							case 'mult':
								if(!empty($sp['respuesta'])){

									$preguntas[$spidentif]['respuesta'] = $respuestas[$spidentif][0]['respuesta'];//$resp['valor'];
									$preguntas[$spidentif]['respuestas'] = $sp['respuestas'];//$resp['valor'];
									$preguntas[$spidentif]['valResp'] = $respuestas[$spidentif][0]['valResp'];//$resp['valor'];
									$preguntas[$spidentif]['nomResp'] = $respuestas[$spidentif][0]['rNom'];//$resp['respuesta'];
									$preguntas[$spidentif]['justificacion'] = $respuestas[$spidentif][0]['justificacion'];//$resp['respuesta'];

								}else{
									$preguntas[$spidentif]['respuesta'] = '';
									$preguntas[$spidentif]['valResp'] = '-';
									$preguntas[$spidentif]['nomResp'] = '';
									$preguntas[$spidentif]['justificacion'] = $respuestas[$spidentif][0]['justificacion'];//$resp['respuesta'];
								}
								break;
							case 'num';
								$preguntas[$spidentif]['respuesta'] = $respuestas[$spidentif][0]['respuesta'];
								$preguntas[$spidentif]['valResp'] = $respuestas[$spidentif][0]['valResp'];
								$preguntas[$spidentif]['nomResp'] = $respuestas[$spidentif][0]['valResp'];
								$preguntas[$spidentif]['justificacion'] = $respuestas[$spidentif][0]['justificacion'];//$resp['respuesta'];
								break;
							case 'sub':
								break;
							case 'ab':
								$preguntas[$spidentif]['respuesta'] = $respuestas[$spidentif][0]['respuesta'];
								$preguntas[$spidentif]['valResp'] = '-';
								$preguntas[$spidentif]['valPreg'] = '-';
								$preguntas[$spidentif]['nomResp'] = $respuestas[$spidentif][0]['valResp'];
								$preguntas[$spidentif]['justificacion'] = $respuestas[$spidentif][0]['justificacion'];//$resp['respuesta'];
								break;
							default:
								break;
						}
					}
					unset($preguntas[$pidentif]['subpregs']);
				}

			}
		}
	}

	foreach ($preguntas as $k =>$p) {
		$valPreg = valPreg($preguntas,$k);
		// echo "$k -> $valPreg<br/>";
		$preguntas[$k]['valPreg'] = is_numeric($valPreg)?$valPreg:'-'; // 
		// $preguntas[$k]['valPreg'] = is_numeric($valPreg)?$valPreg*$p['puntos']:'-'; // Ya lo multiplico por los puntos en valPreg

		// print2($valPreg);
	}
	return $preguntas;
}

function resultadosVisitas($visita,$chk,$resps){
	global $db;
	$respuestas = $resps[$visita];

	foreach ($chk['bloques'] as $bId => $b) {
		foreach ($b['areas'] as $aId => $a) {
			foreach ($a['preguntas'] as $pId => $p) {
				// print2($p);
				switch ($p['tipo']) {
					case 'sub':
						foreach ($p['subpregs'] as $spId => $sp) {
							$chk['bloques'][$bId]['areas'][$aId]['preguntas'][$pId]['subpregs']
								[$spId]['respuesta'] = $respuestas[$spId]['respuesta'];
						}
						break;
					case 'mult':
						$chk['bloques'][$bId]['areas'][$aId]['preguntas'][$pId]['respuesta'] = $respuestas[$pId]['respuesta'];

					default:
						# code...
						break;
				}
			}
		}
	}
	// echo '-=-=-=-=-=-=-=-=-=-';
	// print2($chk);
	// echo '-=-=-=-=-=-=-=-=-=-';

	$preguntas = array();
	foreach ($chk['bloques'] as $bidentif => $b) {
		foreach ($b['areas'] as $aidentif => $a) {
			foreach ($a['preguntas'] as $pidentif => $p) {
				// print2($p);
				$preguntas[$pidentif] = $p;
				$preguntas[$pidentif]['bloque'] = $bidentif;
				$preguntas[$pidentif]['area'] = $aidentif;

				switch ($p['tipo']) {
					case 'mult':
						if(!empty($p['respuesta'])){
							// $resp = $db->query("SELECT * FROM Respuestas WHERE id = $p[respuesta]")->fetch(PDO::FETCH_ASSOC);
							$preguntas[$pidentif]['valResp'] = $respuestas[$pidentif]['valResp'];//$resp['valor'];
							$preguntas[$pidentif]['nomResp'] = $respuestas[$pidentif]['rNom'];//$resp['respuesta'];
						}else{
							$preguntas[$pidentif]['valResp'] = '-';
						}
						break;
					case 'num';
						$preguntas[$pidentif]['valResp'] = $p['respuesta'];
						break;
					case 'sub':
						$cont = count($p['conds']);
						if($cont == 0){
							$preguntas[$pidentif]['valResp'] = '-';
						}
						break;
					default:
						# code...
						break;
				}

				if($p['tipo'] == 'sub'){
					foreach ($p['subpregs'] as $spidentif => $sp) {
						$preguntas[$spidentif] = $sp;
						$preguntas[$spidentif]['bloque'] = $bidentif;
						$preguntas[$spidentif]['area'] = $aidentif;
						$preguntas[$spidentif]['subarea'] = $pidentif;

						switch ($sp['tipo']) {
							case 'mult':
								if(!empty($sp['respuesta'])){

									$preguntas[$spidentif]['valResp'] = $respuestas[$spidentif]['valResp'];//$resp['valor'];
									$preguntas[$spidentif]['nomResp'] = $respuestas[$spidentif]['rNom'];//$resp['respuesta'];

								}else{
									$preguntas[$spidentif]['valResp'] = '-';
								}
								break;
							case 'num';
								$preguntas[$spidentif]['valResp'] = $sp['respuesta'];
								break;
							case 'sub':
								break;
							default:
								# code...
								break;
						}
					}
					unset($preguntas[$pidentif]['subpregs']);
				}

			}
		}
	}

	foreach ($preguntas as $k =>$p) {
		$valPreg = valPreg($preguntas,$k);
		// echo "$k -> $valPreg<br/>";
		$preguntas[$k]['valPreg'] = is_numeric($valPreg)?$valPreg:'-';
	}
	return $preguntas;
}

function valPreg(&$pregs,$identificador){
	global $db;

	// if($identificador == 'p_3_216_7_250'){
	// 	print2($pregs['p_3_216_7_250']);
	// }
	// echo "ASASAS";

	// print2($pregs);
	if($identificador != 'p_72_361_617_5230jj'){
		ob_start();
	}

	// echo $pregs[$identificador]['valPreg']."<br/>";

	if(isset($pregs[$identificador]['valPreg'])){
		print2('aaa');
		$valor = is_numeric($pregs[$identificador]['valPreg'])?$pregs[$identificador]['valPreg']:'-';
	}else{
		if(count($pregs[$identificador]['conds']) == 0){
			// echo $pregs[$identificador]['puntos'];
			$valor =  is_numeric($pregs[$identificador]['valResp'] ) &&  is_numeric($pregs[$identificador]['puntos'] )?
				$pregs[$identificador]['valResp']*$pregs[$identificador]['puntos']:
				'-';
			// echo $pregs[$identificador]['valResp']*$pregs[$identificador]['puntos'];
		}else{

			$valor =  is_numeric($pregs[$identificador]['valResp'] ) ? 
				$pregs[$identificador]['valResp']*$pregs[$identificador]['puntos']:
				'-';

			foreach ($pregs[$identificador]['conds'] as $c) {

				$ok = evalCond($pregs,$c['condicion']);
				if($ok){
					switch ($c['accion']) {
						case 1:
							

							if(is_numeric($c['valor']) || $c['valor'] == '-'){
								$valor = $c['valor'];
							}else{
								
								$p = $c['valor'];
								$pattern = '/ /';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/[\+\*\-\/?:]/';
								$p = preg_replace($pattern, '', $p);
								// echo $p;
								$pattern = '/val/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/contar/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/pos/';

								$p = preg_replace($pattern, '', $p);
								$pattern = '/\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/Y/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/O/';
								$p = preg_replace($pattern, '', $p);


								$pattern = '/[0-9]+\.*[0-9]*/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/[\(\)]/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/[><=]/';
								$p = preg_replace($pattern, '', $p);



								// echo "--- $p ---<br/>";
								if($p == ''){
									// echo "asas";
									$a = evalCond($pregs,$c['valor']);
									// echo "$c[valor]----- $a ------<br/>";
									$valor = is_numeric($pregs[$identificador]['valResp']) && is_numeric($a)?
										$pregs[$identificador]['valResp'] * $a:
										'-';
								}else{
									$valor = '-';
								}
								
							}



							break;
						case 2:
							$valor = '-';
							$pregs[$identificador]['muestra'] = 0;
							break;
						case 5:
							if( is_numeric($c['valor']) || $c['valor'] == '-'){								
								$pregs[$identificador]['puntos'] = $c['valor'];
								$valor = is_numeric($pregs[$identificador]['valResp']) && is_numeric($c['valor'])?
								$pregs[$identificador]['valResp'] * $c['valor']:
								'-';
							}else{
								$p = $c['valor'];
								$pattern = '/ /';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/[\+\*\-\/?:]/';
								$p = preg_replace($pattern, '', $p);
								// echo $p;
								$pattern = '/val/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/contar/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/pos/';

								$p = preg_replace($pattern, '', $p);
								$pattern = '/\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/Y/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/O/';
								$p = preg_replace($pattern, '', $p);


								$pattern = '/[0-9]+\.*[0-9]*/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/[\(\)]/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/[><=]/';
								$p = preg_replace($pattern, '', $p);



								// echo "--- $p ---<br/>";
								if($p == ''){
									// echo "asas";
									$a = evalCond($pregs,$c['valor']);
									// echo "$c[valor]----- $a ------<br/>";
									$pregs[$identificador]['puntos'] = $a;
									$valor = is_numeric($pregs[$identificador]['valResp']) && is_numeric($a)?
										$pregs[$identificador]['valResp'] * $a:
										'-';
								}else{
									$valor = '-';
								}
							}
							break;
						case 6:

							if( is_numeric($c['valor']) || $c['valor'] == '-'){								
								$pregs[$identificador]['valResp'] = floatval($a);
								$pregs[$identificador]['nomResp'] = $pregs[$identificador]['tipo'] == 'mult'?
									$pregs[$identificador]['respuestas'][$c['valor']][0]['respuesta']:
									$c['valor'];
								$valor = '-';


							}else{
								$p = $c['valor'];
								$pattern = '/ /';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/[\+\*\-\/?:]/';
								$p = preg_replace($pattern, '', $p);
								// echo $p;
								$pattern = '/val/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/contar/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/pos/';

								$p = preg_replace($pattern, '', $p);
								$pattern = '/\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/Y/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/O/';
								$p = preg_replace($pattern, '', $p);


								$pattern = '/[0-9]+\.*[0-9]*/';
								$p = preg_replace($pattern, '', $p);
								$pattern = '/[\(\)]/';
								$p = preg_replace($pattern, '', $p);

								$pattern = '/[><=]/';
								$p = preg_replace($pattern, '', $p);



								// echo "--- $p ---<br/>";
								if($p == ''){
									// echo "asas";
									$a = evalCond($pregs,$c['valor']);
									// echo "$c[valor]----- $a ------<br/>";
									$pregs[$identificador]['puntos'] = $a;
									$valor = is_numeric($pregs[$identificador]['valResp']) && is_numeric($a)?
										$pregs[$identificador]['valResp'] * $a:
										'-';
								}else{
									$valor = '-';
								}
								$pregs[$identificador]['valResp'] = floatval($a);
								$pregs[$identificador]['nomResp'] = $pregs[$identificador]['tipo'] == 'mult'?
									$pregs[$identificador]['respuestas'][$a][0]['respuesta']:
									$a;
								$valor = '-';
	
							}

							break;
						default:
							# code...
							break;
					}
				}
			}
		}
	}
	echo "!!! $valor !!!";
	if($identificador != 'p_72_361_617_5230jj'){
		ob_end_clean();
	}
	return $valor;
}

function calculos($pregs,$chk){
	
	// print2($chk);
	$r['chk'] = array();
	$r['chk']['max'] = 0;
	$r['chk']['conseguido'] = '-';
	$r['chk']['pond'] = '-';
	$r['chk']['tipoProm'] = $chk['tipoProm'];
	$r['chk']['sumInf'] = '-';
	$r['chk']['numInf'] = 0;
	$r['chk']['promInf'] = 0;
	$r['chk']['pregTot'] = 0;
	$r['chk']['pregPos'] = 0;
	$r['chk']['prom'] = '-';

	$r['bloques'] = array();
	foreach ($chk['bloques'] as $bId => $b) {
		$r['bloques'][$bId] = array();
		$r['bloques'][$bId]['max'] = 0;
		$r['bloques'][$bId]['conseguido'] = '-';
		$r['bloques'][$bId]['sumInf'] = '-';
		$r['bloques'][$bId]['numInf'] = 0;
		$r['bloques'][$bId]['promInf'] = 0;
		$r['bloques'][$bId]['pond'] = '-';
		$r['bloques'][$bId]['tipoProm'] = $b['tipoProm'];
		$r['bloques'][$bId]['valMax'] = $b['valMax'];
		$r['bloques'][$bId]['muestra'] = 1;
		$r['bloques'][$bId]['pregTot'] = 0;
		$r['bloques'][$bId]['pregPos'] = 0;
		$r['bloques'][$bId]['nombre'] = $b['nombre'];
		$r['bloques'][$bId]['areas'] = array();
		$r['bloques'][$bId]['conds'] = $b['conds'];
		$r['bloques'][$bId]['encabezado'] = $b['encabezado'];
		$r['bloques'][$bId]['prom'] = '-';

		foreach ($b['areas'] as $aId => $a) {
			$r['bloques'][$bId]['areas'][$aId] = array();
			$r['bloques'][$bId]['areas'][$aId]['max'] = 0;
			$r['bloques'][$bId]['areas'][$aId]['conseguido'] = '-';
			$r['bloques'][$bId]['areas'][$aId]['sumInf'] = '-';
			$r['bloques'][$bId]['areas'][$aId]['numInf'] = 0;
			$r['bloques'][$bId]['areas'][$aId]['pond'] = '-';
			$r['bloques'][$bId]['areas'][$aId]['valMax'] = $a['valMax'];
			$r['bloques'][$bId]['areas'][$aId]['muestra'] = 1;
			$r['bloques'][$bId]['areas'][$aId]['pregTot'] = 0;
			$r['bloques'][$bId]['areas'][$aId]['pregPos'] = 0;
			$r['bloques'][$bId]['areas'][$aId]['nombre'] = $a['nombre'];
			$r['bloques'][$bId]['areas'][$aId]['conds'] = $a['conds'];
			$r['bloques'][$bId]['areas'][$aId]['efectos'] = 0;
			$r['bloques'][$bId]['areas'][$aId]['modif'] = 0;

			$aPregs = array();
			foreach ($a['preguntas'] as $p) {
				$tmp['tipo'] = $p['tipo'];
				$tmp['identificador'] = $p['identificador'];
				if($p['tipo'] == 'sub'){
					$tmp['subpregs'] = array();
					foreach ($p['subpregs'] as $sp) {
						$tmp2['tipo'] = $sp['tipo'];
						$tmp2['identificador'] = $sp['identificador'];
						$tmp['subpregs'][] = $tmp2;
					}
				}
				$aPregs[] = $tmp;
			}
			$r['bloques'][$bId]['areas'][$aId]['preguntas'] = $aPregs;
		}
	}

	foreach ($pregs as $pId => $p){
		$puntos = (is_numeric($p['valPreg']) && $p['influyeValor'] == 1)?$p['puntos']:0;
		$valPreg = (is_numeric($p['valPreg']) && $p['influyeValor'] == 1)?$p['valPreg']:'-';


		$r['chk']['max'] += $puntos;

		if( is_numeric($r['chk']['conseguido']) ){
			if ( is_numeric($valPreg) ) {
				$r['chk']['conseguido'] += $valPreg;
			}
		}else{
			$r['chk']['conseguido'] = $valPreg;
		}
		if (is_numeric($valPreg) ) {
			$r['chk']['pregTot']++;
			if($valPreg > 0){
				$r['chk']['pregPos']++;
			}
		}


		$r['bloques'][$p['bloque']]['max'] += $puntos;

		if( is_numeric($r['bloques'][$p['bloque']]['conseguido']) ){
			if( is_numeric($valPreg) ){
				$r['bloques'][$p['bloque']]['conseguido'] += $valPreg;
			}
		}else{
			$r['bloques'][$p['bloque']]['conseguido'] = $valPreg;
		}

		if( is_numeric($valPreg) ){
			$r['bloques'][$p['bloque']]['pregTot'] ++;
			if($valPreg > 0){
				$r['bloques'][$p['bloque']]['pregPos'] ++;
			}
		}

		$r['bloques'][$p['bloque']]['areas'][$p['area']]['max'] += $puntos;

		if( is_numeric($r['bloques'][$p['bloque']]['areas'][$p['area']]['conseguido']) ){
			if( is_numeric($valPreg) ){
				$r['bloques'][$p['bloque']]['areas'][$p['area']]['conseguido'] += $valPreg;
			}
		}else{
			$r['bloques'][$p['bloque']]['areas'][$p['area']]['conseguido'] = $valPreg;
		}
		if( is_numeric($valPreg)){
			$r['bloques'][$p['bloque']]['areas'][$p['area']]['pregTot'] ++;
			if($valPreg > 0){
				$r['bloques'][$p['bloque']]['areas'][$p['area']]['pregPos'] ++;
			}
		}
	}


	if( is_numeric($r['chk']['conseguido']) ){
		$r['chk']['prom'] = $r['chk']['max']>0?$r['chk']['conseguido']/$r['chk']['max']:0;
	}else{
		$r['chk']['prom'] = '-';
	}
	foreach ($r['bloques'] as $bId => $b) {
		if( is_numeric($b['conseguido']) ){
			$r['bloques'][$bId]['prom'] = $b['max'] > 0 ? $b['conseguido']/$b['max']:0;
		}else{
			$r['bloques'][$bId]['prom'] = '-';
		}
		foreach ($b['conds'] as $c) {
			// echo "$bId<br/>";
			// print2($c);
			$ok = evalCond($pregs,$c['condicion']);
			if($ok){
				// echo "Bloque: $bId<br/>SE APLICA $c[condicion]<br/> CON ACCION $c[accion] <br/> Valor $c[valor]<br/>-=-=-=-=-=<br/>";
				switch ($c['accion']) {
					case 2:
						$r['bloques'][$bId]['prom'] = '-';
						$r['bloques'][$bId]['muestra'] = 0;
						break;
					case 3:
						$r['bloques'][$bId]['prom'] = $c['valor'];
						break;
					case 4:
						if( is_numeric($r['bloques'][$bId]['prom']) && is_numeric($c['valor']) ){
							$r['bloques'][$bId]['prom'] = $r['bloques'][$bId]['prom']+(double)$c['valor'];
						}
						break;
					default:
						break;
				}
			}
		}

		foreach ($b['areas'] as $aId => $a) {
			// print2($a);
			if( is_numeric($a['conseguido']) ){
				$r['bloques'][$bId]['areas'][$aId]['prom'] = $a['max'] > 0 ? $a['conseguido']/$a['max']:0;
			}else{
				$r['bloques'][$bId]['areas'][$aId]['prom'] = '-';
			}
			foreach ($a['conds'] as $c) {
				$ok = evalCond($pregs,$c['condicion']);
				if($ok){
					switch ($c['accion']) {
						case 2:
							$r['bloques'][$bId]['areas'][$aId]['prom'] = '-';
							$r['bloques'][$bId]['areas'][$aId]['muestra'] = 0;
							break;
						case 3:
							$r['bloques'][$bId]['areas'][$aId]['prom'] = $c['valor'];
							break;
						case 4:
							if( is_numeric($r['bloques'][$bId]['areas'][$aId]['prom'])  && is_numeric($c['valor']) ){
								$r['bloques'][$bId]['areas'][$aId]['prom'] = $r['bloques'][$bId]['prom']+(double)$c['valor'];
							}
							break;
						default:
							break;
					}
				}

			}
			$prom = $r['bloques'][$bId]['areas'][$aId]['prom'];
			$r['bloques'][$bId]['areas'][$aId]['prom'] = is_numeric($prom) ? ($a['valMax']/100)*$prom : '-';
		}
	}

	foreach ($chk['conds'] as $c) {
		$ok = evalCond($pregs,$c['condicion']);
		if($ok){
			switch ($c['accion']) {
				case 3:
					$r['chk']['prom'] = $c['valor'];
					break;
				case 4:
					if( is_numeric($r['chk']['prom']) && is_numeric($c['valor']) ){
						$r['chk']['prom'] = $r['chk']['prom']+(double)$c['valor'];
					}
					break;
				default:
					break;
			}
		}
	}
	$r['chk']['pond'] = $r['chk']['max'] > 0 ? 100/$r['chk']['max'] : '-';

	foreach ($r['bloques'] as $bId => $b) {

		$r['bloques'][$bId]['pond'] = 
			$r['bloques'][$bId]['max'] > 0 ? 
				$r['bloques'][$bId]['valMax']/$r['bloques'][$bId]['max'] : 
				'-';

		switch ($r['tipoProm']) {
			case 1:
				$r['bloques'][$bId]['pond'] = $r['chk']['pond'];
				break;
			case 2:
			case 3:
			default:
				$r['bloques'][$bId]['pond'] = $r['bloques'][$bId]['pond'];
				break;
		}

		foreach ($b['areas'] as $aId => $a) {
			if(is_numeric($r['bloques'][$bId]['sumInf'])){
				$r['bloques'][$bId]['sumInf'] += is_numeric($a['prom']) ? $a['prom'] : 0;
			}else{
				$r['bloques'][$bId]['sumInf'] = $a['prom'];
			}
			if(is_numeric($a['prom'])){
				$r['bloques'][$bId]['numInf']++;
			}
			$r['bloques'][$bId]['areas'][$aId]['pond'] = 
				$r['bloques'][$bId]['areas'][$aId]['max'] > 0 ? 
					$r['bloques'][$bId]['areas'][$aId]['valMax']/$r['bloques'][$bId]['areas'][$aId]['max'] : 
					'-';
			switch ($b['tipoProm']) {
				case 1:
					$r['bloques'][$bId]['areas'][$aId]['pond'] = $r['bloques'][$bId]['pond'];
					break;
				case 2:
				case 3:
				default:
					$r['bloques'][$bId]['areas'][$aId]['pond'] = $r['bloques'][$bId]['areas'][$aId]['pond'] ;
					break;
			}

		}
		$r['bloques'][$bId]['promInf'] = 
			$r['bloques'][$bId]['numInf'] > 0 ? 
				$r['bloques'][$bId]['sumInf']/$r['bloques'][$bId]['numInf'] : 
				'-';

		switch ($b['tipoProm']) {
			case 1:
				$r['bloques'][$bId]['prom'] = $r['bloques'][$bId]['prom'];
				break;
			case 2:
				if(is_numeric($b['valMax']) && is_numeric($r['bloques'][$bId]['sumInf']) ){
					$r['bloques'][$bId]['prom'] = ($b['valMax']/100)*$r['bloques'][$bId]['sumInf'];
				}else{
					$r['bloques'][$bId]['prom'] = '-';
				}
				break;
			case 3:
				if(is_numeric($b['valMax']) && is_numeric($r['bloques'][$bId]['promInf']) ){
					$r['bloques'][$bId]['prom'] = ($b['valMax']/100)*$r['bloques'][$bId]['promInf'];
				}else{
					$r['bloques'][$bId]['prom'] = '-';
				}
				break;
			default:
				$r['bloques'][$bId]['prom'] = $r['bloques'][$bId]['prom'];
				break;
		}
		$b['prom'] = $r['bloques'][$bId]['prom'];



		if(is_numeric($r['chk']['sumInf'])){
			$r['chk']['sumInf'] += is_numeric($b['prom']) ? $b['prom'] : 0 ;
		}else{
			$r['chk']['sumInf'] = $b['prom'];
		}
		if(is_numeric($b['prom'])){
			$r['chk']['numInf']++;
		}
	}
	$r['chk']['promInf'] = $r['chk']['numInf'] > 0 ?  $r['chk']['sumInf']/$r['chk']['numInf'] : '-';

	switch ($r['chk']['tipoProm']) {
		case 1:
			$r['chk']['prom'] = $r['chk']['prom'];
			break;
		case 2:
			$r['chk']['prom'] = $r['chk']['sumInf'];
			break;
		case 3:
			$r['chk']['prom'] = $r['chk']['promInf'];
			break;
		default:
			$r['chk']['prom'] = $r['chk']['prom'];
			break;
	}

	return $r;
}

function valResp($pregs,$identificador){

	// $str -
	if(is_numeric($pregs[$identificador]['valResp'])){
		if( floatval( $pregs[$identificador]['valResp'] ) == intval($pregs[$identificador]['valResp'])  ){
		// if( is_integer($pregs[$identificador]['valResp']) ){
			$valor = intval($pregs[$identificador]['valResp']);
		}else{
			$valor = floatval($pregs[$identificador]['valResp']);
		}
	}else{
		$valor = '-';
	}
	return $valor;
}

function evalCond($pregs,$test){

	$testO = $test;
	// if($test == 'val(p_138_540_1182_9401)=0'){
	// 	print2($pregs['p_138_540_1182_9401']);
	// }
	// print2($test);
	// echo "AASASASAS";
	// $test = $c['condicion'];
	$pattern = '/ /';
	$test = preg_replace($pattern, '', $test);
	$pattern = '/val\(/';
	$test = preg_replace($pattern, 'valResp($pregs,', $test);

	$pattern = '/contar\(/';
	$test = preg_replace($pattern, 'cuenta($pregs,', $test);
	$pattern = '/pos\(/';
	$test = preg_replace($pattern, 'cuentaPos($pregs,', $test);

	$pattern = '/(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+)/';
	$test = preg_replace($pattern, '"$1"', $test);
	$pattern = '/Y/';
	$test = preg_replace($pattern, '&&', $test);
	$pattern = '/O/';
	$test = preg_replace($pattern, '||', $test);

	$pattern = '/([^<>=!]{1})=/';
	$test = preg_replace($pattern, '$1===', $test);

	$a ='$ok ='. $test.";";
	// if($testO == 'val(p_138_540_1182_9401)=0'){
	// 	echo valResp($pregs,"p_138_540_1182_9401")."<br/>";
	// 	echo gettype(valResp($pregs,"p_138_540_1182_9401"));
	// 	// print2($pregs['p_138_540_1182_9401']);
	// 	print2($a);
	// }


	// $pattern = '/(valResp\(\$pregs,"p_[0-9]+_[0-9]+_[0-9]+_[0-9]+"\))/';
	// $test2 = preg_replace($pattern, '".$1."', $test);
	// $b = '$rr = '.'"'.$test2.'"';
	// eval ($b.";");

	// echo "<br/>evalCond: --------   ".$a." -------<br/>";
	
	eval($a);

	// if($a){
	// 	echo "SI = $ok ";
	// }else{
	// 	echo "no = $ok ";
	// }
	// echo "$rr result: $ok <br/>";

	return($ok);
}

function calculaVisitas($vis,$respuestas){
	$estructura = array();
	foreach ($vis as $v) {
		// print2($v);
		$chkId = getChkId($v['id']);
		// echo "$chkId -----<br/>";
		if(!isset($estructura[$chkId])){
			$estructura[$chkId] = estructura($chkId);
			// echo "$chkId \n";
		}
		$pregs = resultadosVisitas($v['id'],$estructura[$chkId],$respuestas);
		$c[$v['id']] = calculos($pregs,$estructura[$chkId]);
		$c[$v['id']]['repeticionesId'] = $v['repeticionesId'];
		$c[$v['id']]['mId'] = $v['mId'];
		$c[$v['id']]['mNom'] = $v['mNom'];
		$c[$v['id']]['nombreHijo'] = $v['nombreHijo'];
		$c[$v['id']]['idHijo'] = $v['idHijo'];
	}
	return $c;
}

function cuentaPos($pregs,$identificador){
	$a = valResp($pregs,$identificador) > 0?1:0;
	return $a;
}

function cuenta($pregs,$identificador){
	$a = is_numeric(valResp($pregs,$identificador)) ?1:0;
	return $a;
}

function calculaTodo($calcs){
	$num = count($calcs);
	$tot['prom'] = '-';
	$tot['num'] = 0;
	$tot['bloques'] = array();
	foreach ($calcs as $c) {
		if( is_numeric($tot['prom']) ){
			if( is_numeric($c['chk']['prom']) ){
				$tot['prom'] += $c['chk']['prom'];
			}
		}else{
			$tot['prom'] = $c['chk']['prom'];
		}
		if( is_numeric($c['chk']['prom']) ){
			$tot['num']++;
		}

		foreach ($c['bloques'] as $bId => $b) {
			if(!isset($tot['bloques'][$bId]['prom'])){
				$tot['bloques'][$bId]['prom'] = $b['prom'];
				$tot['bloques'][$bId]['num'] = 0;
			}else{
				if( is_numeric($tot['bloques'][$bId]['prom']) ){
					if ( is_numeric($b['prom']) ) {
						$tot['bloques'][$bId]['prom'] += $b['prom'];
					}
				}else{
					$tot['bloques'][$bId]['prom'] = $b['prom'];
				}
			}
			if ( is_numeric($b['prom']) ) {
				$tot['bloques'][$bId]['num']++;
			}


			if(!isset($tot['bloques'][$bId]['areas'])){
				$tot['bloques'][$bId]['areas'] = array();
			}
			foreach ($b['areas'] as $aId => $a) {
				if(!isset($tot['bloques'][$bId]['areas'][$aId]['prom'])){
					$tot['bloques'][$bId]['areas'][$aId]['prom'] = $a['prom'];
					$tot['bloques'][$bId]['areas'][$aId]['num'] = 0;
				}else{
					if( is_numeric($tot['bloques'][$bId]['areas'][$aId]['prom']) ){
						if( is_numeric($a['prom']) ){
							$tot['bloques'][$bId]['areas'][$aId]['prom'] += $a['prom'];
						}
					}else{
						$tot['bloques'][$bId]['areas'][$aId]['prom'] = $a['prom'];
					}
				}
				if( is_numeric($a['prom']) ){
					$tot['bloques'][$bId]['areas'][$aId]['num']++;
				}

			}
		}
	}

	// print2($calcs);

	if( is_numeric($tot['prom']) ){
		$tot['prom'] = $tot['num']>0?$tot['prom']/$tot['num']:0;
	}else{
		$tot['prom'] = '-';
	}

	foreach ($tot['bloques'] as $bId => $b) {
		if( is_numeric($tot['bloques'][$bId]['prom']) ){
			$tot['bloques'][$bId]['prom'] = $tot['bloques'][$bId]['num']>0?$tot['bloques'][$bId]['prom']/$tot['bloques'][$bId]['num']:0;
		}else{
			$tot['bloques'][$bId]['prom'] = '-';
		}
		foreach ($b['areas'] as $aId => $a) {
			if( is_numeric($tot['bloques'][$bId]['areas'][$aId]['prom']) ){
				$tot['bloques'][$bId]['areas'][$aId]['prom'] = 
					$tot['bloques'][$bId]['areas'][$aId]['num']>0
					? $tot['bloques'][$bId]['areas'][$aId]['prom']/$tot['bloques'][$bId]['areas'][$aId]['num']
					: 0;
			}else{
				$tot['bloques'][$bId]['areas'][$aId]['prom'] = '-';
			}
		}
	}
	// print2($tot);
	return $tot;
}

function calculaTotales($calcs){
	$num = count($calcs);
	$tot['prom'] = '-';
	$tot['num'] = 0;
	foreach ($calcs as $c) {
		if( is_numeric($tot['prom']) ){
			if( is_numeric($c['chk']['prom']) ){
				$tot['prom'] += $c['chk']['prom'];
			}
		}else{
			$tot['prom'] = $c['chk']['prom'];
		}
		if( is_numeric($c['chk']['prom']) ){
			$tot['num']++;
		}
	}

	// print2($calcs);

	if( is_numeric($tot['prom']) ){
		$tot['prom'] = $tot['num']>0?$tot['prom']/$tot['num']:0;
	}else{
		$tot['prom'] = '-';
	}

	// print2($tot);
	return $tot;
}

function visUsuario($elem,$reps,$proyectoId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT v.id, r.repeticionesId, m.id as mId, m.nombre as mNom, t.nombre as nombreHijo, t.id as idHijo
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId
			WHERE v.aceptada = 100 AND $wReps AND $wDs";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT v.id, r.repeticionesId, m.id as mId, m.nombre as mNom $fields
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE";

		// echo "$LJ";
	}
	
	// echo "$sql<br/>";
	$vis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

	// print2($vis);

	return $vis;
}

function respVisitas($elem,$reps,$proyectoId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT v.id as vId, p.identificador as pId,p.identificador, rv.*, 
			CASE 
				WHEN tipo.siglas = 'mult' THEN resp.valor
				ELSE rv.respuesta
			END as valResp,

			CASE 
				WHEN tipo.siglas = 'mult' THEN resp.respuesta
				ELSE rv.respuesta
			END as rNom

			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId
			LEFT JOIN RespuestasVisita rv ON v.id = rv.visitasId

			LEFT JOIN Preguntas p ON rv.preguntasId = p.id
			LEFT JOIN Tipos tipo ON p.tiposId = tipo.id
			LEFT JOIN Respuestas resp ON rv.respuesta = resp.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT v.id as vId, p.identificador as pId,p.identificador, rv.*, 
			CASE 
				WHEN tipo.siglas = 'mult' THEN resp.valor
				ELSE rv.respuesta
			END as valResp,

			CASE 
				WHEN tipo.siglas = 'mult' THEN resp.respuesta
				ELSE rv.respuesta
			END as rNom

			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN RespuestasVisita rv ON rv.visitasId = v.id

			LEFT JOIN Preguntas p ON rv.preguntasId = p.id
			LEFT JOIN Tipos tipo ON p.tiposId = tipo.id
			LEFT JOIN Respuestas resp ON rv.respuesta = resp.id

			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE";

		// echo "$LJ";
	}
	
	// echo "$sql<br/>";
	$vis = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	// print2($vis);
	$visMod = array();
	foreach ($vis as $v => $resps) {
		$visMod[$v] = array();
		foreach ($resps as $r) {

			unset($r['pId']);
			$visMod[$v][$r['identificador']] = $r;
		}
	}

	return $visMod;
}

function buscaNietos($DEId,$numDim,&$nietos,$paso){
	global $db;
	$paso++;
	// echo "$DEId --- $numDim --- $paso<br/>";


	$sql = "SELECT de.*, d.nivel FROM DimensionesElem de
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id
		WHERE padre = $DEId";
	// echo $sql;
	$hijosDB = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	if(count($hijosDB)>0 && $paso <= $numDim+1){
		foreach ($hijosDB as $h) {
			if($h['nivel'] == $numDim){
				$nietos[] = $h['id'];
			}else{
				buscaNietos($h['id'],$numDim,$nietos,$paso);
			}
		}
	}
}

function calculosDesagregados($visCalc){
	$reps = array();
	$marca = array();
	$hijo = array();
	foreach ($visCalc as $v) {
		if( !isset($reps[$v['repeticionesId']]) ){
			$reps[$v['repeticionesId']] = array();
			$reps[$v['repeticionesId']]['vis'] = array();
			$reps[$v['repeticionesId']]['nombre'] = $v['repeticionesId'];
		}
		$reps[$v['repeticionesId']]['vis'][] = $v;

		if( !isset($marca[$v['mId']]) ){
			$marca[$v['mId']] = array();
			$marca[$v['mId']]['vis'] = array();
			$marca[$v['mId']]['nombre'] = $v['mNom'];
		}
		$marca[$v['mId']]['vis'][] = $v;

		if( !isset($hijo[$v['idHijo']]) ){
			$hijo[$v['idHijo']] = array();
			$hijo[$v['idHijo']]['vis'] = array();
			$hijo[$v['idHijo']]['nombre'] = $v['nombreHijo'];
		}
		$hijo[$v['idHijo']]['vis'][] = $v;
	}

	// echo "REPETICIONES<br/>";
	// print2($reps);
	// print2($marca);
	// print2($hijo);


	$repsTot = array();
	foreach ($reps as $rId => $r) {
		$repsTot[$rId] = calculaTodo($r['vis']);
		$repsTot[$rId]['id'] = $rId;
		$repsTot[$rId]['nombre'] = $r['nombre'];
	}

	$marcaTot = array();
	foreach ($marca as $mId => $m) {
		$marcaTot[$mId] = calculaTodo($m['vis']);
		$marcaTot[$mId]['id'] = $mId;
		$marcaTot[$mId]['nombre'] = $m['nombre'];
	}

	$hijoTot = array();
	foreach ($hijo as $hId => $h) {
		$hijoTot[$hId] = calculaTodo($h['vis']);
		$hijoTot[$hId]['id'] = $hId;
		$hijoTot[$hId]['nombre'] = $h['nombre'];
	}

	$tot = calculaTodo($visCalc);
	// print2($repsTot);
	// print2($marcaTot);
	// print2($hijoTot);

	$res['repeticiones'] = $repsTot;
	$res['marcas'] = $marcaTot;
	$res['hijos'] = $hijoTot;
	$res['total'] = $tot;

	return $res;
}

function insertaCacheVisita($visitasId){
	global $db;
	$del = $db->prepare("DELETE FROM CalculosVisita WHERE visitasId = :visitasId");
	$del->execute(array("visitasId"=>$visitasId));



	$chkId = getChkId($visitasId);
	$estructura = estructura($chkId);
	$pregs = resultados($visitasId,$estructura);
	$c = calculos($pregs,$estructura);
	// print2($c);
	// exit;
	// print2($c);

	$pc['tabla'] = 'CalculosVisita';
	$pc['datos']['visitasId'] = $visitasId;
	$pc['datos']['total'] = $c['chk']['prom'];
	foreach ($c['bloques'] as $bId => $b) {
		$pc['datos']['bloque'] = $bId;
		$pc['datos']['bloqueCalif'] = $b['prom'];
		$pc['datos']['bloqueNom'] = $b['nombre'];
		foreach ($b['areas'] as $aId => $a) {
			$pc['datos']['area'] = $aId;
			$pc['datos']['areaCalif'] = $a['prom'];
			$pc['datos']['areaNom'] = $a['nombre'];
			$rcj = inserta($pc);
			$rc = json_decode($rcj,true);
			if($rc['ok'] != 1){
				break 2;
			}
		}
	}
	return $rc;
}





// juanma 283;
// $reps = [17];
// $vis = visUsuario(0,$reps,8);
// // print2($vis);



// foreach ($vis as $v) {
// 	echo "$v[id]<br/>";
// 	$chkId = getChkId($v['id']);
// 	$estructura = estructura($chkId);
// 	$pregs = resultados($v['id'],$estructura);
// 	// print2($pregs);

// 	$c = calculos($pregs,$estructura);
// 	print2($c);
// 	break;
	
// }



// $_POST['visitaId'] = 5094;

// $chkId = getChkId($_POST['visitaId']);
// echo $chkId;
// $estructura = estructura($chkId);
// $pregs = resultados($_POST['visitaId'],$estructura);
// print2($pregs);




// $c = calculos($pregs,$estructura);
// // $cb = condBloquesAreas($c,$pregs);
// // print2($c);
// // print2($pregs);


// print2($vis);

// $vr = calculaVisitas($vis);
// $cd = calculosDesagregados($vr);

// print2($cd['total']);
// print2($vr);
// $ct = calculaTodo($vr);
// print2($ct);

?>

