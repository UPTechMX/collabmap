<?php

class Cliente {
	
	function __construct($id) {
		global $db;
	    $this->id = $id;
	}

	public function getProgramadas($repId){
		global $db;
		return $db->query("SELECT COUNT(*) FROM Rotaciones WHERE repeticionesId = $repId AND estatus != 4")->fetchAll(PDO::FETCH_NUM)[0][0];
	}

	public function getPendientes($repId){
		global $db;
		return $db->query("SELECT COUNT(*) FROM Rotaciones 
			WHERE repeticionesId = $repId AND estatus >=20 AND estatus < 60")->fetchAll(PDO::FETCH_NUM)[0][0];
	}

	public function getRealizadas($repId){
		global $db;
		return $db->query("SELECT COUNT(*) FROM Rotaciones 
			WHERE repeticionesId = $repId AND estatus >= 60")->fetchAll(PDO::FETCH_NUM)[0][0];
	}

	public function getCanceladas($repId){
		global $db;
		return $db->query("SELECT COUNT(*) FROM Rotaciones 
			WHERE repeticionesId = $repId AND estatus <20 AND (estatus != 0 AND estatus != 4)")->fetchAll(PDO::FETCH_NUM)[0][0];
	}

	public function getNoAsignadas($repId){
		global $db;
		return $db->query("SELECT COUNT(*) FROM Rotaciones 
			WHERE repeticionesId = $repId AND estatus = 0 ")->fetchAll(PDO::FETCH_NUM)[0][0];
	}

	private function getInf(){
		global  $db;

		$info = $db->query("SELECT * FROM Clientes 
			WHERE id = $this->id ")->fetchAll(PDO::FETCH_ASSOC)[0];

		return $info;
	}

	public function inf(){
		if(empty($this->inf)){
			$this->inf = $this->getInf();
		}

		return $this->inf;
	}

	private function getProyectos(){
		global $db;
		return $db->query("SELECT p.*, CONCAT(ua.nombre,' ',ua.aPat,' ',ua.aMat) as vNom
			FROM Proyectos p
			LEFT JOIN usrAdmin ua ON ua.id = p.vendedor 
			WHERE clientesId = $this->id")->fetchAll(PDO::FETCH_ASSOC);
	}

	public function proyectos(){
		if(empty($this->proyectos)){
			$this->proyectos = $this->getProyectos();
		}
		return $this->proyectos;
	}


}


















?>