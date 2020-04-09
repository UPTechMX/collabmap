<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(50);


	// print2($_POST);

	switch ($_POST['opt']) {
		case 1:
			$post['tabla'] = 'Proyectos';
			break;
		case 2:
			$post['tabla'] = 'ProyectosFondeadores';
			break;
		case 3:
			$post['tabla'] = 'Instalaciones';
			// print2($_POST);
			break;
		case 4:
			$post['tabla'] = 'ProyectosColonias';
			// print2($_POST);
			// $_POST['datos']['colonia'] = trim($_POST['datos']['colonia']);
			break;
		case 5:
			$post['tabla'] = 'ProyectosUbicaciones';
			// print2($_POST);
			// $_POST['datos']['colonia'] = trim($_POST['datos']['colonia']);
			break;
		case 6:
			$post['tabla'] = 'InstalacionesEquipos';
			// print2($_POST);
			// $_POST['datos']['colonia'] = trim($_POST['datos']['colonia']);
			break;
		case 7:
			$post['tabla'] = 'ProyectosChecklist';
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
			// print2($_POST);
			// print2($post);
			echo atj(upd($post));
			break;
		case 3:
			$post['where'] = "id = $_POST[elemId]";
			// print2($post);
			echo atj(del($post));
			break;
		case 4:
			// print2($_POST);
			$db->beginTransaction();
			$db->query("DELETE FROM ProyectosChecklist WHERE proyectosId = $_POST[pryId]");
			$ok = true;
			$_POST['datos'] = is_array($_POST['datos'])?$_POST['datos']:array();
			foreach ($_POST['datos'] as $d) {
				$post['datos']['proyectosId'] = $_POST['pryId'];
				$post['datos']['checklistId'] = $d;
				// print2($post);
				$rj = atj(inserta($post));
				$r = json_decode($rj,true);
				if($r['ok'] != 1){
					$ok = false;
					$err = $r['err'];
					// echo $rj;
					break;
				}
			}
			// echo "AAA";
			if($ok){
				$db->commit();
				echo '{"ok":"1"}';
			}else{
				$db->rollBack();
				echo '{"ok":"0","err":"Error a guardar los cuestionarios en el proyecto. Err:PC023","errF":"'.$err.'"}';
			}
			break;


		default:
			# code...
			break;
	}


?>