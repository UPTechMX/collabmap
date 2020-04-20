<?php
	

	include_once '../../lib/j/j.func.php';
	checaAcceso(60);// checaAcceso Usuarios

	$dims = $db->query("SELECT * FROM Dimensiones WHERE type ='structure' AND elemId = $_POST[targetsId] ")->fetchAll(PDO::FETCH_ASSOC);

	if(count($dims) == 0){
		$path = raiz().'admin/examples/structure.csv';
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.basename($path).'"');
		header("Content-length: " . filesize($path));
		header("Pragma: no-cache");
		header("Expires: 0");

		ob_clean();
			flush();

		readfile($path);
		// unlink($zip_file);
		exit;

	}else{
		$target = $db->query("SELECT t.name as tName, p.name as pName
			FROM Targets t
			LEFT JOIN Projects p ON p.id = t.projectsId
			WHERE t.id = $_POST[targetsId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		// print2($target);
		header('Content-Type: text/html; charset=utf-8'); 
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$target[pName] - $target[tName] - structure.csv");
	}

	$csv = "";
	// print2($dims);
	foreach ($dims as $k => $d)  {
		if($k == 0){
			$csv .= '"'.$d['nombre'].'"';
		}else{
			$csv .= ', "'.$d['nombre'].'"';
		}
	}
	$csv .= "\n";

	echo $csv;


?>