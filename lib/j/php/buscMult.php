<?php

include_once '../j.func.php';

$multimedia = $db->query("SELECT m.archivo,m.visitasId,m.tipo
	FROM Multimedia m
	LEFT JOIN Visitas v ON v.id = m.visitasId
	LEFT JOIN Rotaciones rot ON v.rotacionesId = rot.id
	LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
	LEFT JOIN Proyectos p ON p.id = rep.proyectosId
	LEFT JOIN Clientes c ON c.id = p.clientesId
	LEFT JOIN Tiendas t ON t.id = rot.tiendasId

	")->fetchAll(PDO::FETCH_ASSOC);
$raiz = raiz();

$i = 1;
foreach ($multimedia as $m) {
	if(!file_exists($raiz."archivos/$m[archivo]")){
		echo "$m[visitasId]-$m[tipo] - ".($i++)."<br/>";
	}
}


?>