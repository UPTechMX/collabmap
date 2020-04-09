<?php

class Campo {
	
	function __construct($id) {
		global $db;
	    $this->id = $id;
	    $this->nivel = $db->query("SELECT * FROM usrAdmin WHERE id = $this->id")->fetchAll(PDO::FETCH_ASSOC)[0]['nivel'];
	}

	private function getVisUsr($min,$max){
		global $db;
		// echo "$min $max";
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT vu.*
			FROM Visitas v
			LEFT JOIN Visitas vu ON v.clientesId = vu.clientesId
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max)
			GROUP BY vu.id
			";
			// print2($sql);
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getCtesVis($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$visitas = $db->query("SELECT c.id, c.nombre, c.aPat, c.aMat, c.estadosId, c.c_mnpio, c.municipiosId, c.territorial, c.colonia, c.barrio, c.codigoPostal, c.calle, c.manzana, c.lote, c.numeroExt, c.numeroInt, c.entreCalles, c.referencia, c.telefono, c.celular1, c.celular2, c.celular3, c.estatusDoc, c.estatusIdent, c.estatusCurp, c.estatusComprobante, c.estatusPredial, c.junta, c.pwd, c.lat, c.lng, c.proyectosId, c.mail, c.estatus, c.visitasId, c.token, c.instalacionSug, c.instalacionRealizada, c.LideresComunitarios_id, c.costo, c.permiso
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max)
			")->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getCtesHist($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$visitas = $db->query("SELECT eh.*
			FROM Visitas v
			LEFT JOIN estatusHist eh ON eh.clientesId = v.clientesId
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max)
			")->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getCtesResps($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$visitas = $db->query("SELECT rv.*
			FROM Visitas v
			LEFT JOIN estatusHist eh ON eh.clientesId = v.clientesId
			LEFT JOIN RespuestasVisita rv ON rv.visitasId = eh.visitasId
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max)
			GROUP BY rv.id
			")->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	private function getVisHoy($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$visitas = $db->query("SELECT v.*, CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as nombre,
			CONCAT(IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'')) as label, c.lat, c.lng, c.id as cId,
			c.calle, c.numeroExt, c.numeroInt, e.nombre as eNom, m.nombre as mNom, c.colonia, c.codigoPostal, c.token, j.webId
			FROM Visitas v
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Estados e ON e.id = c.estadosId
			LEFT JOIN Municipios m ON m.id = c.municipiosId
			LEFT JOIN JuntasComunitarias j ON j.id = c.junta
			WHERE (v.usuarioProgramado = $this->id OR v.usuarioRealizo = $this->id) AND v.fecha = '$hoy'
			AND (v.estatus >= $min AND v.estatus<=$max)
			")->fetchAll(PDO::FETCH_ASSOC);
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

	function getInsts($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT v.*
			FROM Visitas v
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
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
			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	function getInstsCte($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT c.id, c.nombre, c.aPat, c.aMat, c.estadosId, c.c_mnpio, c.municipiosId, c.territorial, c.colonia, c.barrio, c.codigoPostal, c.calle, c.manzana, c.lote, c.numeroExt, c.numeroInt, c.entreCalles, c.referencia, c.telefono, c.celular1, c.celular2, c.celular3, c.estatusDoc, c.estatusIdent, c.estatusCurp, c.estatusComprobante, c.estatusPredial, c.junta, c.pwd, c.lat, c.lng, c.proyectosId, c.mail, c.estatus, c.visitasId, c.token, c.instalacionSug, c.instalacionRealizada, c.LideresComunitarios_id, c.costo, c.permiso
			FROM Visitas v
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
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

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	function getInstsVis($min,$max){ 
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT vu.*
			FROM Visitas v
			LEFT JOIN Visitas vu ON v.clientesId = vu.clientesId
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
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

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}
	function getInstsHist($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT eh.*
			FROM Visitas v
			LEFT JOIN estatusHist eh ON eh.clientesId = v.clientesId
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
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

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	function getInstsResp($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT rv.*
			FROM Visitas v
			LEFT JOIN estatusHist eh ON eh.clientesId = v.clientesId
			LEFT JOIN RespuestasVisita rv ON rv.visitasId = eh.visitasId
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
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

			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			GROUP BY rv.id
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	function getEvalInt($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT vEi.*
			FROM Visitas v
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
			LEFT JOIN Visitas vEi ON vEi.clientesId = c.id AND vEi.etapa = 'evaluacionInt' 
				AND vEi.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vEi.clientesId 
					AND k.etapa = 'evaluacionInt' 
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vi ON vi.clientesId = c.id AND vi.etapa = 'impacto' 
				AND vi.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vi.clientesId 
					AND z.etapa = 'impacto' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
			";
		// echo $sql;
		$visitas = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		// print2($visitas);
		return $visitas;
	}

	function getImp($min,$max){
		global $db;
		// echo "getVisHoy<br/>";
		$hoy = date('Y-m-d');
		// echo "Fecha getVisHoy : $hoy<br/>";
		$sql = "SELECT vi.*
			FROM Visitas v
			LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
			LEFT JOIN Clientes c ON c.id = v.clientesId
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
			WHERE (ei.instalador = $this->id OR v.usuarioRealizo = $this->id)
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
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
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
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
			AND (v.estatus >= $min AND v.estatus<=$max) AND (vEi.finalizada = 0 OR vEi.finalizada IS NULL)
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


	public function ctesVis($estatus,$min,$max){
		// print2('asas');
		if(empty($this->ctesVis[$estatus])){
			$this->ctesVis[$estatus] = $this->getCtesVis($min,$max);
			return $this->ctesVis[$estatus];
		}else{
			return $this->ctesVis[$estatus];
		}
	}

	public function ctesHist($estatus,$min,$max){
		// print2('asas');
		if(empty($this->ctesHist[$estatus])){
			$this->ctesHist[$estatus] = $this->getCtesHist($min,$max);
			return $this->ctesHist[$estatus];
		}else{
			return $this->ctesHist[$estatus];
		}
	}

	public function ctesResps($estatus,$min,$max){
		// print2('asas');
		if(empty($this->ctesResps[$estatus])){
			$this->ctesResps[$estatus] = $this->getCtesResps($min,$max);
			return $this->ctesResps[$estatus];
		}else{
			return $this->ctesResps[$estatus];
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



	public function insts($estatus,$min,$max){
		// print2('asas');
		if(empty($this->instHoy[$estatus])){
			$this->instHoy[$estatus] = $this->getInsts($min,$max);
			return $this->instHoy[$estatus];
		}else{
			return $this->instHoy[$estatus];
		}
	}

	public function instsCts($estatus,$min,$max){
		// print2('asas');
		if(empty($this->instHoy[$estatus])){
			$this->instHoy[$estatus] = $this->getInsts($min,$max);
			return $this->instHoy[$estatus];
		}else{
			return $this->instHoy[$estatus];
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