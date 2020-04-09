<?php 

include_once '../j/j.func.php';

$ok = true;
$db->beginTransaction();
// print2($_POST);

if($_POST['archivoPOS'] != '' && $_POST['proyectoId'] != ''){
	include 'importarTiendasYDims.php';
}

if($_POST['archivoDir'] != '' && $ok){
	include 'actualizaDir.php';
}

if($ok){
	$db->commit();
	echo '{"ok":"1"}';
}else{
	$db->rollback();
	echo '{"ok":0,"err":"'.$err.'"}';
}


?>