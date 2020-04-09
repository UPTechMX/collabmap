<?php  

include_once '../../../lib/j/j.func.php';

// print2($_POST);

$dims = $db->query("SELECT de.* FROM DimensionesElem de
	LEFT JOIN Dimensiones d ON de.dimensionesId = d.id
	LEFT JOIN Clientes c ON d.clientesId = c.id
	WHERE padre = $_POST[elem]
	GROUP BY nombrePub")->fetchAll(PDO::FETCH_ASSOC);

echo atj($dims);





?>