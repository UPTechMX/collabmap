<?php  

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCache.php';
$tiempoIni = microtime(true); 


// print2($_POST);
switch ($_POST['get']) {
	case 'chks':
		$params['camposInt'] = "chk.id as chkId, chk.nombre as chkNom, rep.id as rId, v.Id as vId";
		$params['grpInt'] = "GROUP BY chkId";
		$params['camposExt'] = "chkId as val,chkNom as nom, rId as clase";
		// $params['grpExt'] = "GROUP BY repId";
		// $params['where'] = " AND total != '-' ";
		$params['JOINS'] = "LEFT JOIN Checklist chk ON chk.marcasId = m.id AND rep.id = chk.repeticionesId";
		// $params['orderInt'] = "ORDER BY rep.fechaIni";
		$params['orderExt'] = "ORDER BY chkNom";
		break;
	default:
		# code...
		break;
}
	$datos = promTotComp($_POST['elem'],$_POST['reps'],$_POST['proyectoId'],$params);

	echo atj($datos);

?>