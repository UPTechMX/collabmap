<?php


class Capacitacion{
	
	function __construct($id){
		global $db;
		$this->id = $id;
	}

	private function getPregs(){
		global $db;
		
		$pregs = $db->query("SELECT cp.id as pId, cp.* FROM CapacitacionesPreguntas cp
			WHERE capacitacionesId = $this->id AND (elim != 1 OR elim IS NULL)
			ORDER BY orden ")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

		return $pregs;
	}

	public function pregs(){
		if(!isset($this->pregs)){
			$this->pregs = $this->getPregs();
		}
		return $this->pregs;
	}

	private function getResps(){
		global $db;

		$pregs = $this->pregs();

		$resp = array();
		foreach ($pregs as $pa) {
			$p = $pa[0];
			$resp[$p['id']] = $db->query("SELECT cr.id as rId, cr.* FROM CapacitacionesRespuestas cr
				WHERE preguntasId = $p[id] AND (elim != 1 OR elim IS NULL)
				ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
		}

		return $resp;
	}

	public function resps(){
		if(!isset($this->resps)){
			$this->resps = $this->getResps();
		}
		return $this->resps;
	}

	public function evalCap($respsMult,$respsSel){
		$pregs = $this->pregs();
		$resps = $this->resps();
		$totPregs = count($pregs);
		// print2($respsMult);

		$aciertos = 0;
		foreach ($pregs as $pId => $p) {
			$p = $p[0];
			switch ($p['tipo']) {
				case 1:
					$resp = $resps[$pId][$respsMult[$pId]][0];
					if($resp['valor'] == 1){
						$aciertos++;	
					}
					break;
				case 2:
					$respsPreg = $resps[$pId];
					$numValidas = 0;
					foreach ($respsPreg as $rId => $r) {
						$r = $r[0];
						if($r['valor'] == 1){
							$numValidas++;
						}
					}
					// print2($respsSel[$pId]);
					if(count($respsSel[$pId]) != $numValidas){
						continue;
					}

					$numBuenas = 0;
					foreach ($respsSel[$pId] as $r) {
						$resp = $resps[$pId][$r][0];
						if($resp['valor'] == 1){
							$numBuenas++;
						}
					}
					if($numBuenas == $numValidas){
						$aciertos++;
					}

					// echo "\nnumValidas $pId : $numValidas\n";
					// echo "\nnumBuenas $pId : $numBuenas\n";

					break;
				default:
					# code...
					break;
			}
		}
		return $aciertos/$totPregs;
	}

	private function califShopper($uId){
		if(!class_exists('Shopper')){
			include_once raiz().'lib/php/shoppers.php';
		}
		$shopper = new Shopper($uId);

		return $shopper->capCalif($this->id);
	}

	public function getIntentoInf($uId){
		global $db;
		$capCalif = $db->query("SELECT c.minimo as cMin, c.id as capId, scc.fecha as cFecha, c.duracion as duracion, c.general,
			TIMESTAMPDIFF(MONTH, scc.fecha, NOW()) as dif, scc.calificacion as calif, sc.intento as intento, scc.estatus, scc.intentoId
			FROM ShoppersCapacitacionesCalif scc
			LEFT JOIN ShoppersCapacitaciones sc ON sc.id = scc.intentoId
			LEFT JOIN Capacitaciones c ON c.id = scc.capacitacionesId
			WHERE scc.shoppersId = $uId AND scc.capacitacionesId = $this->id")->fetchAll(PDO::FETCH_ASSOC)[0];

		return $capCalif;
	}

	public function getIntento($uId){
		global $db;
		$calif = $this->califShopper($uId);
		$intento = $this->getIntentoInf($uId);

		// print2($intento);
		// echo "Calif : $calif \n";
		if($intento['estatus'] != 1){
			if($calif == 1 || $calif == 3){
				if($calif != 3){				
					if($intento['intento'] == 1){
						$int = 2;
					}else{
						$int = 1;
					}
				}else{
					$int = 1;
				}
				// $db->beginTransaction();
				try {
					
					$db->query("INSERT INTO ShoppersCapacitaciones 
						SET shoppersId = $uId, capacitacionesId = $this->id, intento = $int, fecha = NOW()");
					$intentoId = $db->lastInsertId();

					$db->query("REPLACE ShoppersCapacitacionesCalif 
						SET shoppersId = $uId, capacitacionesId = $this->id, intentoId = $intentoId, fecha = NOW(), estatus = 1");
					
					// $db->commit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					// $db->rollBack();
				}

				return $intentoId;
			}else{
				return -1;
			}
		}else{
			return $intento['intentoId'];
		}
	}

	public function insertaCalif($respsMult,$respsSel,$uId){
		global $db;
		$calif = $this->evalCap($respsMult,$respsSel);
		$intento = $this->getIntento($uId);
		$insResp = $db->prepare("INSERT INTO CapacitacionesRespuestasShopper 
			SET shoppersCapacitacionesId = $intento, preguntaId = ?, respuestaId = ?");

		$intInf = $this->getIntentoInf($uId);
		
		// $db->beginTransaction();
		try {
			$respsMult = is_array($respsMult)?$respsMult:array();
			foreach ($respsMult as $pId => $rId) {
				$arr = [$pId,$rId];
				$insResp->execute($arr);
			}

			// print2($respsSel);
			$respsSel = is_array($respsSel)?$respsSel:array();
			foreach ($respsSel as $pId => $resps) {
				foreach ($resps as $rId) {
					$arr = [$pId,$rId];
					$insResp->execute($arr);
				}
			}
			$db->query("UPDATE ShoppersCapacitaciones SET fecha = NOW(), calificacion = $calif WHERE id = $intento");
			$db->query("UPDATE ShoppersCapacitacionesCalif SET fecha = NOW(), calificacion = $calif, estatus = NULL WHERE intentoId = $intento");

			// $db->commit();
			if($calif*10 >= $intInf['cMin']){
				$ap = 1;
			}else{
				$ap = 0;
			}
			return '{"ok":"1","calif":"'.($calif*10).'","aprobada":"'.$ap.'","intento":"'.$intInf['intento'].'","general":"'.$intInf['general'].'"}';
		} catch (PDOException $e) {
			// $db->rollBack();
			// echo $e->getMessage();
			return '{"ok":"0","err":"'.$e->getMessage().'"}';
			
		}
	}

	private function getCapInf(){
		global  $db;

		$info = $db->query("SELECT * FROM Capacitaciones 
			WHERE id = $this->id ")->fetchAll(PDO::FETCH_ASSOC)[0];

		return $info;
	}

	public function capInf(){
		if(empty($this->capInf)){
			$this->capInf = $this->getCapInf();
		}

		return $this->capInf;
	}

	private function getCapMult(){
		global $db;

		$mult = $db->query("SELECT mc.* 
			FROM CapacitacionesMultimediaCap cmc
			LEFT JOIN MultimediaCap mc ON cmc.multimediaId = mc.id
			WHERE cmc.capacitacionesId = $this->id ")->fetchAll(PDO::FETCH_ASSOC);

		return $mult;
	}

	public function capMult(){
		if(empty($this->capMult)){
			$this->capMult = $this->getCapMult();
		}

		return $this->capMult;
	}



}
	
?>