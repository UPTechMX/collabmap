<?php  
	include_once '../../../../lib/j/j.func.php';



	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Vehiculos';
			break;
		case 2:
			$post['tabla'] = 'ProyectosFondeadores';
			break;

		
		default:
			# code...
			break;
	}

	switch ($_POST['acc']) {
		case 1:
			$post['datos'] = $_POST['datos'];
			// print2($post);
			echo atj(inserta($post));
			break;
		case 2:
			$post['datos'] = $_POST['datos'];
			// print2($post);
			echo atj(upd($post));
			break;
		case 3:
			$post['where'] = "id = $_POST[elemId]";
			// print2($post);
			echo atj(del($post));
			break;
		case 4:
			$p['tabla'] = 'VehiculosMult';
			$datos['vehiculosId'] = $_POST['vId'];
			// $datos['nombre'] = $_POST['dat']['nombreArchivo'];
			$datos['archivo'] = $_POST['dat']['prefijo'].$_POST['dat']['nombreArchivo'];
			$p['datos'] = $datos;
			// print2($p);
			echo atj(inserta($p));
			break;
		case 5:
			$multimedia = $db->query("SELECT * FROM VehiculosMult WHERE id = $_POST[mId]")->fetchAll(PDO::FETCH_ASSOC)[0];
			$db->exec("DELETE FROM VehiculosMult WHERE id = $_POST[mId]");
			unlink(raiz()."admin/administracion/vehiculos/img/$multimedia[archivo]");
			// print2($multimedia);
			echo '{"ok":"1"}';
			break;



		default:
			# code...
			break;
	}


?>