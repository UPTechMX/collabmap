<?php

session_start();

class Checklist {

	function __construct($vId) {
		global $db;
	    $this->id = getChkId($vId);
	    $this->vId = $vId;
	}


	public function getEstructura(){
		global $db;

		if(empty($this->estructura)){
			// echo "SELECT estructura FROM ChecklistEst WHERE checklistId = $this->id";
			$chkDB = $db->query("SELECT estructura FROM ChecklistEst WHERE checklistId = $this->id")->fetchAll(PDO::FETCH_NUM)[0][0];
			// print2($chkDB);
			if(empty($chkDB)){
				$chk = estructuraEXT($this->id);
				$prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $this->id, estructura = ?");
				$prep -> execute(array(atj($chk)));
			}else{
				$chk = json_decode($chkDB,true);
			}
			// echo "AAAAA";
			// print2($chk);
			$this->estructura = $chk;
			return $chk;
		}else{
			return $this->estructura;
		}
	}

	public function getResultados($vId){
		if(empty($this->resultados)){
			$resultados = resultadosEXT($vId,$this->getEstructura());
			$this->resultados = $resultados;
			return $resultados;
		}else{
			return $this->resultados;
		}
	}

	public function getGeneral(){
		global $db;
		if(empty($this->general)){
			$general = $db->query("SELECT * FROM Checklist WHERE id = $this->id")->fetchAll(PDO::FETCH_ASSOC)[0];
			$this->general = $general;
			return $general;
		}else{
			return $this->general;
		}		
	}

	public function getVisita(){
		global $db;
		if(empty($this->visita[$vId])){
			$visita = $db->query("SELECT v.*
				FROM Visitas v
				WHERE v.id = $this->vId")->fetchAll(PDO::FETCH_ASSOC)[0];
			$this->visita = $visita;
			return $visita;
		}else{
			return $this->visita;
		}		
	}

	public function insertaCacheVisita($vId){
		global $db;
		$del = $db->prepare("DELETE FROM CalculosVisita WHERE visitasId = :visitasId");
		$del->execute(array("visitasId"=>$vId));

		// exit();
		$chkId = $this->id;
		$estructura = $this->getEstructura();
		$pregs = $this->getResultados($vId);
		$c = calculos($pregs,$estructura);
		// print2($c);
		// exit;
		// print2($c);

		$pc['tabla'] = 'CalculosVisita';
		$pc['datos']['visitasId'] = $vId;
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

		return empty($rc)?'{"ok":"1"}':$rc;
	}
}

function sigPreg($bId,$aId,$pId,$est,$pregs){

	// print2($est);
	// echo "($bId,$aId,$pId)<br/>";
	$preguntas = $est['bloques'][$bId]['areas'][$aId]['preguntas'];
	if($pId === 0){
		// echo "ACA<br/>";
		// print2($preguntas);
		$npId = key($preguntas);
		// echo "NPID: $npId<br/>";
	}else{
		if(!is_array($preguntas)){ // revisar
			$r['pId'] = null;
			$r['aId'] = null;
			$r['bId'] = null;
			return $r;
		}
		foreach ($preguntas as $ppId =>$p) {
			next($preguntas);
			if($ppId == $pId){
				$npId = key($preguntas);
				break;
			}
		}
	}

	if( empty($npId) ){
		// echo "aa";
		$sA = sigArea($bId,$aId,$est);
		// print2($sA);
		$aId = $sA['aId'];
		$bId = $sA['bId'];
		// echo "AID: $aId<br/>";

		if(empty($aId)){
			// echo "aaA";
			$r['pId'] = null;
			$r['aId'] = null;
			$r['bId'] = null;
			return $r;
		}else{
			return sigPreg($bId,$aId,0,$est,$pregs);
		}
	}else{
		// echo "AAAA ACAAAAA<br/>";
		// echo "npId: $npId<br/>";
		// echo "aId: $aId<br/>";
		// echo "bId: $bId<br/>";
		$r['pId'] = $npId;
		$r['aId'] = $aId;
		$r['bId'] = $bId;

		// print2($r);
		return $r;
	}
	
	// if()

	// $nb = sigBloque($bId,$est);
	// $na = sigArea($bId,$aId,$est);
}

function sigSubPreg($bId,$aId,$pId,$spId,$est,$pregs){
	// echo "PID: $pId<br/>";
	$subPreguntas = $est['bloques'][$bId]['areas'][$aId]['preguntas'][$pId]['subpregs'];
	if($spId === 0){
		$nspId = key($subPreguntas);
	}else{
		foreach ($subPreguntas as $sppId =>$sp) {
			next($subPreguntas);
			if($sppId == $spId){
				$nspId = key($subPreguntas);
				break;
			}
		}
	}

	if( empty($nspId) ){
		// echo "EMPTY";
		// echo "pId: $pId<br/>";
		$sP = sigPreg($bId,$aId,$pId,$est,$pregs);
		$pId = $sP['pId'];
		$aId = $sP['aId'];
		$bId = $sP['bId'];
		// echo "AID: $aId<br/>";

		if(empty($pId)){
			// echo "aaA";
			$r['pId'] = null;
			$r['aId'] = null;
			$r['bId'] = null;
			return $r;
		}else{
			if($pregs[$pId]['tipo'] == 'sub'){
				$ssp = sigSubPreg($bId,$aId,$pId,0,$est,$pregs);
				// print2($ssp);
				return $ssp;
			}else{
				$r['pId'] = $pId;
				$r['aId'] = $aId;
				$r['bId'] = $bId;

				return $r;
			}

		}
	}else{
		$r['pId'] = $nspId;
		$r['aId'] = $aId;
		$r['bId'] = $bId;
		return $r;
	}
}

function siguiente($pId,$est,$pregs){

	$p = $pregs[$pId];
	$bId = $p['bloque'];
	$aId = $p['area'];

	if( isset($p['subarea']) ){
		// echo "aaa";
		return sigSubPreg($bId,$aId,$p['subarea'],$pId,$est,$pregs);
	}else{
		$sP = sigPreg($bId,$aId,$pId,$est,$pregs);
		$p = $pregs[$sP['pId']];
		if($p['tipo'] == 'sub'){
			return sigSubPreg($sP['bId'],$sP['aId'],$sP['pId'],0,$est,$pregs);
		}else{
			return $sP;
		}
	}
}

function sigBloque($bId,$est){
	$bloques = $est['bloques'];

	foreach ($bloques as $bbId =>$b) {
		next($bloques);
		if($bbId == $bId){
			return key($bloques);
		}
	}
}

function sigArea($bId,$aId,$est){

	$areas = $est['bloques'][$bId]['areas'];
	// print2($areas);
	if($aId === 0){
		$naId = key($areas);
	}else{
		foreach ($areas as $aaId =>$b) {
			next($areas);
			if($aaId == $aId){
				$naId = key($areas);
				break;
			}
		}
	}

	if ( empty($naId) ) {
		$bId = sigBloque($bId,$est);
		if( empty($bId) ){
			$r['bId'] = null;
			$r['aId'] = null;
			return $r;
		}else{
			return sigArea($bId,0,$est);
		}
	}else{
		$r['bId'] = $bId;
		$r['aId'] = $naId;

		return $r;
	}
}

function regBloque($bId,$est){
	$bloques = $est['bloques'];

	foreach ($bloques as $bbId =>$b) {
		if($bbId == $bId){
			prev($bloques);
			return key($bloques);
		}
		next($bloques);
	}
}

function regArea($bId,$aId,$est){

	$areas = $est['bloques'][$bId]['areas'];
	// print2($areas);
	if($aId === 0){
		end($areas);
		$naId = key($areas);
	}else{
		$areas = is_array($areas)?$areas:array();
		foreach ($areas as $aaId =>$b) {
			if($aaId == $aId){
				prev($areas);
				$naId = key($areas);
				break;
			}
			next($areas);
		}
	}

	if ( empty($naId) ) {
		$bId = regBloque($bId,$est);
		if( empty($bId) ){
			$r['bId'] = null;
			$r['aId'] = null;
			return $r;
		}else{
			return regArea($bId,0,$est);
		}
	}else{
		$r['bId'] = $bId;
		$r['aId'] = $naId;

		return $r;
	}
}

function regPreg($bId,$aId,$pId,$est,$pregs){

	// print2($est);
	// sig
	// echo "($bId,$aId,$pId)<br/>";
	$preguntas = $est['bloques'][$bId]['areas'][$aId]['preguntas'];
	if($pId === 0){
		end($preguntas);
		$npId = key($preguntas);
	}else{
		if(!is_array($preguntas)){ //revisar
			$r['pId'] = null;
			$r['aId'] = null;
			$r['bId'] = null;
			return $r;

		}
		foreach ($preguntas as $ppId =>$p) {
			if($ppId == $pId){
				prev($preguntas);
				$npId = key($preguntas);
				break;
			}
			next($preguntas);
		}
	}

	if( empty($npId) ){
		// echo "aa";
		$sA = regArea($bId,$aId,$est);
		// print2($sA);
		$aId = $sA['aId'];
		$bId = $sA['bId'];
		// echo "AID: $aId<br/>";

		if(empty($aId)){
			// echo "aaA";
			$r['pId'] = null;
			$r['aId'] = null;
			$r['bId'] = null;
			return $r;
		}else{
			return regPreg($bId,$aId,0,$est,$pregs);
		}
	}else{
		$r['pId'] = $npId;
		$r['aId'] = $aId;
		$r['bId'] = $bId;

		return $r;
	}
	
	// if()

	// $nb = sigBloque($bId,$est);
	// $na = sigArea($bId,$aId,$est);
}

function regSubPreg($bId,$aId,$pId,$spId,$est,$pregs){
	$subPreguntas = $est['bloques'][$bId]['areas'][$aId]['preguntas'][$pId]['subpregs'];
	// print2($subPreguntas);
	if($spId === 0){
		end($subPreguntas);
		$nspId = key($subPreguntas);
	}else{
		foreach ($subPreguntas as $sppId =>$sp) {
			if($sppId == $spId){
				prev($subPreguntas);
				$nspId = key($subPreguntas);
				break;
			}
			next($subPreguntas);
		}
	}

	if( empty($nspId) ){
		// echo "EMPTY";
		// echo "pId: $pId<br/>";
		$sP = regPreg($bId,$aId,$pId,$est,$pregs);
		$pId = $sP['pId'];
		$aId = $sP['aId'];
		$bId = $sP['bId'];
		// echo "AID: $aId<br/>";

		if(empty($pId)){
			// echo "aaA";
			$r['pId'] = null;
			$r['aId'] = null;
			$r['bId'] = null;
			return $r;
		}else{
			if($pregs[$pId]['tipo'] == 'sub'){
				$ssp = regSubPreg($bId,$aId,$pId,0,$est,$pregs);
				// print2($ssp);
				return $ssp;
			}else{
				$r['pId'] = $pId;
				$r['aId'] = $aId;
				$r['bId'] = $bId;

				return $r;
			}

		}
	}else{
		$r['pId'] = $nspId;
		$r['aId'] = $aId;
		$r['bId'] = $bId;
		return $r;
	}
}

function regresar($pId,$est,$pregs){

	$p = $pregs[$pId];
	$bId = $p['bloque'];
	$aId = $p['area'];

	if( isset($p['subarea']) ){
		// echo "AAA";
		return regSubPreg($bId,$aId,$p['subarea'],$pId,$est,$pregs);
	}else{
		// echo "BBB";
		$sP = regPreg($bId,$aId,$pId,$est,$pregs);
		$p = $pregs[$sP['pId']];
		if($p['tipo'] == 'sub'){
			$rSP = regSubPreg($sP['bId'],$sP['aId'],$sP['pId'],0,$est,$pregs);
			// echo "-=-=-=-=<br/>";
			// print2($rSP);
			// echo "-=-=-=-=<br/>";
			return $rSP;
		}else{
			return $sP;
		}
	}
}

function visible($pId,$res,$est,$direccion){
	$p = $res[$pId];
	// print2($p);
	$visible = true;
	$p['conds'] = is_array($p['conds'])?$p['conds']:array();
	foreach ($p['conds'] as $c) {
		if($c['accion'] == 2){
			$v = evalCond($res,$c['condicion']);
			if($v){
				$visible = false;
			}
		}
	}

	if($visible){
		return $pId;
	}else{
		$sP = $direccion($pId,$est,$res);
		if( empty($sP['pId'])  ){
			return null;
		}
		return visible($sP['pId'],$res,$est,$direccion);
	}
}

function estructuraEXT($chkId){
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
				t.siglas as tsiglas, t.nombre as nTipo, p.justif as justif, p.comShopper,p.comVerif
				FROM Preguntas p
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

				// $chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				// [$p['identificador']]['studyAreas'] = $db -> query("SELECT sa.id, ST_AsGeoJSON(sa.geometry) as geometry
				// 	FROM Studyarea sa WHERE preguntasId = $p[id]")->fetchAll(PDO::FETCH_ASSOC);

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['justif'] = $p['justif'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['comShopper'] = $p['comShopper'];

				$chk['bloques'][$b['identificador']]['areas'][$a['identificador']]['preguntas']
				[$p['identificador']]['comVerif'] = $p['comVerif'];

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
						t.siglas as tsiglas, t.nombre as nTipo, p.justif as justif 
						FROM Preguntas p
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
						['preguntas'][$p['identificador']]['subpregs'][$sp['identificador']]['justif'] = $sp['justif'];

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

function resultadosEXT($visita,$chk){ 
	global $db;
	// print2($chk);

	$sql = "SELECT p.identificador as pId,p.identificador, rv.*, p.orden, 
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
		WHERE visitasId = $visita";

	// echo $sql."<br/>";

	$respuestas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	// print2($respuestas);

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
					case 'num':
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
					case 'cm':
					case 'spatial':
					case 'op':
					case 'ab':
						$preguntas[$pidentif]['respuesta'] = $respuestas[$pidentif][0]['respuesta'];
						$preguntas[$pidentif]['valResp'] = '-';
						// $preguntas[$pidentif]['valPreg'] = '-';
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
							case 'cm':
							case 'spatial':
							case 'op':
							case 'ab':
								$preguntas[$spidentif]['respuesta'] = $respuestas[$spidentif][0]['respuesta'];
								$preguntas[$spidentif]['valResp'] = '-';
								// $preguntas[$spidentif]['valPreg'] = '-';
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
		// print2($k);
		// print2($preguntas[$k]['valPreg']);
		// $preguntas[$k]['valPreg'] = is_numeric($valPreg)?$valPreg*$p['puntos']:'-'; // Ya lo multiplico por los puntos en valPreg

		// print2($valPreg);
	}
	return $preguntas;
}
















?>