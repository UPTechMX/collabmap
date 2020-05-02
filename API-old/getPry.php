<?php

include_once '../lib/j/j.func.php';
$cadena = "NL_$_POST[usrId]_$_POST[nivel]";
if(!password_verify($cadena,$_POST['hash'])){
	exit('[]');
}


$pry = $db -> query("SELECT * FROM Proyectos")->fetchAll(PDO::FETCH_ASSOC);

echo atj($pry);
