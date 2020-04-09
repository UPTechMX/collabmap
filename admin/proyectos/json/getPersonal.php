<?php

include_once '../../../lib/j/j.func.php';
session_start();
$uId = $_SESSION['CM']['admin']['usrId'];
$nivel = $_SESSION['CM']['admin']['nivel'];

checaAcceso(49);

if($_POST['nivel'] == 30){	
	$personal = $db->query("SELECT u.id as uId, u.id, CONCAT(u.nombre,' ',u.aPat,' ',u.aMat) as uNom, 
		v.hora, v.horario, c.lat, c.lng, v.id as vId
		FROM usrAdmin u
		LEFT JOIN Visitas v ON v.usuarioProgramado = u.id AND fecha = '$_POST[fecha]' AND (v.estatus >= 33)
		LEFT JOIN Clientes c ON c.id = v.clientesId
		WHERE nivel = $_POST[nivel]
		ORDER BY u.id, v.hora")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
}elseif($_POST['nivel'] == 40){
	$personal = $db->query("SELECT u.id as uId, u.id, u.nombre as uNom, 
		v.hora, v.horario, c.lat, c.lng, v.id as vId
		FROM EquiposInstalacion u
		LEFT JOIN Visitas v ON v.equipo = u.id AND v.fecha = '$_POST[fecha]' AND (v.estatus >= 45)
		LEFT JOIN Clientes c ON c.id = v.clientesId
		WHERE u.fecha = '$_POST[fecha]'
		ORDER BY u.id, v.hora")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
}

echo atj($personal);

?>

