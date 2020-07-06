<?php

	include_once '../../../../../lib/j/j.func.php';
	
	checaAcceso(60);// checaAcceso Targets

	// $_POST['clienteId'] = isset($_POST['clienteId'])?$_POST['clienteId']:8;

	// print2($_POST);
	if(!is_numeric($_POST['targetsId'])){
		exit();
	}

	$db->beginTransaction();

	$targetsId =  $_POST['targetsId'];
	// $ok = true;
	$insArr = array();

	$row = 0;

	$insDims = $db->prepare("INSERT INTO Dimensiones SET elemId = ?, nombre = ?,nivel = ?, type='structure'");
	$buscElemDim = $db->prepare("SELECT id FROM DimensionesElem WHERE nombre = ? AND dimensionesId = ? AND padre = ?");
	$insDimElem = $db->prepare("INSERT INTO DimensionesElem SET  nombre = ?, dimensionesId = ?, padre = ?");
	
	// if (($handle = fopen(raiz()."lib/archivos/9_PDV.csv", "r")) !== FALSE) {
	$ok = true;
	$dimsElems = array();
	if (($handle = fopen(raiz()."externalFiles/$_POST[file]", "r")) !== FALSE) {
		while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {

			if($row == 0){
				// print2($col);
				
				$numDims = count($col);
				$dims = array();
				$j = 0;
				for($i = 0;$i<$numDims;$i++){
					$dims[$j++] = $col[$i];
				}
				// print2($numDims);

				$buscaDims = $db->query("SELECT id FROM Dimensiones 
					WHERE elemId = $targetsId AND type = 'structure'
					ORDER BY nivel") -> fetchAll(PDO::FETCH_NUM);

				if(count($buscaDims) > 0){
					if(count($buscaDims) == $numDims){
						// print2($buscaDims);
						foreach ($buscaDims as $k => $d) {
							$dimsTarget[$k] = $d[0];
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
						$insDims -> execute(array($targetsId,$dim,$k+1));
						$dimsTarget[$k] = $db->lastInsertId();
					}
				}
				// print2($dimsTarget);
			}else{


				for($i = 0;$i<$numDims;$i++){
					try{
						if(empty($col[$i])){
							break;
						}
						$padre = $i == 0?0:$ids[$i-1];
						if( !isset($dimsElems[$padre.'-'.$col[$i]]) ){
							// echo "padre: $padre\n";
							$buscElemDim -> execute([$col[$i],$dimsTarget[$i],$padre]);

							// print2([$col[$i],$dimsTarget[$i],$padre]);
							$elemDim = $buscElemDim -> fetchAll(PDO::FETCH_NUM);
							if(!empty($elemDim)){
								$dimsElems[$padre.'-'.$col[$i]] = $elemDim[0][0];
								$ids[$i] = $dimsElems[$padre.'-'.$col[$i]];
							}else{
								// $insDimElem = $db->prepare("INSERT INTO DimensionesElem SET  nombre = ?, dimensionesId = ?, padre = ?");
								$insDimElem -> execute([ $col[$i],$dimsTarget[$i], $padre]);
								// echo "padre: $padre, nombre:".$col[$i].", col:$i   \n";
								$dimsElems[$padre.'-'.$col[$i]] = $db->lastInsertId();
								$ids[$i] = $dimsElems[$padre.'-'.$col[$i]];
							}
						}

					}
					catch(PDOException $e){
						exit('{"ok":0,"err":"'.$e->getMessage().'"}');
					}
				}
				// continue;

			}
			$row++;
		}

		fclose($handle);
		
	}
	// print2($dimsTarget);



	if($ok){
		$db->commit();
		echo '{"ok":"1"}';
	}else{
		$db->rollback();
		echo '{"ok":"0","err":"'.$err.'"}';
	}



?>

