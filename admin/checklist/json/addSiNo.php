<?php  

include_once '../../../lib/j/j.func.php';
checaAcceso(50); // checaAcceso Checklist

// print2($_POST);

$p = array();
$p['tabla'] = 'Respuestas';
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

			$p['datos']['respuesta'] = 'No sé';
			$p['datos']['valor'] = 2;
			$p['datos']['orden'] = 2;
			inserta($p);
			
			echo '{"ok":"1"}';

			break;
		case 3:
			$p['datos']['respuesta'] = 'Se compromete';
			$p['datos']['valor'] = 0;
			$p['datos']['orden'] = 0;
			inserta($p);

			$p['datos']['respuesta'] = 'Realizado';
			$p['datos']['valor'] = 1;
			$p['datos']['orden'] = 1;
			inserta($p);

			$p['datos']['respuesta'] = 'NA';
			$p['datos']['valor'] = '-';
			$p['datos']['orden'] = 2;
			inserta($p);
			
			echo '{"ok":"1"}';

			break;
		case 4:
			$p['datos']['respuesta'] = 'Muy buena';
			$p['datos']['valor'] = 1;
			$p['datos']['orden'] = 1;
			inserta($p);

			$p['datos']['respuesta'] = 'Buena';
			$p['datos']['valor'] = 2;
			$p['datos']['orden'] = 2;
			inserta($p);

			$p['datos']['respuesta'] = 'Regular';
			$p['datos']['valor'] = 3;
			$p['datos']['orden'] = 3;
			inserta($p);

			$p['datos']['respuesta'] = 'Mala';
			$p['datos']['valor'] = 4;
			$p['datos']['orden'] = 4;
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