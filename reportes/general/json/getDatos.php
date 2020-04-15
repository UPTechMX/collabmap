<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCache.php';

// print2($_POST);

$cliente = $db->query("SELECT p.clientesId,(SELECT COUNT(*) FROM Dimensiones WHERE clientesId = p.clientesId ) as numDim
	FROM Proyectos p WHERE id = $_POST[proyectoId]")->fetch(PDO::FETCH_NUM);
// print2($cliente);

if( !isset($_POST['reps']) ){
	$rps = $db->query("SELECT id FROM Repeticiones WHERE proyectosId = $_POST[proyectoId] AND elim IS NULL")->fetchAll(PDO::FETCH_ASSOC);
	$_POST['reps'] = array();
	foreach ($rps as $r) {
		$_POST['reps'][] = $r['id'];
	}
}
// print2($_POST);
$tiempoIni = microtime(true); 

// $vis = visUsuario($_POST['elem'],$_POST['reps'],$_POST['proyectoId']);
// $resp = respVisitas($_POST['elem'],$_POST['reps'],$_POST['proyectoId']);
// // print2($resp);
// $vr = calculaVisitas($vis,$resp);
// // echo "yayayayaya \n";
// // print2($vr);

// $cd = calculosDesagregados($vr);
// echo atj($cd);

$promTot = promTot($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
$promBloqTot = promBloqTot($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
$promAreaTot = promAreaTot($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
$drilldown = array();

foreach ($promBloqTot as $k => $b) {
	$numDD = count($drilldown);
	$tmpArr['data'] = $promAreaTot[$b['bloque']];
	$tmpArr['name'] = $b['name'];
	$tmpArr['cuenta'] = $b['cuenta'];;
	$tmpArr['id'] = "Total_$b[name]";
	$drilldown[] = $tmpArr;
}



$promTotMarca = promTotMarca($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
if($cliente[1] != $_POST['nivel']){
	$hijos = buscaHijos($_POST['elem']);
	$dims = array();
	foreach ($hijos as $kh => $h) {
		$tmpDim['total'] = promTot($h['id'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId'])[0];
		$tmpDim['cuenta'] = promTot($h['id'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId'])[1];
		$tmpDim['id'] = $h['id'];
		$tmpDim['nombre'] = $h['nombrePub'];
		$dims[] = $tmpDim;
		$promBloq = promBloqTot($h['id'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
		$areas = promAreaTot($h['id'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
		// print2($areas);
		$tmpB['name'] = $h['nombrePub'];
		$tmpB['id'] = $h['nombrePub'];
		foreach ($promBloq as $i => $bp) {
			$promBloq[$i]['drilldown'] = "$h[nombre]_$bp[name]";
		}
		// print2($promBloq);
		$tmpB['data'] = $promBloq;
		$drilldown[] = $tmpB;

		foreach ($promBloq as $i => $pb) {
			$bId = $pb['bloque'];
			// echo "$bId\n";
			// print2($pb['bloqur']);
			// echo "$bp[bloque]\n";
			$promAreas = $areas[$bId];
			// print2($promAreas);
			$tmpArr['data'] = $promAreas;
			// $tmpArr['color'] = "#FF0000";
			$tmpArr['name'] = $pb['name'];
			$tmpArr['id'] = "$h[nombre]_$pb[name]";
			$drilldown[] = $tmpArr;
		}
	}
}else{
	$dims = array();
	$proms = promTotN3($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$_POST['mId']);
	// print2($proms);
	foreach ($proms as $kh => $h) {
		$tmpDim['total'] = $h['tot'];
		$tmpDim['cuenta'] = $h['cuenta'];

		$tmpDim['id'] = $h['vId'];
		$tmpDim['nombre'] = $h['nombre'];
		$dims[] = $tmpDim;
		$promBloq = promBloqTotN3($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$h['tId'],$_POST['mId']);
		$areas = promAreaTotN3($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$h['tId'],$_POST['mId']);
		// print2($areas);
		$tmpB['name'] = $h['nombre'];
		$tmpB['cuenta'] = $h['cuenta'];

		$tmpB['id'] = $h['nombre'];
		foreach ($promBloq as $i => $bp) {
			$promBloq[$i]['drilldown'] = "$h[nombre]_$bp[name]";
		}
		$tmpB['data'] = $promBloq;
		$drilldown[] = $tmpB;
		// print2($promBloq);
		foreach ($promBloq as $i => $pb) {
			$promAreas = $areas[$pb['bloque']];
			// print2($promAreas);
			$tmpArr['data'] = $promAreas;
			$tmpArr['name'] = $pb['name'];
			$tmpArr['id'] = "$h[nombre]_$pb[name]";
			$drilldown[] = $tmpArr;
		}
	}
	// print2($drilldown);
}

// print2($hijos);



foreach ($promBloqTot as $i => $bp) {
	$promBloqTot[$i]['drilldown'] = "Total_$bp[name]";
}

$tmpTot['data'] = $promBloqTot;

$tmpTot['name'] = 'Total';
$tmpTot['id'] = 'Total';
// $drilldown[count($drilldown-1)]['id'] = 'Total';
$drilldown[] = $tmpTot;


$tiempoFin = microtime(true);
$tiempo = ($tiempoFin - $tiempoIni);

echo '{"tot":"'.$promTot[0].'","cuenta":"'.$promTot[1].'","marcas":'.atj($promTotMarca).',"dims":'.atj($dims).',"drilldown":'.atj($drilldown).',"tiempo":"'.$tiempo.' Segundos"}';


// echo "\n Tiempo: $tiempo. Sec \n";

?>