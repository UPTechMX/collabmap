<?php

class Usuario {

	function __construct($uId) {
		global $db;
	    $this->id = $uId;

	    $this->getGeneral();
	    $this->nivel = $this->general['nivel'];
	}

	public function getGeneral(){
		global $db;
		if(empty($this->general)){
			$general = $db->query("SELECT * FROM usrAdmin WHERE id = $this->id")->fetchAll(PDO::FETCH_ASSOC)[0];
			$this->general = $general;
			return $general;
		}else{
			return $this->general;
		}		
	}

	public function getProyectos(){
		global $db;
		if(empty($this->proyectos)){

			if($this->getGeneral()['nivel'] == 40){
				$sql = "SELECT c.id as cId, c.nombre as cNom, p.* 
					FROM Proyectos p
					LEFT JOIN Clientes c ON c.id = p.clientesId 
					ORDER BY c.nombre, p.nombre";
			}else{
				$sql = "SELECT c.id as cId, c.nombre as cNom, p.* 
					FROM Proyectos p
					LEFT JOIN Clientes c ON c.id = p.clientesId 
					LEFT JOIN AdminProyectos ap ON ap.proyectosId = p.id AND ap.usradminId = $this->id AND ap.rol = $this->nivel
					WHERE ap.usradminId = $this->id AND ap.rol = $this->nivel
					ORDER BY c.nombre, p.nombre";
			}
			$proyectos = $db->query($sql) ->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

			$this->proyectos = $proyectos;
			return $proyectos;
		}else{
			return $this->proyectos;
		}		
	}

	public function getRepeticiones($pId){
		global $db;

		if(empty($this->repeticiones[$pId])){
			$sql = "SELECT * FROM Repeticiones WHERE proyectosId = $pId AND (elim IS NULL OR elim != 1) ORDER BY fechaIni DESC";
			// echo $sql;
			$repeticiones = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

			$this->repeticiones[$pId] = $repeticiones;
			// print2($repeticiones);
			return $repeticiones;
		}else{
			return $this->repeticiones[$pId];
		}
	}

	public function usuariosPry($pId,$nivel){
		global $db;

		if(empty($this->relacionados[$pId][$nivel])){
			$sql = "SELECT * FROM usrAdmin u
			LEFT JOIN AdminProyectos ap ON ap.usradminId = u.id
			WHERE proyectosId = $pId AND rol = $nivel AND nivel = $nivel";
			// echo $sql;
			$relacionados = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

			$this->relacionados[$pId][$nivel] = $relacionados;
			// print2($relacionados);
			return $relacionados;
		}else{
			return $this->relacionados[$pId][$nivel];
		}
	}

	public function getRotTotales($rId){
		global $db;

		if(empty($this->rotTotales[$rId])){
			$sql = "SELECT 
				CASE
					WHEN r.estatus = 0 THEN 'creadas'
					WHEN r.estatus >= 1 AND r.estatus <20 AND r.estatus != 4 THEN 'canceladas'
					WHEN r.estatus = 4 THEN 'noRealizara'
					WHEN r.estatus >= 20 AND r.estatus <60 THEN 'enviadas'
					WHEN r.estatus >= 60 AND r.estatus <90 THEN 'noRevisadas'
					WHEN r.estatus >= 90 AND r.estatus <100 THEN 'revisadas'
					WHEN r.estatus = 100 THEN 'publicadas'
					ELSE 'algo'
				END as eGroup,
				COUNT(*) as cuenta, estatus
				FROM Rotaciones r 
				WHERE repeticionesId = $rId
				GROUP BY eGroup";
			// echo $sql;
			$gpos = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

			$rotTotales = $this->cuentas($gpos);

			$rotTotales['gpos'] = $gpos;

			$this->rotTotales[$rId] = $rotTotales;
			// print2($rotTotales);
			return $rotTotales;

		}else{
			return $this->rotTotales[$rId];
		}
	}

	public function visitasGral(){
		global $db;


		if($this->nivel == 40){
			$wN = "1";
		}else{
			$wN = "ap.usradminId = $this->id AND ap.rol = $this->nivel";
		}

		$sql = "SELECT 
			CASE
				WHEN r.estatus = 0 THEN 'creadas'
				WHEN r.estatus >= 1 AND r.estatus <20 AND r.estatus != 4 THEN 'canceladas'
				WHEN r.estatus = 4 THEN 'noRealizara'
				WHEN r.estatus >= 20 AND r.estatus <60 THEN 'enviadas'
				WHEN r.estatus >= 60 AND r.estatus <90 THEN 'noRevisadas'
				WHEN r.estatus >= 90 AND r.estatus <100 THEN 'revisadas'
				WHEN r.estatus = 100 THEN 'publicadas'
				ELSE 'algo'
			END as eGroup,
			COUNT(*) as cuenta, estatus
			FROM Rotaciones r
			LEFT JOIN Repeticiones rep ON rep.id = r.repeticionesId
			LEFT JOIN AdminProyectos ap ON ap.proyectosId = rep.proyectosId AND ap.usradminId = $this->id AND ap.rol = $this->nivel
			WHERE $wN
			AND (rep.elim IS NULL OR rep.elim != 1)
			GROUP BY eGroup ";
		
		$gpos = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
		// print2($gpos);


		$tots = $this->cuentas($gpos);
		$resp['faltantes'] = $tots['faltantes'];

		$sols = $db->query("SELECT COUNT(*) as cuenta 
			FROM RotacionesPagoExt rpe
			LEFT JOIN Rotaciones r ON r.id = rpe.rotacionesId
			LEFT JOIN Repeticiones rep ON rep.id = r.repeticionesId
			LEFT JOIN AdminProyectos ap ON ap.proyectosId = rep.proyectosId AND ap.usradminId = $this->id AND ap.rol = $this->nivel
			WHERE $wN
			AND (rep.elim != 1 OR rep.elim IS NULL)
			AND rpe.estatus = 0 ")->fetchAll(PDO::FETCH_ASSOC);
		$resp['solicitudes'] = $sols[0]['cuenta'];
		
		// print2($sols);

		$visEnvAyer = $db->query("SELECT COUNT(*) as cuenta FROM 
			(SELECT rot.*,rep.nombre as repNom, t.nombre as tNom 
			FROM Rotaciones rot
			LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
			LEFT JOIN AdminProyectos ap ON ap.proyectosId = rep.proyectosId AND ap.usradminId = $this->id AND ap.rol = $this->nivel
			LEFT JOIN Tiendas t ON t.id = rot.tiendasId
			LEFT JOIN RotacionesHistorial rh ON rh.rotacionesId = rot.id AND (rh.estatus = 20 OR rh.estatus = 21 OR rh.estatus = 22) 
				AND rh.estatus = rot.estatus
			WHERE $wN
			AND TIMESTAMPDIFF(DAY, rh.timestamp, NOW()) = 1
			AND (rep.elim != 1 OR rep.elim IS NULL)
			GROUP BY rot.id)
		as t")->fetchAll(PDO::FETCH_ASSOC);
		$resp['visEnvAyer'] = $visEnvAyer[0]['cuenta'];

		$visEnv = $db->query("SELECT COUNT(*) as cuenta FROM 
			(SELECT rot.*,rep.nombre as repNom, t.nombre as tNom, p.nombre as pNom, c.nombre as cNom, rep.nombre as rNom 
			FROM Rotaciones rot
			LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
			LEFT JOIN Proyectos p ON p.id = rep.proyectosId
			LEFT JOIN Clientes c ON c.id = p.clientesId
			LEFT JOIN AdminProyectos ap ON ap.proyectosId = rep.proyectosId AND ap.usradminId = $this->id AND ap.rol = $this->nivel
			LEFT JOIN Tiendas t ON t.id = rot.tiendasId
			LEFT JOIN RotacionesHistorial rh ON rh.rotacionesId = rot.id AND (rh.estatus = 20 OR rh.estatus = 21 OR rh.estatus = 22) 
				AND rh.estatus = rot.estatus
			WHERE $wN
			AND (rot.estatus = 20 OR rot.estatus = 21 OR rot.estatus = 22) 
			AND (rep.elim != 1 OR rep.elim IS NULL)
			GROUP BY rot.id)
		as t")->fetchAll(PDO::FETCH_ASSOC);
		$resp['visEnv'] = $visEnv[0]['cuenta'];

		$visRev = $db->query("SELECT COUNT(*) as cuenta FROM 
			(SELECT rot.*,rep.nombre as repNom, t.nombre as tNom, p.nombre as pNom, c.nombre as cNom, rep.nombre as rNom 
			FROM Rotaciones rot 
			LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
			LEFT JOIN Proyectos p ON p.id = rep.proyectosId
			LEFT JOIN Clientes c ON c.id = p.clientesId
			LEFT JOIN AdminProyectos ap ON ap.proyectosId = rep.proyectosId AND ap.usradminId = $this->id AND ap.rol = $this->nivel
			LEFT JOIN Tiendas t ON t.id = rot.tiendasId
			LEFT JOIN RotacionesHistorial rh ON rh.rotacionesId = rot.id AND (rh.estatus >= 90 AND rh.estatus<100) 
				AND rh.estatus = rot.estatus
			WHERE $wN
			AND (rot.estatus >= 90 AND rot.estatus < 100 ) 
			AND (rep.elim != 1 OR rep.elim IS NULL)
			GROUP BY rot.id)
		as t")->fetchAll(PDO::FETCH_ASSOC);
		$resp['visRev'] = $visRev[0]['cuenta'];

		$visAtt = $db->query("SELECT COUNT(*) as cuenta FROM 
			(SELECT v.*,rep.nombre as repNom, t.nombre as tNom, p.nombre as pNom, c.nombre as cNom, rep.nombre as rNom 
			FROM Visitas v
			LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
			LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
			LEFT JOIN Proyectos p ON p.id = rep.proyectosId
			LEFT JOIN Clientes c ON c.id = p.clientesId
			LEFT JOIN AdminProyectos ap ON ap.proyectosId = rep.proyectosId AND ap.usradminId = $this->id AND ap.rol = $this->nivel
			LEFT JOIN Tiendas t ON t.id = rot.tiendasId
			LEFT JOIN VisitasHistorial vh ON vh.visitasId = v.id AND (vh.estatus = 91) 
				AND vh.estatus = v.aceptada
			WHERE $wN
			AND (v.aceptada = 91 ) 
			AND (rep.elim != 1 OR rep.elim IS NULL)
			GROUP BY v.id)
		as t")->fetchAll(PDO::FETCH_ASSOC);
		$resp['visAtt'] = $visAtt[0]['cuenta'];
			// print2($visEnv);

		return $resp;
	}

	public function cuentas($gpos){
		$rotTotales['total'] = 0;
		foreach ($gpos as $g => $num) {
			$rotTotales['total'] += $num[0]['cuenta'];
		}

		$rotTotales['total'] -= $gpos['noRealizara'][0]['cuenta'];

		$rotTotales['enviadas'] = $gpos['enviadas'][0]['cuenta'] + $gpos['noRevisadas'][0]['cuenta'] + 
			$gpos['revisadas'][0]['cuenta'] + $gpos['publicadas'][0]['cuenta'];
		$rotTotales['canceladas'] = empty($gpos['canceladas'][0]['cuenta'])?0:$gpos['canceladas'][0]['cuenta'];
		$rotTotales['faltantes'] = $rotTotales['total'] - $rotTotales['enviadas'];
		$rotTotales['publicadas'] = empty($gpos['publicadas'][0]['cuenta'])?0:$gpos['publicadas'][0]['cuenta'];
		$rotTotales['revisadas'] = $gpos['revisadas'][0]['cuenta'] + $rotTotales['publicadas'];
		$rotTotales['recibidas'] = $gpos['noRevisadas'][0]['cuenta'] + $gpos['revisadas'][0]['cuenta'] + $gpos['publicadas'][0]['cuenta'];

		$this->rotTotales[$rId] = $rotTotales;
		// print2($rotTotales);
		return $rotTotales;

	}

}
















?>