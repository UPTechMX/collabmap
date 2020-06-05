<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$padre = empty($_POST['padre']) ? 0 : $_POST['padre'];
$nivelMax = empty($_POST['nivelMax']) ? 0 : $_POST['nivelMax'];

$LJ = getLJTrgt($nivelMax,$padre,$_POST['elemId'],'documents');
// print2($LJ);

$lastLevel = intval($LJ['numDim'])-1;
// echo "AAAAAA: $lastLevel";

$sql = "SELECT te.* $LJ[fields], de$nivelMax.nombre as lastName
	FROM DocumentsComments te
	$LJ[LJ]
	WHERE documentsId = $_POST[elemId] AND $LJ[wDE]
	ORDER BY te.timestamp";

// print2($sql);
$comments = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: text/html; charset=utf-8'); 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".TR('comments').".csv");

$csv .= '"'.TR("date").'","'.TR("docLevel").'","'.TR("lastLevel").'","'.TR("comment").'"'."\n";

foreach ($comments as $c) {
	$csv .= '"'.$c['timestamp'].'",';
	$csv .= '"'.$c['nombreHijo'].'",';
	$csv .= '"'.$c['lastName'].'",';
	$csv .= '"'.$c['comment'].'"';

	$csv .= "\n";
	# code...
}
echo $csv;
// print2($csv);
// print2($comments);

