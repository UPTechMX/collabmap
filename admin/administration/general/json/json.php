<?php  
	include_once '../../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Consultations

	
	switch ($_POST['acc']) {
		case 1:

			$elems = ['about','privacy'];

			if(!in_array($_POST['elem'], $elems)){
				exit('{"ok":0}');
			}

			$stmt = $db->prepare("REPLACE INTO General SET name = :name, texto = :texto ");
			$arr['name'] = $_POST['elem'];
			$arr['texto'] = $_POST['texto'];
			$stmt -> execute($arr);
			echo '{"ok":1}';
			break;
		case 2:
			$post['tabla'] = '';
			break;
		case 3:
			$post['tabla'] = '';
			break;
		default:
			# code...
			break;
	}
?>