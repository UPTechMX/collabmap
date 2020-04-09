<?php

 	include_once '../j/j.func.php';


 	$updVis = $db->prepare("UPDATE Visitas SET resumen = ? WHERE id = ?");


	$db->beginTransaction();
	try {
		$ok = true;
		$row = 0;
		// echo raiz()."lib/archivos/$_POST[archChk] \n";
		if (($handle = fopen(raiz()."lib/archivos/resumenes.csv", "r")) !== FALSE) {
			while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {
				if($row == 0){
					$row++;
					continue;
				}

				// print2($col);
				switch ($col[0]) {
					case 'C1':
						$repId = 17;
						break;
					case 'C2':
						$repId = 18;
						break;
					case 'C3':
						$repId = 19;
						break;
					
					default:
						# code...
						break;
				}

				if($col[3] == ''){
					continue;
				}

				$sql = "SELECT v.id FROM Visitas v
					LEFT JOIN Rotaciones r ON  r.id = v.rotacionesId
					LEFT JOIN Tiendas t ON t.id = r.tiendasId
					WHERE r.repeticionesId =  $repId AND t.POS = $col[3]";

				// echo "$sql<br/>";

				$vInf = $db-> query($sql)->fetchAll(PDO::FETCH_ASSOC);
				if(!isset($vInf[0]['id'])){
					echo $col[0]." $col[3]<br/>";
					// print2($vInf);
					continue;
				}
				// break;
				$updVis->execute(array($col[4],$vInf[0]['id']));


				$row++;
			}
		}
		if($ok){
			$db->commit();
			echo '{"ok":"1"}';
		}else{
			echo '{"ok":"0","err":"'.$err.'"}';
			$db->rollBack();
		}
		
	} catch (PDOException $e) {
		$db->rollBack();
		echo '{"ok":"0","err":"'.$e->getMessage().' '.$e->getLine().'"}';

	}



?>