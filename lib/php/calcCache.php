<?php  
function buscaHijos($DEId){
	global $db;
	$sql = "SELECT de.*, d.nivel 
		FROM DimensionesElem de
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id
		WHERE padre = $DEId";
	$hijosDB = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	return $hijosDB;
}

function promTot($elem,$reps,$proyectoId,$mId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";
	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT cv.visitasId as vId, cv.*
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs AND total != '-' $wM
			GROUP BY visitasId";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT cv.visitasId as vId, cv.*
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE AND total != '-' $wM
			GROUP BY visitasId";


		// echo "$LJ";
	}
	
	// echo "$sql\n\n";
	$sql2 = "SELECT AVG(total)*100, COUNT(*) as cuenta FROM ($sql) cache";
	$vis = $db->query($sql2)->fetch(PDO::FETCH_NUM);

	// print2($vis);

	return $vis;
}

function promBloqTot($elem,$reps,$proyectoId,$mId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";

	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}



	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT cv.visitasId as vId, cv.*
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs AND bloqueCalif != '-' $wM
			GROUP BY visitasId,bloque";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT cv.visitasId as vId, cv.*
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE AND bloqueCalif != '-' $wM
			GROUP BY visitasId,bloque";


		// echo "$LJ";
	}
	
	// echo "$sql<br/>";
	$sql2 = "SELECT AVG(bloqueCalif)*100 as y,bloque,bloqueNom as name, COUNT(*) as cuenta FROM ($sql) cache GROUP BY bloque";
	$prom = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// print2($vis);

	return $prom;
}

function promAreaTot($elem,$reps,$proyectoId,$mId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";
	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT cv.visitasId as vId, cv.*
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs AND areaCalif != '-' $wM
			GROUP BY visitasId,bloque,area";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT cv.visitasId as vId, cv.*
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE AND areaCalif != '-' $wM
			GROUP BY visitasId,bloque,area";


		// echo "$LJ";
	}
	
	// echo "$sql<br/>";
	$sql2 = "SELECT bloque as bId, AVG(areaCalif)*100 as y,area,bloque,areaNom as name, COUNT(*) as cuenta FROM ($sql) cache GROUP BY bloque,area";
	$prom = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	// print2($vis);

	return $prom;
}


function promPregsArea($elem,$reps,$proyectoId,$mId,$aId){


}

function promTotMarca($elem,$reps,$proyectoId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT cv.visitasId as vId, cv.total,m.nombre,m.id
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs AND total != '-'
			GROUP BY visitasId, m.id";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT cv.visitasId as vId, cv.total,m.nombre,m.id
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE AND total != '-'
			GROUP BY visitasId, m.id";


		// echo "$LJ";
	}
	
	// echo "$sql<br/>";
	$sql2 = "SELECT AVG(total)*100 as total, nombre, id FROM ($sql) cache GROUP BY cache.id";
	$prom = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// print2($vis);

	return $prom;
}

function promTotN3($elem,$reps,$proyectoId,$mId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";


	$wDs = '0';
	$wD = "de.id = $elem";
	$wDs .= " OR ($wD) ";
	$wDs = " ($wDs) ";

	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}

	$sql = "SELECT cv.visitasId as vId, cv.total, t.nombre, t.id as tId, de.id as dId
		FROM Rotaciones r
		LEFT JOIN Visitas v ON v.rotacionesId = r.id
		LEFT JOIN Tiendas t ON r.tiendasId = t.id
		LEFT JOIN Marcas m ON t.marcasId = m.id
		LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

		LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

		WHERE v.aceptada = 100 AND $wReps AND $wDs AND total != '-' $wM
		GROUP BY visitasId,t.id";
	
	// echo "$sql<br/>";
	$sql2 = "SELECT AVG(total)*100 as tot, nombre, vId, tId, dId FROM ($sql) cache GROUP BY cache.tId";
	$vis = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// print2($vis);

	return $vis;
}

function promBloqTotN3($elem,$reps,$proyectoId,$tId,$mId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";


	$wDs = '0';
	$wD = "de.id = $elem AND t.id = $tId ";
	$wDs .= " OR ($wD) ";
	$wDs = " ($wDs) ";

	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}


	$sql = "SELECT cv.visitasId as vId, cv.*
		FROM Rotaciones r
		LEFT JOIN Visitas v ON v.rotacionesId = r.id
		LEFT JOIN Tiendas t ON r.tiendasId = t.id
		LEFT JOIN Marcas m ON t.marcasId = m.id
		LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

		LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

		WHERE v.aceptada = 100 AND $wReps AND $wDs AND bloqueCalif != '-' $wM
		GROUP BY visitasId,bloque";
	
	// echo "$sql<br/>";
	$sql2 = "SELECT AVG(bloqueCalif)*100 as y,bloque,bloqueNom as name, COUNT(*) as cuenta FROM ($sql) cache GROUP BY bloque";

	// $sql2 = "SELECT AVG(bloqueCalif)*100 as y, bloque, vId, tId FROM ($sql) cache GROUP BY cache.id";
	$vis = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// print2($vis);

	return $vis;
}

function promAreaTotN3($elem,$reps,$proyectoId,$tId,$mId){
	global $db;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";


	$wDs = '0';
	$wD = "de.id = $elem AND t.id = $tId ";
	$wDs .= " OR ($wD) ";
	$wDs = " ($wDs) ";

	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}


	$sql = "SELECT cv.visitasId as vId, cv.*
		FROM Rotaciones r
		LEFT JOIN Visitas v ON v.rotacionesId = r.id
		LEFT JOIN Tiendas t ON r.tiendasId = t.id
		LEFT JOIN Marcas m ON t.marcasId = m.id
		LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

		LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id

		WHERE v.aceptada = 100 AND $wReps AND $wDs AND areaCalif != '-' $wM
		GROUP BY visitasId,bloque,area";
	
	// echo "$sql<br/>";
	$sql2 = "SELECT bloque as bId, AVG(areaCalif)*100 as y,area,bloque,areaNom as name, COUNT(*) as cuenta FROM ($sql) cache GROUP BY bloque,area";

	// $sql2 = "SELECT AVG(bloqueCalif)*100 as y, bloque, vId, tId FROM ($sql) cache GROUP BY cache.id";
	$prom = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	// print2($vis);

	return $prom;
}

function cuentaNPS($elem,$reps,$proyectoId,$corte,$identificador,$mId){
	global $db;

	// print2($corte);
	// print2($elem);
	// print2($reps);
	// print2($proyectoId);
	// print2($corte);
	// print2($identificador);
	// print2($mId);

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;

	$when = ' CASE ';
	foreach ($corte as $c) {
		if(is_numeric($c['inf'])){
			$when .= " WHEN valresp $c[desigualdadInf] $c[inf] AND valResp $c[desigualdadSup] $c[sup] AND valResp != '-' THEN '$c[nombre]' ";
		}else{
			$when .= " WHEN valresp = '-' THEN '$c[nombre]' ";

		}
	}
	$when .= ' END as grupo ';
	// echo $when;
	// return;

	if(is_numeric($corte['inf'])){
		$wCorte = "( valResp $corte[desigualdadInf] $corte[inf] 
			AND valResp $corte[desigualdadSup] $corte[sup]) AND valResp != '-'";
	}else{
		$wCorte = "( valResp = '$corte[inf]' ) ";
	}

	if($mId != 0){
		// echo "ASAASASAS \n";
		$wM = "AND ( t.marcasId = $mId ) ";
	}
	// echo "$wM <br/><br/>";

	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT
			CASE
				WHEN ti.siglas = 'mult' THEN resp.valor
				ELSE rv.respuesta
			END as valResp
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

			LEFT JOIN RespuestasVisita rv ON rv.visitasId = v.id
			LEFT JOIN Preguntas p ON rv.preguntasId = p.id
			LEFT JOIN Tipos ti ON p.tiposId = ti.id
			LEFT JOIN Respuestas resp ON rv.respuesta = resp.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs AND p.identificador = '$identificador' $wM";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}
		// print2($wCorte);
		$sql = "SELECT 
			CASE
				WHEN ti.siglas = 'mult' THEN resp.valor
				ELSE rv.respuesta
			END as valResp

			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id

			LEFT JOIN RespuestasVisita rv ON rv.visitasId = v.id
			LEFT JOIN Preguntas p ON rv.preguntasId = p.id
			LEFT JOIN Tipos ti ON p.tiposId = ti.id
			LEFT JOIN Respuestas resp ON rv.respuesta = resp.id

			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE AND p.identificador = '$identificador' $wM";


		// echo "$LJ";
	}

	// echo "$sql<br/>";
	$sql2 = "SELECT $when,COUNT(*) as cuenta FROM ($sql) cache GROUP BY grupo";
	// echo "$sql2<br/>";
	$vis = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	// print2($vis);

	return $vis;
}

function pregImp($elem,$reps,$proyectoId,$identificador,$mId){
	global $db;

	$wIdP = ' AND ( 0 ';
	foreach ($identificador as $idP) {
		$wIdP .= " OR p.identificador = '$idP[identificador]'";
	}
	$wIdP .= ' ) ';

	// echo $wIdP;

	$wReps = '0';
	foreach ($reps as $r) {
		$wReps .= " OR repeticionesId = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;

	// $when = ' CASE ';
	// foreach ($corte as $c) {
	// 	if(is_numeric($c['inf'])){
	// 		$when .= " WHEN valresp $c[desigualdadInf] $c[inf] AND valResp $c[desigualdadSup] $c[sup] AND valResp != '-' THEN '$c[nombre]' ";
	// 	}else{
	// 		$when .= " WHEN valresp = '-' THEN '$c[nombre]' ";

	// 	}
	// }
	// $when .= ' END as grupo ';
	// echo $when;
	// return;

	if(is_numeric($corte['inf'])){
		$wCorte = "( valResp $corte[desigualdadInf] $corte[inf] 
			AND valResp $corte[desigualdadSup] $corte[sup]) AND valResp != '-'";
	}else{
		$wCorte = "( valResp = '$corte[inf]' ) ";
	}

	if($mId != 0){
		// echo "ASAASASAS \n";
		$wM = "AND ( t.marcasId = $mId ) ";
	}
	// echo "$wM <br/><br/>";

	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT
			p.identificador,
			p.pregunta,
			CASE
				WHEN ti.siglas = 'mult' THEN resp.valor
				ELSE rv.respuesta
			END as valResp
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId

			LEFT JOIN RespuestasVisita rv ON rv.visitasId = v.id
			LEFT JOIN Preguntas p ON rv.preguntasId = p.id
			LEFT JOIN Tipos ti ON p.tiposId = ti.id
			LEFT JOIN Respuestas resp ON rv.respuesta = resp.id

			WHERE v.aceptada = 100 AND $wReps AND $wDs $wIdP $wM";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}
		// print2($wCorte);
		$sql = "SELECT 
			p.identificador,
			p.pregunta,
			CASE
				WHEN ti.siglas = 'mult' THEN resp.valor
				ELSE rv.respuesta
			END as valResp

			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id

			LEFT JOIN RespuestasVisita rv ON rv.visitasId = v.id
			LEFT JOIN Preguntas p ON rv.preguntasId = p.id
			LEFT JOIN Tipos ti ON p.tiposId = ti.id
			LEFT JOIN Respuestas resp ON rv.respuesta = resp.id

			$LJ
			WHERE v.aceptada = 100 AND $wReps AND $wDE $wIdP $wM";


		// echo "$LJ";
	}

	// echo "$sql<br/>";
	$sql2 = "SELECT AVG(valResp)*100 as y,identificador,pregunta as name FROM ($sql) cache WHERE valResp != '-' GROUP BY identificador";
	// echo "$sql2<br/>";
	$vis = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// print2($vis);

	return $vis;
}

function getMarcas($elem,$proyectoId){
	global $db;

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);

	// echo "$LJ $fields  $wDE";
	if($mId != 0){
		$wM = " AND ( m.id = $mId ) ";
	}


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " ($wDs) ";

		$sql = "SELECT m.id,m.nombre
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId


			WHERE v.aceptada = 100 AND $wDs $wM
			GROUP BY m.id
			ORDER BY m.nombre";


	}else{
		$LJ = '';
		for ($i=$nivel; $i <$numDim ; $i++) { 
			if($i == $nivel){
				$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
				$wDE = " de$i.padre = $elem";
			}
		}

		$sql = "SELECT m.id,m.nombre
			FROM Rotaciones r
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			$LJ
			WHERE v.aceptada = 100 AND $wDE $wM
			GROUP BY m.id
			ORDER BY m.nombre";


		// echo "$LJ";
	}
	
	// echo "$sql\n\n<br/><br/>";
	// $sql2 = "SELECT AVG(total)*100 FROM ($sql) cache";
	$vis = $db->query($sql)->fetchAll(PDO::FETCH_NUM);

	// print2($vis);

	return $vis;
}

function getBloques($proyectoId,$elem){
	global $db;

	$sql = "SELECT b.nombre as nom, b.identificador as clase, b.id as val
	FROM Bloques b 
	LEFT JOIN Checklist chk ON b.checklistId = chk.id
	LEFT JOIN ProyectosChecklist pc ON pc.checklistId = chk.id
	WHERE pc.proyectosId = $proyectoId AND (b.elim IS NULL OR b.elim != 1) AND (chk.elim IS NULL OR chk.elim != 1)
	GROUP BY clase
	ORDER BY b.orden";

	$bloques = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

	return $bloques;
}


function promTotComp($prys,$params,$etapa){
	global $db;

	// print2($params);

	$where = $params['where'];
	$JOINS = $params['JOINS'];
	$camposInt = $params['camposInt'];
	$camposExt = $params['camposExt'];
	$grpInt = $params['grpInt'];
	$grpExt = $params['grpExt'];
	$orderInt = $params['orderInt'];
	$orderExt = $params['orderExt'];
	$wCampo = $params['wCampo'];


	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$sql = "SELECT $camposInt
		FROM Visitas v 
		LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
		
		$JOINS
		WHERE 1 $where
		$grpInt $orderInt";

	// echo "$sql\n\n<br/><br/>";
	

	
	// echo "AAA\n";
	$sql2 = "SELECT $camposExt FROM ($sql) cache $grpExt $orderExt";
	// echo "$sql2\n\n<br/><br/>";

	
	$vis = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// echo "$sql\n\n\n\n\n\n";
	// print2($vis);
	return $vis;
}



function promTotCompOrig($elem,$reps,$proyectoId,$params){
	global $db;

	$where = $params['where'];
	$JOINS = $params['JOINS'];
	$camposInt = $params['camposInt'];
	$camposExt = $params['camposExt'];
	$grpInt = $params['grpInt'];
	$grpExt = $params['grpExt'];
	$orderInt = $params['orderInt'];
	$orderExt = $params['orderExt'];
	$wCampo = $params['wCampo'];

	$aceptada = isset($params['aceptada'])?$params['aceptada']:" v.aceptada = 100 ";

	$wReps = '0';
	$reps = is_array($reps)?$reps:[0];
	foreach ($reps as $r) {
		// echo $r;
		$wReps .= " OR rep.id = $r";
	}
	$wReps = " ($wReps) ";

	$dim = $db->query("SELECT d.nivel FROM DimensionesElem de 
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id 
		WHERE de.id = $elem")->fetch(PDO::FETCH_NUM);

	$nivel = is_numeric($dim[0])?$dim[0]:0;
	// echo " =-=-=-=-=- $nivel =-=-=-=-=- \n";
	
	$clte = $db -> query("SELECT p.clientesId FROM Proyectos p WHERE id = $proyectoId")->fetch(PDO::FETCH_NUM);

	$nD = $db->query("SELECT COUNT(*) FROM Dimensiones WHERE clientesId = $clte[0]")->fetch(PDO::FETCH_NUM);
	$numDim = $nD[0];

	$nivel = is_numeric($nivel)?$nivel:0;
	// print2($de);


	$wDs = '0';
	if($nivel == $numDim){
		$wD = "de.id = $elem";
		$wDs .= " OR ($wD) ";
		$wDs = " AND ($wDs) ";

		$sql = "SELECT $camposInt
			FROM Rotaciones r
			LEFT JOIN Repeticiones rep ON rep.id = r.repeticionesId
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId
			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
			$JOINS

			WHERE $aceptada AND $wReps $wDs $where
			$grpInt $orderInt";


	}else{
		$LJ = '';
		// echo "nivel:$nivel\nnumDim:$numDim \n\n";
		for ($i=$nivel; $i < $numDim ; $i++) { 
			if($i == $nivel){
				if($i == $numDim - 1){
					$LJ .= " LEFT JOIN DimensionesElem de ON de.id = t.dimensionesElemId";
				}else{
					$LJ .= " LEFT JOIN DimensionesElem de$i ON t.dimensionesElemId = de$i.id";
				}
			}else{
				if($i == $numDim - 1){
					$LJ .= " LEFT JOIN DimensionesElem de ON de.id = de".($i-1).".padre";
				}else{
					$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
				}
			}
			if($i == $numDim - 1){
				$fields = ", de.nombrePub as nombreHijo, de.id as idHijo";
				$wDE = " AND (de.padre = $elem)";
			}
		}

		// echo "$LJ\n\n";

		$sql = "SELECT $camposInt
			FROM Rotaciones r
			LEFT JOIN Repeticiones rep ON rep.id = r.repeticionesId
			LEFT JOIN Visitas v ON v.rotacionesId = r.id
			LEFT JOIN Tiendas t ON r.tiendasId = t.id
			LEFT JOIN Marcas m ON t.marcasId = m.id
			LEFT JOIN CalculosVisita cv ON cv.visitasId = v.id
			$LJ
			$JOINS
			WHERE $aceptada AND $wReps $wDE $where
			$grpInt $orderInt";

			// echo "aaaa";
		// echo "$LJ \n\n";
	}
	// echo "$sql\n\n";
	
	// echo "AAA\n";
	$sql2 = "SELECT $camposExt FROM ($sql) cache $grpExt $orderExt";

	
	$vis = $db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);

	// echo "$sql\n\n\n\n\n\n";
	// echo "$sql2\n\n";
	// print2($vis);
	return $vis;
}


?>