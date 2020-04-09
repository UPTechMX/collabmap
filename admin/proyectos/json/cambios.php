<?php  

include_once '../../../lib/j/j.func.php';
// print2($_POST);

$where = "1";
if(!empty($_POST['pryId'])){
	$where .= " AND proyectosId = $_POST[pryId]";
}

if(!empty($_POST['ajenos'])) {
	// return;
	$file = 'cambios/cambios.cmb';
	if(is_file($file)){
		$where .= " AND userId != ".$_SESSION['IU']['admin']['usrId']; 
		// echo "SELECT * FROM Cambios LEFT JOIN Clientes ON Cambios.clientesId = Clientes.id WHERE $where";
		$cambios = $db->query("SELECT a.id, a.clientesId, a.timestamp, a.proyectosId, a.userId, b.aPat, b.aMat, b.nombre, b.token FROM Cambios a LEFT JOIN Clientes b ON a.clientesId = b.id WHERE $where")->fetchAll(PDO::FETCH_ASSOC);
		// print2($cambios);
		$cambiosDel = $db->prepare("DELETE FROM Cambios WHERE $where");
		echo json_encode($cambios);
		sleep(5);
		$cambiosDel -> execute();
		@unlink($file);
	}
	return;
}


$file = 'cambios/cambios.cmb';
if(is_file($file)){
	$cambios = $db->query("SELECT * FROM Cambios WHERE $where")->fetchAll(PDO::FETCH_ASSOC);
	$cambiosDel = $db->prepare("DELETE FROM Cambios WHERE $where");
	echo atj($cambios);
	sleep(5);
	$cambiosDel -> execute();
	@unlink($file);
}else{
	// echo '[]';
}


?>