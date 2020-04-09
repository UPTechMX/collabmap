<?php

include_once '../../../lib/j/j.func.php';
session_start();
$uId = $_SESSION['CM']['admin']['usrId'];
$nivel = $_SESSION['CM']['admin']['nivel'];

$visita = $db->query("SELECT v.*, c.token as cToken
	FROM Visitas v
	LEFT JOIN Clientes c ON c.id = v.clientesId
	WHERE v.clientesId = $_POST[cteId] AND v.etapa = 'instalacion' AND v.finalizada = 1
	ORDER BY v.timestamp DESC LIMIT 1
")->fetchAll(PDO::FETCH_ASSOC)[0];


$archivos = $db->query("SELECT * FROM Multimedia WHERE visitasId = $visita[id]")->fetchAll(PDO::FETCH_ASSOC);

// print2($archivos);

$zip_file = $rz."admin/descargas/archivos/zipFiles/$visita[cToken]_Reporte_fotogafico_instalacion_$visita[id].zip";

// print2($zip_file);



$zip = new ZipArchive();
if ( $zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
	exit("Error");
}

$i = 0;
foreach ($archivos as $a) {

	// echo $rz."campo/archivosCuest/$a[archivo]<br/>";
	$zip->addFile($rz."campo/archivosCuest/$a[archivo]", $a['archivo']);

}

$zip->close();


// header("Content-type: application/zip"); 
// header("Pragma: no-cache"); 
// header("Expires: 0"); 
// readfile("$zip_file");
// exit;

header('Content-type: application/zip');
header('Content-Disposition: attachment; filename="'.basename($zip_file).'"');
header("Content-length: " . filesize($zip_file));
header("Pragma: no-cache");
header("Expires: 0");

ob_clean();
	flush();

readfile($zip_file);
unlink($zip_file);
exit;

?>