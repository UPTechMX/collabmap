<?php

	include_once '../j/j.func.php';

	// $_POST['clienteId'] = isset($_POST['clienteId'])?$_POST['clienteId']:8;

	// print2($_POST);

	$insArr = array();

	$row = 0;

	$clienteId = $_POST['clienteId'];

	$buscMarca = $db->prepare("SELECT id FROM Marcas WHERE nombre = ?");
	$buscTienda = $db->prepare("SELECT id FROM Tiendas WHERE marcasId = ? AND POS = ?");
	$updTienda = $db->prepare("UPDATE Tiendas SET nombre = ?, calle = ?,colonia = ?,cp = ?, estado = ?,municipio = ? WHERE id = ?");
	$buscEstado = $db->prepare("SELECT id FROM Estados WHERE nombre LIKE ?");
	$buscMunic = $db->prepare("SELECT id FROM Municipios WHERE nombre LIKE ? AND estadosId = ?");
	$buscMunicSEDO = $db->prepare("SELECT id, estadosId FROM Municipios WHERE nombre LIKE ?");
	if (($handle = fopen(raiz()."lib/archivos/$_POST[archivoDir]", "r")) !== FALSE) {
		while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {

			if($row == 0){
				// print2($col);
			}else{

				$buscMarca->execute(array($col[1]));
				$mId = $buscMarca->fetchAll(PDO::FETCH_NUM)[0][0];
				if(empty($mId))
					continue;

				$buscTienda -> execute(array($mId,$col[0]));
				$tId = $buscTienda->fetchAll(PDO::FETCH_NUM)[0][0];
				if(empty($tId))
					continue;

				$edoBusc = "%".trim($col[6])."%";
				$buscEstado -> execute(array($edoBusc));
				$edoId = $buscEstado->fetchAll(PDO::FETCH_NUM)[0][0];

				$muncBusc = "%".trim($col[5])."%";

				// if(empty($edoId)){
				// 	echo "NO --- $col[6]------";
				// }

				if(empty($edoId)){
					$buscMunicSEDO -> execute(array($muncBusc));
					$dat = $buscMunicSEDO->fetchAll(PDO::FETCH_NUM)[0];
					$muncId = $dat[0];
					$edoId = $dat[1];
					// echo "$edoId =-=-=- $col[5]<br/>";
				}else{
					$buscMunic -> execute(array($muncBusc,$edoId));
					$dat = $buscMunic->fetchAll(PDO::FETCH_NUM)[0];
					$muncId = $dat[0];
				}

				$updTienda -> execute(array($col[2],$col[3],$col[4],$col[7],$edoId,$muncId,$tId));
				// echo "$col[6]----- $edoId<br/>";


				// echo $edoId.'<br/>';
				// echo trim($col[6]).'<br/>';



				// echo $tId."<br/>";


				// if($row == 1)
				// 	break;

			}


			$row++;
		}

		fclose($handle);
		
	}
	// print2($dimsMarcas);







?>

