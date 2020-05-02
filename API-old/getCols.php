<?php

include_once '../lib/j/j.func.php';
// print2($_POST);
// $_POST['pryId'] = 2;
$cadena = "NL_$_POST[usrId]_$_POST[nivel]";
if(!password_verify($cadena,$_POST['hash'])){
	exit('[]');
}


$cols = $db -> query("SELECT DISTINCT(colonia) as col 
	FROM Clientes WHERE proyectosId = $_POST[pryId] AND (colonia IS NOT NULL AND colonia != '')
	")->fetchAll(PDO::FETCH_ASSOC);

echo atj($cols);
