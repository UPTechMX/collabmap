<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCache.php';
$tiempoIni = microtime(true); 

// print2($_POST);
// exit();


switch ($_POST['wData']['tipo']) {
	case '1': // total

		$pregunta = $_POST['wData']['pregunta'];
		$params['camposInt'] = "resp.valor,resp.respuesta";
		$params['grpInt'] = "GROUP BY cv.visitasId";
		$params['camposExt'] = "COUNT(*) as y,valor,respuesta";
		$params['grpExt'] = "GROUP BY valor";
		$params['orderInt'] = "ORDER BY pry.id";
		$params['orderExt'] = "ORDER BY valor DESC";
		$params['JOINS'] = "LEFT JOIN RespuestasVisita rv ON rv.visitasId = cv.visitasId
							LEFT JOIN Preguntas p ON p.id = rv.preguntasId
							LEFT JOIN Respuestas resp ON resp.id =  rv.respuesta";
		$params['where'] = "AND p.identificador = '$pregunta' AND resp.valor != '-'";


		// $params['camposInt'] = "cv.visitasId as vId, cv.*, CONCAT(p.nombre) as nRep";
		// $params['grpInt'] = "GROUP BY visitasId, p.id";
		// $params['camposExt'] = "AVG(total)*100 as y, nRep, COUNT(*) as cuenta";
		// $params['grpExt'] = "GROUP BY id";
		// $params['where'] = " AND total != '-' ";
		// $params['JOINS'] = "";
		// $params['orderInt'] = '';//"ORDER BY rep.fechaIni";
		// $params['orderExt'] = '';//"ORDER BY fechaIni";
		break;
	case '2': // bloque
		$bloque = $_POST['wData']['bloque'];
		$params['camposInt'] = "cv.visitasId as vId, cv.*, rep.id as repId, CONCAT(rep.nombre) as nRep, rep.fechaIni as fechaIni";
		$params['grpInt'] = "GROUP BY visitasId,bloque,rep.id";
		$params['camposExt'] = "AVG(bloqueCalif)*100 as y,bloque,bloqueNom as name, repId, nRep, COUNT(*) as cuenta";
		$params['grpExt'] = "GROUP BY bloque, repId";
		$params['JOINS'] = "";
		$params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY fechaIni";
		$params['where'] = " AND bloqueCalif != '-' AND cv.bloque = '$bloque' ";

		break;
	case '3': // area
		$area = $_POST['wData']['area'];
		$params['camposInt'] = "cv.visitasId as vId, cv.*, rep.id as repId, CONCAT(rep.nombre) as nRep, rep.fechaIni as fechaIni";
		$params['grpInt'] = "GROUP BY visitasId,area,rep.id";
		$params['camposExt'] = "AVG(areaCalif)*100 as y,area,areaNom as name, repId, nRep, COUNT(*) as cuenta";
		$params['grpExt'] = "GROUP BY area, repId";
		$params['JOINS'] = "";
		$params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY fechaIni";
		$params['where'] = " AND areaCalif != '-' AND cv.area = '$area' ";

		break;
	case '4': // pregunta
		$pregunta = $_POST['wData']['pregunta'];
		$params['camposInt'] = "resp.valor,resp.respuesta";
		$params['grpInt'] = "GROUP BY cv.visitasId";
		$params['camposExt'] = "COUNT(*) as y,valor,respuesta";
		$params['grpExt'] = "GROUP BY valor";
		$params['orderInt'] = "ORDER BY pry.id";
		$params['orderExt'] = "ORDER BY valor DESC";
		$params['JOINS'] = "LEFT JOIN RespuestasVisita rv ON rv.visitasId = cv.visitasId
							LEFT JOIN Preguntas p ON p.id = rv.preguntasId
							LEFT JOIN Respuestas resp ON resp.id =  rv.respuesta";
		$params['where'] = "AND p.identificador = '$pregunta' AND resp.valor != '-'";

		break;
	default:
		# code...
		break;
}

	// print2($_POST);
	// exit();

	// $tot = promTotComp($_POST['reps'],$params,$_POST['wData']['etapa']);
	// print2($tot);
	$i = 0;

	$results = array();
	switch ($_POST['wData']['analisis']) {
		case '1': // ninguno
			// $elems = array();
			$params['camposInt'] .= ", 'Acumulado' as nRep, pry.id as repId";
			$params['camposExt'] .= ", nRep,repId";
			break;
		case '2': // Proyectos
			$params['camposInt'] .= ", CONCAT(pry.nombre) as nRep, pry.id as repId";
			$params['camposExt'] .= ", nRep,repId";
			$params['grpExt'] .= ",nRep,repId";
			$params['grpInt'] .= ",pry.id";

			$dats = array();

			$results[] = $dats;

			break;
		case '3': // Dimensiones

			break;
		case '4': // Estados
			$params['camposInt'] .= ", edo.nombre as nRep, edo.id as eId,edo.id as repId ";
			$params['grpInt'] .= ",edo.id";
			$params['grpExt'] .= ",eId";
			$params['camposExt'] .= ", eId,nRep, repId ";
			$params['JOINS'] .= " LEFT JOIN Estados edo ON edo.id = c.estadosId";

			$dats = array();
			// $dats = promTotComp($_POST['reps'],$params,$_POST['wData']['etapa']);

			$results[] = $dats;

			break;
		case '5': // Municipios
			$params['camposInt'] .= ", edo.nombre as nRep, edo.id as eId,edo.id as repId ";
			$params['grpInt'] .= ",edo.id";
			$params['grpExt'] .= ",eId";
			$params['camposExt'] .= ", eId,nRep, repId ";
			$params['JOINS'] .= " LEFT JOIN Municipios edo ON edo.id = c.municipiosId";

			$dats = array();
			// $dats = promTotComp($_POST['reps'],$params,$_POST['wData']['etapa']);

			$results[] = $dats;

			break;

		default:
			break;
	}

	// print2($dats);
	// print2($results);
	$tot = promTotComp($_POST['reps'],$params,$_POST['wData']['etapa']);
	// print2($tot);


	$cats = array();
	$repes = array();
	// print2($tot);
	foreach ($tot as $r) {
		if(!isset($repes[$r['repId']])){
			$repes[$r['repId']]['nombre'] = $r['nRep'];
			$repes[$r['repId']]['categoria'] = $i;
			$cats[$i] = $r['nRep'];
			$i++;
		}
	}
	$series = array();
	switch ($_POST['wData']['grafica']) {
		case 'column':
		case 'line':
			foreach ($tot as $r) {
				$series['total']['name'] = 'Total';
				$tmp['y'] = $r['y'];
				$tmp['x'] = $repes[$r['repId']]['categoria'];//$r['nRep'];
				$tmp['cuenta'] = $r['cuenta'];
				$series['total']['data'][] = $tmp;
			}

			foreach ($results as $rep) {
				foreach ($rep as $r) {
					if( !isset( $series[$r['eleNom']] ) ){
						$series[$r['eleNom']] = array();
						$series[$r['eleNom']]['name'] = $r['eleNom'];
						$series[$r['eleNom']]['data'] = array();
					}
					$tmp['y'] = $r['y'];
					$tmp['x'] = $repes[$r['repId']]['categoria'];
					$tmp['cuenta'] = $r['cuenta'];
					$series[$r['eleNom']]['data'][] = $tmp;
				}
			}
			break;
		case 'apiladas':


			$colores = ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', 
				'#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];


			$serResp = array();
			foreach ($tot as $r){
				if( !isset($serResp[$r['respuesta']]) ){
					$serResp[$r['respuesta']] = array();
				}
				$tmp['y'] = $r['y'];
				$tmp['x'] = $repes[$r['repId']]['categoria'];//$r['nRep'];
				$serResp[$r['respuesta']][] = $tmp;
			}

			if(count($serResp) == 2){
				$colores = ['#25a002', '#c00004', '#90ed7d', '#f7a35c', '#8085e9', 
				   '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];
			}
			krsort($serResp);
			// print2($serResp);
			$i = 0;
			foreach ($serResp as $resp => $r) {

				$series["Total - $resp"] = array();
				$series["Total - $resp"]['name'] = "Total";
				foreach ($r as $rr) {
					$series["Total - $resp"]['data'][] = $rr;
				}
				$series["Total - $resp"]['stack'] = "Total";
				$series["Total - $resp"]['color'] = $colores[$i];
				$series["Total - $resp"]['respNom'] = $resp;
				if($i != 0){
					$series["Total - $resp"]['linkedTo'] = ':previous';
				}
				$i++;

			}

			// print2($results);
			$serResp = array();
			foreach ($results as $rep) {
				// echo "aaaa\n";
				$eleNom = $rep['eleNom'];
				$serResp = array();
				foreach ($rep as $r){
					if( !isset($serResp["$r[eleNom]"]) ){
						$serResp["$r[eleNom]"] = array();
					}
					$tmp['y'] = $r['y'];
					$tmp['x'] = $repes[$r['repId']]['categoria'];//$r['nRep'];
					$tmp['eleNom'] = $r['eleNom'];//$r['nRep'];
					$tmp['resp'] = $r['respuesta'];//$r['nRep'];
					$tmp['repNom'] = $r['nRep'];//$r['nRep'];
					$tmp['rId'] = $r['repId'];//$r['nRep'];
					$serResp["$r[eleNom]"][] = $tmp;
				}
			}
			// print2($serResp);

			$j = 0;
			$eles = array();
			foreach ($serResp as $eleNom => $resps) {
				foreach ($resps as $s) {
					if(!isset($color[$s['resp']])){
						$color[$s['resp']] = $colores[$j];
						$j++;
					}
					$eles["$eleNom"]["$s[resp]"][] = $s;
					
				}
			}
			// print2($eles);

			foreach ($eles as $eleNom => $resps) {
				$i = 0;
				foreach ($resps as $resp => $s) {
					// echo "$eleNom-$resp\n";
					$series["$eleNom-$resp"]['respNom'] = $resp;
					$series["$eleNom-$resp"]['name'] = $eleNom;
					$series["$eleNom-$resp"]['data'] = $s;
					$series["$eleNom-$resp"]['stack'] = $eleNom;
					$series["$eleNom-$resp"]['color'] = $color[$resp];
					if($i != 0){
						$series["$eleNom-$resp"]['linkedTo'] = ':previous';
					}
					$i++;
				}
			}


			// print2($series);



			break;
		default:
			
			break;
	}

	// print2($series);
	$serie = array();
	foreach ($series as $s) {
		$serie[] = $s;
	}
	// print2($serie);
	$tiempoFin = microtime(true);
	$tiempo = ($tiempoFin - $tiempoIni);

	// print2($cats);
	echo '{"series":'.atj($serie).',"cats":'.atj($cats).',"tiempo":"'.$tiempo.'"}';

	// print2($elems);
	// print2($series);


// echo atj($ser);

?>