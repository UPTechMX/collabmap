<?php 

include_once '../j/j.func.php';

$rots = $db->query("SELECT * FROM Rotaciones")->fetchAll(PDO::FETCH_ASSOC);

foreach ($rots as $r) {
	$vId = $db->query("SELECT id FROM Visitas WHERE rotacionesId = $r[id] 
		ORDER BY id DESC LIMIT 1")->fetchAll(PDO::FETCH_NUM)[0][0];
	if(!empty($vId)){
		$db->query("UPDATE Rotaciones SET visitaAct = $vId WHERE id = $r[id]");
	}
}

echo 'FIN';

?>