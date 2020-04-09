<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCache.php';
$tiempoIni = microtime(true); 


// print2($_POST);
switch ($_POST['get']) {
	case 'marcas':
		$params['camposInt'] = "m.id as mId, m.nombre as mNom, rep.id as rId";
		$params['grpInt'] = "GROUP BY mId";
		$params['camposExt'] = "mId as val,mNom as nom, rId as clase";
		// $params['grpExt'] = "GROUP BY repId";
		// $params['where'] = " AND total != '-' ";
		$params['JOINS'] = "";
		// $params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY mNom";
		break;
	case 'repeticiones':
		$reps = $db->query("SELECT id as val,nombre as nom,'clase' as clase FROM Repeticiones WHERE proyectosId = $_POST[proyectoId] AND elim IS NULL
			ORDER BY fechaIni DESC")->fetchAll(PDO::FETCH_ASSOC);
		echo atj($reps);
		exit();
		break;
	case 'tiendas':
		$mId = $_POST['mId'];
		$params['camposInt'] = "t.id as tId, t.nombre as tNom, rep.id as rId";
		$params['grpInt'] = "GROUP BY tId";
		$params['camposExt'] = "tId as val,tNom as nom, rId as clase";
		// $params['grpExt'] = "GROUP BY repId";
		$params['where'] = "AND m.id = $mId ";
		$params['JOINS'] = "";
		// $params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY tNom";
		break;
	case 'visitas':
		$tId = $_POST['tId'];
		$params['camposInt'] = "v.id as vId, CONCAT(v.fecha) as vNom, rep.id as rId";
		$params['grpInt'] = "GROUP BY vId";
		$params['camposExt'] = "vId as val,vNom as nom, rId as clase";
		// $params['grpExt'] = "GROUP BY repId";
		$params['where'] = "AND t.id = $tId ";
		$params['JOINS'] = "";
		// $params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY vNom";
		break;
	case '3':
		$area = $_POST['wData']['area'];
		$params['camposInt'] = "cv.visitasId as vId, cv.*, rep.id as repId, CONCAT(rep.fechaIni,' al ',rep.fechaFin) as nRep";
		$params['grpInt'] = "GROUP BY visitasId,area,rep.id";
		$params['camposExt'] = "AVG(areaCalif)*100 as y,area,areaNom as name, repId, nRep";
		$params['grpExt'] = "GROUP BY area, repId";
		$params['JOINS'] = "";
		$params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY nRep";
		$params['where'] = " AND areaCalif != '-' AND cv.area = '$area' ";

		break;
	case '4':
		$pregunta = $_POST['wData']['pregunta'];
		$params['camposInt'] = "resp.valor,resp.respuesta, rep.id as repId, CONCAT(rep.fechaIni,' al ',rep.fechaFin) as nRep";
		$params['grpInt'] = "GROUP BY cv.visitasId,rep.id";
		$params['camposExt'] = "COUNT(*) as y,valor,respuesta,repId,nRep";
		$params['grpExt'] = "GROUP BY valor, nRep";
		$params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY nRep,valor DESC";
		$params['JOINS'] = "LEFT JOIN RespuestasVisita rv ON rv.visitasId = cv.visitasId
							LEFT JOIN Preguntas p ON p.id = rv.preguntasId
							LEFT JOIN Respuestas resp ON resp.id =  rv.respuesta";
		$params['where'] = "AND p.identificador = '$pregunta' AND resp.valor != '-'";

		break;
	default:
		# code...
		break;
}
	$datos = promTotComp($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$params);

	echo atj($datos);

?>