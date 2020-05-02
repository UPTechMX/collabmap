<?php

include_once '../lib/j/j.func.php';
include_once '../lib/php/api.php';
include_once '../lib/php/checklist.php';

// print2($_POST);
// $_POST['usrId'] = 1;
// $_POST['nivel'] = 60;

$cadena = "NL_$_POST[usrId]_$_POST[nivel]";
if(!password_verify($cadena,$_POST['hash'])){
	exit('[]');
}


$respuesta = array();
$usuario = new campo($_POST['usrId']);
$visitas = array();
$clientesVisitas = array();
$colonias = array();
if($_POST['nivel'] >= 50 || $_POST['nivel'] == 30){
	$visitas = $usuario -> visUsr('visitas',32,37);
	$clientesVisitas = $usuario -> ctesVis('clientesVisitas',32,37);
	$estatusHist = $usuario -> ctesHist('estatusHist',32,37);
	$RespuestasVisita = $usuario -> ctesResps('RespuestasVisita',32,37);

	$respuesta['Visitas'] = $visitas;
	$respuesta['clientesVisitas'] = $clientesVisitas;
	$respuesta['estatusHist'] = $estatusHist;
	$respuesta['RespuestasVisita'] = $RespuestasVisita;

	foreach ($clientesVisitas as $c) {
		if(!empty($c['colonia'])){
			// $colonias[$c['colonia']] = $c['colonia'];
		}
	}

}

if($_POST['nivel'] >= 50 || $_POST['nivel'] == 46){
	$EquiposInstalacion = $db-> query("SELECT * FROM EquiposInstalacion")->fetchAll(PDO::FETCH_ASSOC);
	$insts = $usuario->getInsts(45,47);
	$imp = $usuario->getImp(45,47);
	$evalInt = $usuario->getEvalInt(45,47);
	$instsCtes = $usuario->getInstsCte(45,47);
	$InstsVis = $usuario->getInstsVis(45,47);
	$getInstsHist = $usuario->getInstsHist(45,47);
	$getInstsResp = $usuario->getInstsResp(45,47);

	$rep = $usuario->getInsts(57,58);
	$repimp = $usuario->getImp(57,58);
	$repevalInt = $usuario->getEvalInt(57,58);
	$repinstsCtes = $usuario->getInstsCte(57,58);
	$repInstsVis = $usuario->getInstsVis(57,58);
	$repgetInstsHist = $usuario->getInstsHist(57,58);
	$repgetInstsResp = $usuario->getInstsResp(57,58);


	$respuesta['EquiposInstalacion'] = $EquiposInstalacion;
	$respuesta['visitasInstalacion'] = $insts;
	$respuesta['visitasImpacto'] = $imp;
	$respuesta['visitasEvaluacionInterna'] = $eval;
	$respuesta['InstalacionesClientes'] = $instsCtes;
	$respuesta['InstalacionesClientesVisitas'] = $InstsVis;
	$respuesta['InstalacionesHistorial'] = $getInstsHist;
	$respuesta['InstalacionesRespuestas'] = $getInstsResp;

	$respuesta['RepvisitasInstalacion'] = $rep;
	$respuesta['RepvisitasImpacto'] = $repimp;
	$respuesta['RepvisitasEvaluacionInterna'] = $repeval;
	$respuesta['RepInstalacionesClientes'] = $repinstsCtes;
	$respuesta['RepInstalacionesClientesVisitas'] = $repInstsVis;
	$respuesta['RepInstalacionesHistorial'] = $repgetInstsHist;
	$respuesta['RepInstalacionesRespuestas'] = $repgetInstsResp;

	foreach ($instsCtes as $c) {
		if(!empty($c['colonia'])){
			// $colonias[$c['colonia']] = $c['colonia'];
		}
	}


}

$whereCol = ' 0 ';
$arrCol = array();
if(!empty($_POST['colonia'])){
	$colonias[$_POST['colonia']] = $_POST['colonia'];
}else{
	$whereCol = ' 0 ';
}

foreach($colonias as $col => $nom){
	$whereCol .= " OR c.colonia = ? ";
	$arrCol[] = $col;
}

$prepClentesCol = $db->prepare("SELECT c.id, c.nombre, c.aPat, c.aMat, c.estadosId, c.c_mnpio, c.municipiosId, c.territorial, c.colonia, c.barrio, 
	c.codigoPostal, c.calle, c.manzana, c.lote, c.numeroExt, c.numeroInt, c.entreCalles, c.referencia, c.telefono, c.celular1, c.celular2, c.celular3, 
	c.estatusDoc, c.estatusIdent, c.estatusCurp, c.estatusComprobante, c.estatusPredial, c.junta, c.pwd, c.lat, c.lng, c.proyectosId, c.mail, c.estatus, 
	c.visitasId, c.token, c.instalacionSug, c.instalacionRealizada, c.LideresComunitarios_id, c.costo, c.permiso 
	FROM Clientes c 
	WHERE $whereCol
");

$prepVisCol = $db->prepare("SELECT v.* 
	FROM Clientes c 
	LEFT JOIN Visitas v ON v.clientesId = c.id
	WHERE $whereCol
");

$prepVisHist = $db->prepare("SELECT eh.* 
	FROM Clientes c 
	LEFT JOIN estatusHist eh ON eh.clientesId = c.id
	WHERE $whereCol
");

$prepVisResp = $db->prepare("SELECT rv.* 
	FROM Clientes c 
	LEFT JOIN estatusHist eh ON eh.clientesId = c.id
	LEFT JOIN RespuestasVisita rv ON rv.visitasId = eh.visitasId
	WHERE $whereCol
	GROUP BY rv.id
");

$prepClentesCol->execute($arrCol);
$clientesCol = $prepClentesCol -> fetchAll(PDO::FETCH_ASSOC);

$prepVisCol->execute($arrCol);
$visCol = $prepVisCol -> fetchAll(PDO::FETCH_ASSOC);

$prepVisHist->execute($arrCol);
$histCol = $prepVisHist -> fetchAll(PDO::FETCH_ASSOC);

$prepVisResp->execute($arrCol);
$visRespCol = $prepVisResp -> fetchAll(PDO::FETCH_ASSOC);

$respuesta['clientesCol'] = $clientesCol;
$respuesta['visCol'] = $visCol;
$respuesta['histCol'] = $histCol;
$respuesta['visRespCol'] = $visRespCol;

// print2($clientesCol);

$JuntasComunitarias = $db->query("SELECT * FROM JuntasComunitarias")->fetchAll(PDO::FETCH_ASSOC);


$Checklist = $db-> query("SELECT * FROM Checklist")->fetchAll(PDO::FETCH_ASSOC);
foreach ($Checklist as $c) {
	// print2($c['id']);
	$chk = $db -> query("SELECT * FROM ChecklistEst WHERE checklistId = $c[id]")->fetchAll(PDO::FETCH_ASSOC);
	if(count($chk) == 0){
		$est = estructuraEXT($c['id']);
		$prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $c[id], estructura = ?");
		$prep -> execute(array(atj($est)));
	}
}

$ChecklistEst = $db-> query("SELECT * FROM ChecklistEst")->fetchAll(PDO::FETCH_ASSOC);
$Preguntas = $db-> query("SELECT * FROM Preguntas")->fetchAll(PDO::FETCH_ASSOC);
$Respuestas = $db-> query("SELECT * FROM Respuestas")->fetchAll(PDO::FETCH_ASSOC);
$Tipos = $db-> query("SELECT * FROM Tipos")->fetchAll(PDO::FETCH_ASSOC);
$Proyectos = $db-> query("SELECT * FROM Proyectos")->fetchAll(PDO::FETCH_ASSOC);
$ProyectosChecklist = $db-> query("SELECT * FROM ProyectosChecklist")->fetchAll(PDO::FETCH_ASSOC);
$estatus = $db-> query("SELECT * FROM Estatus")->fetchAll(PDO::FETCH_ASSOC);
$usrAdmin = $db-> query("SELECT id,nombre,aPat,aMat,username,telefono FROM usrAdmin")->fetchAll(PDO::FETCH_ASSOC);
$Dimensiones = $db-> query("SELECT * FROM Dimensiones")->fetchAll(PDO::FETCH_ASSOC);
$DimensionesElem = $db-> query("SELECT * FROM DimensionesElem")->fetchAll(PDO::FETCH_ASSOC);
$AreasEquipos = $db-> query("SELECT * FROM AreasEquipos")->fetchAll(PDO::FETCH_ASSOC);
$Instalaciones = $db-> query("SELECT * FROM Instalaciones")->fetchAll(PDO::FETCH_ASSOC);
$InstalacionesEquipos = $db-> query("SELECT * FROM InstalacionesEquipos")->fetchAll(PDO::FETCH_ASSOC);
$JuntasComunitarias = $db->query("SELECT * FROM JuntasComunitarias")->fetchAll(PDO::FETCH_ASSOC);


// print2($Instalaciones);


$respuesta['post'] = $_POST;
$respuesta['ChecklistEst'] = $ChecklistEst;
$respuesta['Checklist'] = $Checklist;
$respuesta['Preguntas'] = $Preguntas;
$respuesta['Respuestas'] = $Respuestas;
$respuesta['Tipos'] = $Tipos;
$respuesta['Proyectos'] = $Proyectos;
$respuesta['ProyectosChecklist'] = $ProyectosChecklist;
$respuesta['Estatus'] = $estatus;
$respuesta['usrAdmin'] = $usrAdmin;
$respuesta['Dimensiones'] = $Dimensiones;
$respuesta['DimensionesElem'] = $DimensionesElem;
$respuesta['AreasEquipos'] = $AreasEquipos;
$respuesta['Instalaciones'] = $Instalaciones;
$respuesta['InstalacionesEquipos'] = $InstalacionesEquipos;
$respuesta['JuntasComunitarias'] = $JuntasComunitarias;


// Quitar estas
// $Clientes = $db -> query("SELECT * FROM Clientes")->fetchAll(PDO::FETCH_ASSOC);
// $Visitas2 = $db -> query("SELECT * FROM Visitas")->fetchAll(PDO::FETCH_ASSOC);
// $respuesta['Clientes'] = $Clientes;
// $respuesta['Visitas2'] = $Visitas2;

// print2($RespuestasVisita);

// print($visitas);


echo atj($respuesta);


?>