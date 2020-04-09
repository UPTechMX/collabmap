<?php

include_once '../lib/j/j.func.php';
include_once '../lib/php/api.php';

// print2($_POST);
// $_POST['usrId'] = 1;
// $_POST['nivel'] = 60;

$cadena = "NL_$_POST[usrId]_$_POST[nivel]";

$respuesta = array();


$usuario = new campo($_POST['usrId']);

$visitas = array();
$clientesVisitas = array();
if($_POST['nivel'] >= 50 || $_POST['nivel'] == 30){
	$visitas = $usuario -> visUsr('visitas',33,34);
	$clientesVisitas = $usuario -> ctesVis('clientesVisitas',33,34);
	$estatusHist = $usuario -> ctesHist('estatusHist',33,34);
	$RespuestasVisita = $usuario -> ctesResps('RespuestasVisita',33,34);
}

$checklistEst = $db-> query("SELECT * FROM ChecklistEst")->fetchAll(PDO::FETCH_ASSOC);
$checklist = $db-> query("SELECT * FROM Checklist")->fetchAll(PDO::FETCH_ASSOC);
$proyectos = $db-> query("SELECT * FROM Proyectos")->fetchAll(PDO::FETCH_ASSOC);
$ProyectosChecklist = $db-> query("SELECT * FROM ProyectosChecklist")->fetchAll(PDO::FETCH_ASSOC);
$estatus = $db-> query("SELECT * FROM Estatus")->fetchAll(PDO::FETCH_ASSOC);
$usrAdmin = $db-> query("SELECT id,nombre,aPat,aMat,username,telefono FROM usrAdmin")->fetchAll(PDO::FETCH_ASSOC);


// print2($clientesVisitas);

$respuesta['post'] = $_POST;
$respuesta['Visitas'] = $visitas;
$respuesta['clientesVisitas'] = $clientesVisitas;
$respuesta['ChecklistEst'] = $checklistEst;
$respuesta['estatusHist'] = $estatusHist;
$respuesta['Checklist'] = $checklist;
$respuesta['Proyectos'] = $proyectos;
$respuesta['Estatus'] = $estatus;
$respuesta['usrAdmin'] = $usrAdmin;
$respuesta['ProyectosChecklist'] = $ProyectosChecklist;
$respuesta['RespuestasVisita'] = $RespuestasVisita;


echo atj($respuesta);


?>