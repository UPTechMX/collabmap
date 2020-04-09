<?php  

include_once '../../../lib/j/j.func.php';

// print2($_POST);

switch ($_POST['opt']) {
	case 1:
		$post['tabla'] = 'Proyectos';
		$fields = 'id as val, CONCAT(nombre," (",siglas,")") as nom, "clase" as clase';
		$campo = 'clientesId';
		break;
	case 2:
		$post['tabla'] = 'Clientes';
		break;
	case 3:
		$post['tabla'] = 'Repeticiones';
		$fields = 'id as val, CONCAT(nombre) as nom, "clase" as clase';
		$campo = 'proyectosId';
		$elim = 'AND (elim != 1 OR elim IS NULL)';
		$order = 'ORDER BY fechaIni DESC';

		break;
	case 4:
		$post['tabla'] = 'ChecklistExt';
		$fields = 'id as val, CONCAT(nombre," (",siglas,")") as nom, "clase" as clase';
		$campo = 'repeticionesId';
		$elim = 'AND (elim != 1 OR elim IS NULL)';
		break;
	case 5:
		$post['tabla'] = 'BloquesExt';
		break;
	case 6:
		$post['tabla'] = 'AreasExt';
		break;
	case 7:
		$post['tabla'] = 'PreguntasExt';
		break;
	case 8:
		$post['tabla'] = 'RespuestasExt';
		break;
	case 9:
		$post['tabla'] = 'CondicionalesExt';
		break;
	case 10:
		$post['tabla'] = 'ChecklistImagenes';
		break;
	case 11:
		$post['tabla'] = 'ChecklistExt';
		break;
	default:
		break;
}

$o = $_POST['opt'];
$updIdentif = ($o == '5' || $o == '6' || $o == '7' || $o == '8');
	// print2($o);

switch ($_POST['acc']) {
	case '1':
		$post['datos'] = $_POST['datos'];
		// print2($post);
		$rj =  atj(inserta($post));
		$r = json_decode($rj,true);
		if($r['ok'] == 1 && ($updIdentif) ){
			$db->query("UPDATE $post[tabla] SET identificador = CONCAT(identificador,'$r[nId]') WHERE id = $r[nId]");
		}
		echo $rj;
		break;
	case '2':
		$post['datos'] = $_POST['datos'];
		// print2($post);
		echo atj(upd($post));
		break;	
	case '3':
		$sel = $db->query("SELECT $fields FROM $post[tabla] 
			WHERE $campo = $_POST[eleId] $elim $order")->fetchAll(PDO::FETCH_ASSOC);
		echo atj($sel);
		break;
	case '4':
		try {
			$db->query("DELETE FROM CondicionalesExt WHERE id = $_POST[condId]");
			echo '{"ok":"1"}';
		} catch (Exception $e) {
			echo '{"ok":"0"}';
		}
		break;
	case '5':
		// print2($_POST);
		foreach ($_POST['bloques'] as $b) {
			$post['datos'] = $b;
			$r = json_decode( upd($post),true );
			// print2($r);
			if($r['ok'] != 1){
				exit('{"ok":"0"}');
			}
		}
		echo '{"ok":"1"}';
		break;
	case '6':
		ini_set(error_reporting(0));
		include_once 'Calc.min.php';
		// print2($_POST);
		$p = $_POST['cond'];
		$test = $_POST['cond'];



		$pattern = '/ /';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/[\+\*\-\/=YO<>!]/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/val/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/pos/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/contar/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/[0-9]+\.*[0-9]*/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/[\(\)]/';
		$p = preg_replace($pattern, '', $p);

		echo $p;
		$result = 'a';
		if(empty($p)){
			$pattern = '/ /';
			$test = preg_replace($pattern, '', $test);
			$pattern = '/<=/';
			$test = preg_replace($pattern, '+', $test);
			$pattern = '/>=/';
			$test = preg_replace($pattern, '+', $test);
			$pattern = '/!=/';
			$test = preg_replace($pattern, '+', $test);
			$pattern = '/[<>]/';
			$test = preg_replace($pattern, '+', $test);
			$pattern = '/=/';
			$test = preg_replace($pattern, '+', $test);
			$pattern = '/val\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
			$test = preg_replace($pattern, 1, $test);
			$pattern = '/pos\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
			$test = preg_replace($pattern, 1, $test);
			$pattern = '/contar\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
			$test = preg_replace($pattern, 1, $test);
			$pattern = '/[YO]/';
			$test = preg_replace($pattern, '+', $test);

			// echo $test;
			ob_start();
			$calc = new EvalMath();
			$result = $calc->evaluate($test);
			ob_end_clean();
		}

		// echo $test."\n";
		// echo $result."\n";
		


		if(is_numeric($result)){
			echo '{"ok":"1"}';
		}else{
			echo '{"ok":"0"}';
		}


		break;

	case '7':
		// print2($_POST);
	$imgId = $_POST['datos']['id'];
		try {
			$db->query("DELETE FROM ChecklistImagenesExt WHERE id = $imgId");
			echo '{"ok":"1"}';
		} catch (Exception $e) {
			echo '{"ok":"0","err":"'.$e->getMessage().'"}';
		}
		break;


	default:
		# code...
		break;
}








?>








