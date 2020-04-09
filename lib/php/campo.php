<?php

class Campo {
	
	function __construct($id) {
		global $db;
	    $this->id = $id;
	    $this->nivel = $db->query("SELECT * FROM usrAdmin WHERE id = $this->id")->fetchAll(PDO::FETCH_ASSOC)[0]['nivel'];
	}

	private function getVisUsr($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$visitas = $db->query("SELECT v.*
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max)
			")->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getVisHoy($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha = '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max)
			";
		// echo $sql.'<br/>';
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}


	private function getVisFut($min,$max){
		global $db;
		$hoy = date('Y-m-d');
		$visitas = $db->query("SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta

			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha > '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max)
			")->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getVisAnt($min,$max){
		global $db;
		$hoy = date('Y-m-d');
		$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta

			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha < '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getInstHoy($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal,
			vi.finalizada as viFin, vi.id as viId, vi.estatus as viEstatus, vEi.id as vEiId, vEi.estatus as vEiEstatus, 
			vEi.finalizada as vEiFin, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Visitas vi ON vi.clientesId = c.id AND vi.etapa = 'impacto' 
				AND vi.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vi.clientesId 
					AND z.etapa = 'impacto' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vEi ON vEi.clientesId = c.id AND vEi.etapa = 'evaluacionInt' 
				AND vEi.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vEi.clientesId 
					AND k.etapa = 'evaluacionInt' 
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha = '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max) AND (c.estatus >= $min AND c.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getInstAnt($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal,
			vi.finalizada as viFin, vi.id as viId, vi.estatus as viEstatus, vEi.id as vEiId, vEi.estatus as vEiEstatus, 
			vEi.finalizada as vEiFin, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Visitas vi ON vi.clientesId = c.id AND vi.etapa = 'impacto' 
				AND vi.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vi.clientesId 
					AND z.etapa = 'impacto' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vEi ON vEi.clientesId = c.id AND vEi.etapa = 'evaluacionInt' 
				AND vEi.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vEi.clientesId 
					AND k.etapa = 'evaluacionInt' 
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha < '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max) AND (c.estatus >= $min AND c.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getInstFut($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal,
			vi.finalizada as viFin, vi.id as viId, vi.estatus as viEstatus, vEi.id as vEiId, vEi.estatus as vEiEstatus, 
			vEi.finalizada as vEiFin, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Visitas vi ON vi.clientesId = c.id AND vi.etapa = 'impacto' 
				AND vi.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vi.clientesId 
					AND z.etapa = 'impacto' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vEi ON vEi.clientesId = c.id AND vEi.etapa = 'evaluacionInt' 
				AND vEi.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vEi.clientesId 
					AND k.etapa = 'evaluacionInt' 
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha > '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max) AND (c.estatus >= $min AND c.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}



	public function insertaRespuesta($vId,$pId,$resp,$justif){
		global $db;
		$insertaResp = $db->prepare("REPLACE RespuestasVisita 
			SET visitasId = :vId, preguntasId = :pId, respuesta = :resp, justificacion = :justif");
		$arr['vId'] = $vId;
		$arr['pId'] = $pId;
		$arr['resp'] = $resp;
		$arr['justif'] = $justif;
		try {
			$insertaResp->execute($arr);
			$r['ok'] = 1;
		} catch (Exception $e) {
			$r['ok'] = 0;
			$r['err'] = 'Error al insertar la respuesta Err: EIR263';
		}
		return $r;
	}

	public function verifVis($vId){
		global $db;
		$fecha = date('Y-m-d');

		if(is_numeric($vId)){
			$vis = $db->query("SELECT v.shoppersId, rot.fecha, rot.fechaLimite, v.aceptada
				FROM Visitas v
				LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
				WHERE v.id = $vId")->fetchAll(PDO::FETCH_ASSOC)[0];
			if($vis['shoppersId'] == $this->id && 
				$vis['fecha'] <= $fecha && 
				$vis['fechaLimite']>=$fecha)
			{
				return true;
			}
			else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function visUsr($estatus,$min,$max){
		// print2('asas');
		if(empty($this->visUsr[$estatus])){
			$this->visUsr[$estatus] = $this->getVisUsr($min,$max);
			return $this->visUsr[$estatus];
		}else{
			return $this->visUsr[$estatus];
		}
	}

	public function visHoy($estatus,$min,$max){
		// print2('asas');
		if(empty($this->visHoy[$estatus])){
			$this->visHoy[$estatus] = $this->getVisHoy($min,$max);
			return $this->visHoy[$estatus];
		}else{
			return $this->visHoy[$estatus];
		}
	}


	public function visAnt($estatus,$min,$max){
		// print2('asas');
		if(empty($this->visAnt[$estatus])){
			$this->visAnt[$estatus] = $this->getVisAnt($min,$max);
			return $this->visAnt[$estatus];
		}else{
			return $this->visAnt[$estatus];
		}
	}

	public function visFut($estatus,$min,$max){
		// print2('asas');
		if(empty($this->visFut[$estatus])){
			$this->visFut[$estatus] = $this->getVisFut($min,$max);
			return $this->visFut[$estatus];
		}else{
			return $this->visFut[$estatus];
		}
	}



	public function instHoy($estatus,$min,$max){
		// print2('asas');
		if(empty($this->instHoy[$estatus])){
			$this->instHoy[$estatus] = $this->getInstHoy($min,$max);
			return $this->instHoy[$estatus];
		}else{
			return $this->instHoy[$estatus];
		}
	}

	public function instAnt($estatus,$min,$max){
		// print2('asas');
		if(empty($this->instAnt[$estatus])){
			$this->instAnt[$estatus] = $this->getInstAnt($min,$max);
			return $this->instAnt[$estatus];
		}else{
			return $this->instAnt[$estatus];
		}
	}

	public function instFut($estatus,$min,$max){
		// print2('asas');
		if(empty($this->instFut[$estatus])){
			$this->instFut[$estatus] = $this->getInstFut($min,$max);
			return $this->instFut[$estatus];
		}else{
			return $this->instFut[$estatus];
		}
	}


	private function getInf(){
		global  $db;

		$info = $db->query("SELECT * FROM usrAdmin 
			WHERE id = $this->id ")->fetchAll(PDO::FETCH_ASSOC)[0];

		return $info;
	}

	public function inf(){
		if(empty($this->inf)){
			$this->inf = $this->getInf();
		}

		return $this->inf;
	}



}


















?>