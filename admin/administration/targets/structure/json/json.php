<?php  

include_once '../../../../../lib/j/j.func.php';

// print2($_POST);

switch ($_POST['opt']) {
	case 1:
		$post['tabla'] = 'AreasEquipos';
		$post['usrAdminId'] = '1';
		break;
	case 2:
		$post['tabla'] = 'Marcas';
		break;
	case 3:
		$post['tabla'] = 'Usuarios';
		if(!empty($_POST['datos']['pwd'])){
			$_POST['datos']['pwd'] = encriptaUsr($_POST['datos']['pwd']);
		}else{
			unset($_POST['datos']['pwd']);
		}
		break;
	case 4:
		$post['tabla'] = 'Dimensiones';
		break;
	case 5:
		$post['tabla'] = 'DimensionesElem';
		break;
	case 6:
		$post['tabla'] = 'Tiendas';
		break;
	case 7:
		$post['tabla'] = 'UsuariosProyectos';
		break;
	case 8:
		$post['tabla'] = 'Directorio';
		break;
	case 9:
		$post['tabla'] = 'ProyectosArchivos';
		break;
	default:
		break;
}


switch ($_POST['acc']) {
	case '1':
	
		$post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(inserta($post));
		break;
	case '2':
		$post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(upd($post));
		if ($_POST['opt'] == 5) {
			if (!empty($_POST['kmlFile'])) {
				// code...
			}
		}
		break;	
	case '3':
		// print2($_POST);
		$post['tabla'] = 'UsuariosProyectos';
		$post['where'] = "usuariosId = $_POST[usuarioId] AND proyectosId = $_POST[proyectoId]";
		// $post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(del($post));
		break;	
	case '4':
		// print2($_POST);
		$post['tabla'] = 'Usuarios';
		$post['where'] = "id = $_POST[usuarioId]";;

		echo atj(del($post));
		break;
	case 5:
		try {		
			$db->query("DELETE FROM Marcas WHERE id = $_POST[mId]");
			echo '{"ok":"1"}';
		} catch (Exception $e) {
			echo '{"ok":"0","err":"Error al eliminar la marca Err:DM757"}';
		}

		break;
	case 6:
		// print2($_POST);
		try {		
			$db->query("DELETE FROM DimensionesElem WHERE id = $_POST[elemId]");
			echo '{"ok":"1"}';
		} catch (Exception $e) {
			echo '{"ok":"0","err":"Error al eliminar la marca Err:DM757"}';
		}

		break;
	case 7:
		// print2($_POST);
		$post['tabla'] = 'Directorio';
		$post['where'] = "id = $_POST[personaId]";;

		echo atj(del($post));
		break;
	case 8:
		$multimedia = $db->query("SELECT * FROM ProyectosArchivos WHERE id = $_POST[fId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		$db->exec("DELETE FROM ProyectosArchivos WHERE id = $_POST[fId]");
		@unlink(raiz()."admin/administration/targets/structure/archivos/$multimedia[archivo]");
		// print2($multimedia);
		echo '{"ok":"1"}';
		break;
	case 9:
		$kmlId = $_POST['datos']['kmlId'];
		// print2("DELETE FROM KML WHERE id = $kmlId");

		$db->exec("DELETE FROM KML WHERE id = $kmlId");
		// @unlink(raiz()."admin/administration/targets/structure/archivos/$multimedia[archivo]");
		// print2($multimedia);
		echo '{"ok":"1"}';
		break;


	default:
		# code...
		break;
}









?>