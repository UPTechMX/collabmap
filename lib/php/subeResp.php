<?php

	include_once '../j/j.func.php';

	$_POST['repId'] = isset($_POST['repId'])?$_POST['repId']:12;

	// print2($_POST);

	$insArr = array();

	$row = 0;
	$repId = $_POST['repId'];

	$buscMarca = $db->prepare("SELECT id FROM Marcas WHERE nombre = ?");
	$buscTienda = $db->prepare("SELECT id FROM Tiendas WHERE marcasId = ? AND POS = ?");
	$updTienda = $db->prepare("UPDATE Tiendas SET nombre = ?, calle = ?,colonia = ?,cp = ?, estado = ?,municipio = ? WHERE id = ?");
	$buscEstado = $db->prepare("SELECT id FROM Estados WHERE nombre LIKE ?");
	$buscMunic = $db->prepare("SELECT id FROM Municipios WHERE nombre LIKE ? AND estadosId = ?");
	$buscMunicSEDO = $db->prepare("SELECT id, estadosId FROM Municipios WHERE nombre LIKE ?");
	if (($handle = fopen(raiz()."lib/archivos/randResp.csv", "r")) !== FALSE) {
		while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {

			if($row == 0){
				$dat = $db->query("SELECT c.id,r.fechaIni FROM Repeticiones r 
					LEFT JOIN Proyectos p ON r.proyectosId = p.id
					LEFT JOIN Clientes c ON p.clientesId = c.id
					WHERE r.id = $repId
					")->fetch(PDO::FETCH_NUM);
				$clienteId = $dat[0];
				$fIni = $dat[1];
				// print2($clienteId);
				// print2($fIni);
				// print2($col);
			}else{
				

			}
			$row++;
		}

		fclose($handle);
		
	}
	// print2($dimsMarcas);







?>

