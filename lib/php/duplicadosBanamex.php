<?php
include_once '../j/j.func.php';
$POS = $db->query("
	SELECT * FROM (SELECT c.nombre as cliente, m.nombre as marca, t.nombre, t.POS,   COUNT(*) as cuenta
	FROM Tiendas t
	LEFT JOIN Marcas m ON t.marcasId = m.id
	LEFT JOIN Clientes c ON m.clientesId = c.id
	WHERE c.id = 10
	GROUP BY t.POS,m.clientesId ) as tt WHERE cuenta >= 2
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($POS as $p) {
	$POSBB  = $db->query("SELECT * FROM Tiendas WHERE marcasId = 30 AND POS = $p[POS]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$POSBBV = $db->query("SELECT * FROM Tiendas WHERE marcasId = 99 AND POS = $p[POS]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$db->query("UPDATE Rotaciones SET tiendasId = $POSBB[id] WHERE tiendasId = $POSBBV[id]");
	$db->query("DELETE FROM RepeticionesTiendas WHERE tiendasId = $POSBBV[id]");
	$db->query("DELETE FROM Tiendas WHERE id = $POSBBV[id]");
}


?>