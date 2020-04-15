<?php

	include_once '../j/j.func.php';

	// $_POST['clienteId'] = isset($_POST['clienteId'])?$_POST['clienteId']:8;

	// print2($_POST);

	// $ok = true;
	$insArr = array();

	$row = 0;

	$clienteId = is_numeric($_POST['clienteId'])?$_POST['clienteId']:0;
	$proyectoId = is_numeric($_POST['proyectoId'])?$_POST['proyectoId']:0;

	$insMarca = $db->prepare("INSERT INTO Marcas SET clientesId = $clienteId, nombre = ?, siglas = ?");
	$updMarca = $db->prepare("UPDATE Marcas SET siglas = ? WHERE id = ?");
	$buscMarca = $db->prepare("SELECT id FROM Marcas WHERE nombre = ? AND clientesId = ?");
	$insDims = $db->prepare("INSERT INTO Dimensiones SET clientesId = ?, nombre = ?,nivel = ?");
	$buscElemDim = $db->prepare("SELECT id FROM DimensionesElem WHERE nombre = ? AND dimensionesId = ?");
	$insDimElem = $db->prepare("INSERT INTO DimensionesElem SET  nombre = ?, nombrePub=?, dimensionesId = ?, padre = ?");
	$updDimElem = $db->prepare("UPDATE DimensionesElem SET nombrePub = ? WHERE id = ?");
	$buscUsr = $db->prepare("SELECT id FROM Usuarios WHERE username = ? AND clientesId = ?");
	$insUsr = $db->prepare("INSERT INTO Usuarios SET username = ?, nombre = ?, pwd = ?,clientesId = ?");
	$buscTienda = $db->prepare("SELECT t.id 
		FROM Tiendas t
		LEFT JOIN Marcas m ON m.id = t.marcasId
		WHERE m.clientesId = $clienteId AND POS = ?");
	$insTienda = $db->prepare("INSERT INTO Tiendas 
		SET POS = ?, nombre = ?, tipo = ?,dimensionesElemId = ?,marcasId = ?, 
		dom = ?, lun = ?, mar = ?, mie = ?, jue = ?, vie = ?, sab = ?");
	$delUsrPry = $db->prepare("DELETE FROM UsuariosProyectos WHERE usuariosId = ? AND proyectosId = ?");
	$upInsert = $db->prepare("INSERT INTO UsuariosProyectos SET usuariosId = ?, proyectosId = ?,dimensionesElemId = ?");
	$updTienda = $db->prepare("UPDATE Tiendas 
		SET dimensionesElemId = ?, dom = ?, lun = ?, mar = ?, mie = ?, jue = ?, vie = ?, sab = ?, nombre = ?, marcasId = ? 
		WHERE id = ?");

	// if (($handle = fopen(raiz()."lib/archivos/9_PDV.csv", "r")) !== FALSE) {
	$ii = 0;
	if (($handle = fopen(raiz()."lib/archivos/$_POST[archivoPOS]", "r")) !== FALSE) {
		while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {
					// echo $ii++." \n";

			if($row == 0){
				// print2($col);
				$numColsAntesDims = 12;
				$numDims = (count($col)-$numColsAntesDims)/2;
				$dims = array();
				$j = 0;
				for($i = 0;$i<$numDims*2;$i=$i+2){
					$dims[$j++] = $col[$i+$numColsAntesDims];
					// $j = $j+2;
				}
				// print2($dims);
				// break;
			}else{
				
				///// Inserta marca

				if(!isset($marcasId[$col[3]])){
					$buscMarca->execute(array($col[3],$clienteId));
					$result = $buscMarca->fetchAll(PDO::FETCH_NUM)[0][0];
					if(!empty($result)){
						$marcasId[$col[3]] = $result;
						$updMarca -> execute(array($col[4],$result));
					}else{
						$insMarca -> execute(array($col[3],$col[4]));
						$marcasId[$col[3]] = $db->lastInsertId();
					}
				}

				$mId = $marcasId[$col[3]];

				$buscaDims = $db->query("SELECT id FROM Dimensiones WHERE clientesId = $clienteId ORDER BY nivel") -> fetchAll(PDO::FETCH_NUM);
				if(count($buscaDims) > 0){
					if(count($buscaDims) == $numDims){
						// print2($buscaDims);
						foreach ($buscaDims as $k => $d) {
							$dimsCliente[$k] = $d[0];
						}
					}else{
						$ok = false;
						$cuenta = count($buscaDims);
						$err = "El cliente ya tiene dimensiones en el sistema ".
							"y no coinciden con las del archivo. En el sistema existen $cuenta dimensiones";
						break;
					}
				}else{
					foreach ($dims as $k => $dim) {
						$insDims -> execute(array($clienteId,$dim,$k+1));
						$dimsCliente[$k] = $db->lastInsertId();
					}
				}
				
				/// busca eleDims

				// print2($dimsCliente);
				// $dimsCliente["eles"] = 2;
				for($i = 0;$i<$numDims;$i++){
					$nombre = $col[$numColsAntesDims+2*$i+1];
					$nombrePub = $col[$numColsAntesDims+2*$i];

					// echo "Nombre: $nombre - NombrePub: $nombrePub \n";

					// continue;

					if($i>0){
						$ele[$i] = $ele[$i-1].'["'.$nombre.'"]';
					}else{
						$ele[$i] = '$dimsCliente["eles"]["'.$nombre.'"]';
					}
					$buscUsr->execute(array($nombrePub,$clienteId));
					$result = $buscUsr->fetchAll(PDO::FETCH_NUM)[0][0];
					if(!empty($result)){
						$usrId = $result;
					}else{
						$nomExp = explode(' ',$nombrePub);
						$pwd = '';
						$numInic = count($nomExp);

						for($j = ($numInic -1);$j>=0;$j--){
							$pwd .= substr($nomExp[$j], 1,1);
						}
						$pwd = encriptaUsr($pwd.'2017');
						$insUsr -> execute(array($nombrePub,$nombrePub,$pwd,$clienteId));
						$usrId = $db->lastInsertId();
					}
					// echo $ele[$i]."\n";
					// echo '$a = isset('.$ele[$i].");\n";

					eval('$a = isset('.$ele[$i].");");



					if(!$a){
						$buscElemDim->execute(array($nombre,$dimsCliente[$i]));
						$eleDimId = $buscElemDim->fetchAll(PDO::FETCH_NUM)[0][0];
						
						// echo $dimsCliente[$i]."./  $nombre !!-=-=-=-=-=";
						if(!empty($eleDimId)){
							$updDimElem->execute(array($nombrePub,$eleDimId));
							eval($ele[$i].'["id"] = $eleDimId;');
						}else{
							if($i != 0){
								eval('$padreId = '.$ele[$i-1].'["id"];');
							}else{
								$padreId = 0;
							}
							$insDimElem->execute(array($nombre,$nombrePub,$dimsCliente[$i],$padreId));
							$id = $db->lastInsertId();
							eval($ele[$i].'["id"] = $id;');
							$eleDimId = $id;

						}
					}else{
						eval('$eleDimId = '.$ele[$i].'["id"];');
					}

					if($usrId == 1){
						// echo $ele[$i]."\n";
						// print2($dimsCliente['eles']);
						// echo "\n";
					}

					$delUsrPry -> execute(array($usrId,$proyectoId));
					$upInsert->execute(array($usrId,$proyectoId,$eleDimId));

				}
				// continue;

				$buscTienda -> execute(array($col[0]));
				$result = $buscTienda->fetchAll(PDO::FETCH_NUM)[0][0];

				if(!empty($result)){
					$updTienda -> execute(array($eleDimId,$col[5],$col[6],$col[7],
						$col[8],$col[9],$col[10],$col[11],$col[1],$mId,$result));
				}else{
					$insTienda->execute(array($col[0],$col[1],$col[2],$eleDimId,$mId,
						$col[5],$col[6],$col[7],$col[8],$col[9],$col[10],$col[11]));
				}
			}
			$row++;
		}

		fclose($handle);
		
	}
	// print2($dimsCliente);







?>

