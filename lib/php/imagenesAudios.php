<?php  

	set_time_limit(6000);
	include_once '../j/j.func.php';
	include_once raiz().'lib/php/calcCache.php';

	$findMedia = $db->prepare("SELECT COUNT(*) FROM Multimedia WHERE visitasId = ? AND tipo = ? AND archivo = ?");
	$insMedia = $db->prepare("INSERT INTO Multimedia SET visitasId = ?, tipo = ?, nombre = ?, archivo = ?");

	$repeticiones = $db->query("SELECT * FROM Repeticiones WHERE proyectosId = 8 AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_ASSOC);
	// Campaña 1;
	foreach ($repeticiones as $k => $r) {
		$c = $k+1;
		// print2($r);

		$reps = [$r['id']];
		$params['camposInt'] = "v.Id as vId, t.POS";
		$params['grpInt'] = "GROUP BY vId";
		$params['camposExt'] = "*";
		$params['where'] = "";
		$params['orderExt'] = "ORDER BY vId";
		// echo "0,$reps,$pId,$params";
		$visitas = promTotComp(0,$reps,8,$params);

		foreach ($visitas as $v) {
			$vId = $v['vId'];
			$POS = $v['POS'];
			/// Busca IMG
			$img = glob(raiz()."admin/tmpFiles/[Cc]".$c."[a-zA-Z0-9 ]*$POS.[jJ][pP][gG]");
			// print2($img);
			if(isset($img[0])){
				// print2($img);
				$nomArch = end(explode('/', $img[0]));
				$archivo = $vId."_".$nomArch;

				// echo "$nomArch<br/>";
				$findMedia->execute(array($vId,'img',$archivo));
				$numRows = $findMedia->fetchAll(PDO::FETCH_NUM);
				// print2($numRows);
				if( $numRows[0][0] == 0 ){
					$insMedia->execute(array($vId,'img',$nomArch,$archivo));
					copy(raiz()."admin/tmpFiles/$nomArch",raiz()."archivos/$archivo");
				}
			}

			/// Busca MP3
			$mp3 = glob(raiz()."admin/tmpFiles/[Cc]".$c."[a-zA-Z0-9 ]*$POS.[mM][pP]3");
			// print2($mp3);
			if(isset($mp3[0])){
				$nomArch = end(explode('/', $mp3[0]));
				$archivo = $vId."_".$nomArch;

				// echo "$nomArch<br/>";
				$findMedia->execute(array($vId,'audio',$archivo));
				$numRows = $findMedia->fetchAll(PDO::FETCH_NUM);
				// print2($numRows);
				if( $numRows[0][0] == 0 ){
					$insMedia->execute(array($vId,'audio',$nomArch,$archivo));
					copy(raiz()."admin/tmpFiles/$nomArch",raiz()."archivos/$archivo");
				}
			}
		}
	}
	echo "Listo!";


?>

