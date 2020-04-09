<?php

include_once '../lib/j/j.func.php';

include_once raiz().'lib/php/calcCache.php';

$tiempoIni = microtime(true); 

// $params['camposInt'] = "cv.visitasId as vId, cv.*, rep.id as repId, CONCAT(rep.nombre) as nRep";
$cuenta = 0;
$bloques = $db->query("SELECT * FROM Bloques WHERE checklistId = 348")->fetchAll(PDO::FETCH_ASSOC);
foreach ($bloques as $b) {
	$areas = $db->query("SELECT * FROM Areas WHERE bloquesId = $b[id]")->fetchAll(PDO::FETCH_ASSOC);
	foreach ($areas as $a) {
		if($a['identificador'] == 'a_23_255_179'){
			continue;
		}
		$params['camposInt'] = "resp.valor,resp.respuesta, rep.id as repId, rep.nombre as repNom,p.pregunta,p.identificador as pTema,t.nombre as tNom";
		$params['grpInt'] = "GROUP BY rv.visitasId, pTema";
		$params['camposExt'] = "AVG(valor) as y,valor,pregunta,repId,repNom";
		$params['grpExt'] = "GROUP BY pTema";
		$params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY repNom,valor DESC";

		$params['JOINS'] = "LEFT JOIN RespuestasVisita rv ON rv.visitasId = cv.visitasId
							LEFT JOIN Preguntas p ON p.id = rv.preguntasId
							LEFT JOIN Respuestas resp ON resp.id =  rv.respuesta
							LEFT JOIN Areas ar ON ar.id = p.areasId
							";
		$params['where'] = "AND ar.identificador = '$a[identificador]' AND (resp.valor != '-' OR resp.valor IS NOT NULL)";


		$dats = promTotComp($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$params);

		$cuenta += count($dats);
		// print2($dats);

	}
}

echo "\n$cuenta\n";
// print2($bloques);


$tiempoFin = microtime(true);
$tiempo = ($tiempoFin - $tiempoIni);

echo "\n$tiempo\n";


?>