
<?php  

	// $_POST['mensaje'] = 'hola [nombre]:<br><br>bla bla bla';
	include_once '../../lib/j/j.func.php';

	// print2($_POST);
	$_POST['filtros'] = is_array($_POST['filtros'])?$_POST['filtros']:array();
	$filtro = genereaSQLFiltros($_POST['filtros']);

	// print2($filtro,null);

	$sqlAll = $sql = "SELECT s.email,
			CONCAT(s.nombre,' ', s.aPat,' ', s.aMat) as nombre
			FROM Shoppers s
			$filtro[joins]
			WHERE 
			(s.username != '' AND s.username IS NOT NULL AND s.estatus >= 10) $filtro[where]
			GROUP BY s.id";

	// echo "\n\n\n";
	// print2($sqlAll);

	// echo "\n\n\n";

	// print2($filtro['arreglo']);
	// echo "\n\n\n";
	try {
		$stm = $db->prepare("SELECT * FROM ($sql) as busq ");
		$stm->execute( $filtro['arreglo'] );
		$shoppers = $stm -> fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		// print2($e->getMessage());
	}

	$cuerpo = file_get_contents(raiz().'shopper/registro/header.php');

	$cuerpo .= $_POST['mensaje'];

	$cuerpo .= file_get_contents(raiz().'shopper/registro/footer.php');

	// print2($pryCtas);
	$from = "Shoppers Consulting <no-reply@sistema.shoppersconsulting.com>";

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From:" . $from . "\r\n";
	$headers .= "Bcc:" . $from . "\r\n";

	$subject = $_POST['asunto'];


	foreach ($shoppers as $s) {
		$nombre = $s['nombre'];
		$message = str_replace('[nombre]', $nombre, $cuerpo);
		$to = $s['email'];
		// echo $message;
		mail($to,$subject,$message,$headers);

	}

	$shopJ = atj($shoppers);
	echo '{"ok":"1","shoppers":'.$shopJ.'}';
	// print2($shoppers);


?>