<?php  

include_once '../../../lib/j/j.func.php';

// print2($_POST);

$p = array();
$p['tabla'] = 'RespuestasExt';
$p['datos']['preguntasId'] = $_POST['pregId'];

try {
	switch ($_POST['opt']) {
		case '1':
			$p['datos']['respuesta'] = 'Sí';
			$p['datos']['valor'] = 1;
			$p['datos']['orden'] = 0;
			inserta($p);

			$p['datos']['respuesta'] = 'No';
			$p['datos']['valor'] = 0;
			$p['datos']['orden'] = 1;
			inserta($p);

			$p['datos']['respuesta'] = 'NA';
			$p['datos']['valor'] = '-';
			$p['datos']['orden'] = 2;
			inserta($p);
			
			echo '{"ok":"1"}';

			break;
		case 2:
			for($i = 0;$i<=10;$i++){
				$p['datos']['respuesta'] = $i;
				$p['datos']['valor'] = $i;
				$p['datos']['orden'] = $i+1;
				inserta($p);
			}
			$p['datos']['respuesta'] = 'NA';
			$p['datos']['valor'] = '-';
			$p['datos']['orden'] = $i+2;
			inserta($p);

			echo '{"ok":"1"}';

			break;
		default:
			# code...
			break;
	}

} catch (PDOException $e) {
	
	echo '{"ok":"0","err":"'.$e->getMessage().'"}';
}



?>