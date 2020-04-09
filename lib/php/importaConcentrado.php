<?php

	include_once '../j/j.func.php';

	$_POST['repeticionId'] = isset($_POST['repeticionId'])?$_POST['repeticionId']:12;

	// print2($_POST);

	// $ok = true;
	$insArr = array();
	$row = 0;
	$repeticionesId = $_POST['repeticionId'];

	$clienteId = $db->query("SELECT p.clientesId FROM Repeticiones r
		LEFT JOIN Proyectos p ON r.proyectosId = p.id
		WHERE r.id = $repeticionesId")->fetch(PDO::FETCH_NUM)[0];

	$clienteId = empty($clienteId)?0:$clienteId;
	// echo "Cliente ID : $clienteId<br/>";

	$buscTienda = $db->prepare("SELECT t.id as tId, t.dimensionesElemId, t.marcasId
		FROM Tiendas t 
		LEFT JOIN Marcas m ON m.id = t.marcasId
		LEFT JOIN Clientes c ON c.id = m.clientesId
		WHERE POS = ? AND c.id = $clienteId");


	$buscMarca = $db->prepare("SELECT id FROM Marcas WHERE clientesId = $clienteId AND nombre = ?");
	$buscDim = $db->prepare("SELECT de.id FROM DimensionesElem de
		LEFT JOIN Dimensiones d ON d.id = de.dimensionesId
		WHERE d.clientesId = ? AND de.padre = ? AND de.nombre  = ?");
	$ok = true;
	$db->beginTransaction();
	$post['tabla'] = 'tBnmx';
	if (($handle = fopen(raiz()."lib/archivos/$_POST[archivoConc]", "r")) !== FALSE) {
		while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {

			if($row == 0){
				// print2($col);
				$nps = 0;
				for($i = 7;$i<count($col);$i++){
					switch ($col[$i]) {
						case 'BLOQUE':
							$bNom = $col[$i+1];
							$colB = $i+1;
							$i++;
							break;
						case 'AREA':
							$aNom = $col[$i+1];
							$colA = $i+1;
							$i++;
							break;
						case 'PREGS':
							break;
						case 'NPS':
							$nps = 1;
							break;
						default:
							$c[$i]['bloqueNom'] = $bNom;
							$c[$i]['colB'] = $colB;
							$c[$i]['areaNom'] = $aNom;
							$c[$i]['colA'] = $colA;
							$c[$i]['pregunta'] = $col[$i];
							$c[$i]['NPS'] = $nps;
							$nps = 0;
							break;
					}
				}

				// print2($c);
				// break;
			}else{

				if(empty($col[1]))
					continue;

				$buscMarca -> execute(array($col[1]));
				// $mId = $buscMarca -> fetch(PDO::FETCH_NUM)[0];
				$buscTienda -> execute(array($col[4]));
				// echo $col['4']."\n";
				$bT = $buscTienda -> fetch(PDO::FETCH_NUM);
				// echo $col[4]."\n";
				// print2($bT);
				$tId = $bT[0];
				$dim3 = $bT[1];
				$mId = $bT[2];
				// $buscDim -> execute(array($mId,0,$col[1]));
				// $dim1 = $buscDim -> fetch(PDO::FETCH_NUM)[0];
				// $buscDim -> execute(array($mId,$dim1Id,$col[2]));
				// $dim2 = $buscDim -> fetch(PDO::FETCH_NUM)[0];
				// $buscDim -> execute(array($mId,$dim2Id,$col[3]));
				// $dim3 = $buscDim -> fetch(PDO::FETCH_NUM)[0];
				
				$dim2 = $db->query("SELECT padre FROM DimensionesElem WHERE id=$dim3")->fetch(PDO::FETCH_NUM)[0];
				$dim1 = $db->query("SELECT padre FROM DimensionesElem WHERE id=$dim2")->fetch(PDO::FETCH_NUM)[0];


				$arr['POS'] = $col[4];
				$arr['marcasId'] = $mId;
				$arr['tiendasId'] = $tId;
				$arr['dim1Id'] = $dim1;
				$arr['dim2Id'] = $dim2;
				$arr['dim3Id'] = $dim3;
				$arr['total'] = str_replace('%', '', $col[6]);
				$db->exec("DELETE FROM tBnmx WHERE repeticionesId = $repeticionesId AND tiendasId = $arr[tiendasId]");
				// print2($col);
				// echo "=-=-=-=-=- $row =-=-=-=-=-=-<br/>";
				foreach ($c as $i => $d) {
					
					$arr['bloqueNom'] = $c[$i]['bloqueNom'];
					$arr['bloqueCalif'] = str_replace('%', '', $col[$c[$i]['colB']]);
					$arr['areaNom'] = $c[$i]['areaNom'];
					$arr['areaCalif'] = str_replace('%', '', $col[$c[$i]['colA']]);
					$arr['pregunta'] = $c[$i]['pregunta'];
					$arr['pregCalif'] = $col[$i];
					$arr['nps'] = $c[$i]['NPS'];
					$arr['repeticionesId'] = $repeticionesId;
					$arr['col'] = $i;

					////////  

					$arr['total'] = $arr['total'] == '-' || $arr['total'] == 'NA' ? NULL : $arr['total'];
					$arr['bloqueCalif'] = $arr['bloqueCalif'] == '-' || $arr['bloqueCalif'] == 'NA' ? NULL : $arr['bloqueCalif'];
					$arr['areaCalif'] = $arr['areaCalif'] == '-' || $arr['areaCalif'] == 'NA' ? NULL : $arr['areaCalif'];
					$arr['pregCalif'] = $arr['pregCalif'] == '-' || $arr['pregCalif'] == 'NA' ? NULL : $arr['pregCalif'];

					/////////

					$post['datos'] = $arr;
					// print2($arr);


					$rj = inserta($post);
					$j = json_decode($rj,true);
					if($j['ok'] != 1){
						$ok = false;
						// print2($rj);
					}

				}

			}


			$row++;
		}

		fclose($handle);
		
	}

	if($ok){
		$db->commit();
		echo '{"ok":"1"}';
	}else{
		$db->rollBack();
		echo '{"ok":"0"}';
	}
	// print2($dimsMarcas);







?>
