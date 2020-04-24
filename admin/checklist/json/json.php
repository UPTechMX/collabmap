<?php  

include_once '../../../lib/j/j.func.php';
checaAcceso(50); // checaAcceso Checklist

// print2($_POST);
// $aaa = array_map('trim', $_POST);
// print2($_POST);
if(!empty($_POST['datos']['identificador'])){
	$_POST['datos']['identificador'] = trim($_POST['datos']['identificador']);
}

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
		$campo = 'etapa';
		$elim = 'AND (elim != 1 OR elim IS NULL)';
		$order = 'ORDER BY nombre DESC';

		break;
	case 4:
		// print2 ($_POST);
		$post['tabla'] = 'Checklist';
		$fields = 'id as val, CONCAT(nombre," (",siglas,")") as nom, "clase" as clase';
		$campo = 'etapa';
		$elim = 'AND (elim != 1 OR elim IS NULL)';
		break;
	case 5:
		$post['tabla'] = 'Bloques';
		break;
	case 6:
		$post['tabla'] = 'Areas';
		break;
	case 7:
		$post['tabla'] = 'Preguntas';
		break;
	case 8:
		$post['tabla'] = 'Respuestas';
		break;
	case 9:
		$post['tabla'] = 'Condicionales';
		break;
	case 10:
		$post['tabla'] = 'ChecklistImagenes';
		break;
	case 11:
		$post['tabla'] = 'Checklist';
		break;
	case 12:
		$post['tabla'] = 'Categories';
		break;
	default:
		break;
}

$o = $_POST['opt'];
$updIdentif = ($o == '5' || $o == '6' || $o == '7' || $o == '8');
// print2($o);
// print2($_POST);

switch ($_POST['acc']) {
	case '1':
		$post['datos'] = $_POST['datos'];
		// print2($post);
		$rj =  atj(inserta($post));
		$r = json_decode($rj,true);
		if($r['ok'] == 1 && ($updIdentif) ){
			// echo "ACA\n";
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
			WHERE $campo = '$_POST[eleId]' $elim $order")->fetchAll(PDO::FETCH_ASSOC);
		echo atj($sel);
		break;
	case '4':
		try {
			$db->query("DELETE FROM Condicionales WHERE id = $_POST[condId]");
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
		# $pattern = '/\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
		$pattern = '/\(.+)/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/[0-9]+\.*[0-9]*/';
		$p = preg_replace($pattern, '', $p);
		$pattern = '/[\(\)]/';
		$p = preg_replace($pattern, '', $p);

		// echo $p;
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
			# $pattern = '/val\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
			$pattern = '/val\(.+\)/';
			$test = preg_replace($pattern, 1, $test);
			# $pattern = '/pos\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
			$pattern = '/pos\(.+\)/';
			$test = preg_replace($pattern, 1, $test);
			# $pattern = '/contar\(p_[0-9]+_[0-9]+_[0-9]+_[0-9]+\)/';
			$pattern = '/contar\(.+\)/';
			$test = preg_replace($pattern, 1, $test);
			$pattern = '/[YO]/';
			$test = preg_replace($pattern, '+', $test);

			// echo $test;
			ob_start();
			$calc = new EvalMath();
			$result = $calc->evaluate($test);
			ob_end_clean();
		}else{
			$err = "Sintaxis erronea, favor de verificarla. <br/> Los siguientes caracteres no son reconocidos: ".$p;
		}

		// echo "Test: ".$test."\n";
		// echo "Result: ".$result."\n";
		


		if(is_numeric($result)){
			echo '{"ok":"1"}';
		}else{
			echo '{"ok":"0","Err":"'.$err.'"}';
		}


		break;

	case '7':
		// print2($_POST);
	$imgId = $_POST['datos']['id'];
		try {
			$db->query("DELETE FROM ChecklistImagenes WHERE id = $imgId");
			echo '{"ok":"1"}';
		} catch (Exception $e) {
			echo '{"ok":"0","err":"'.$e->getMessage().'"}';
		}
		break;
	case '8':
		// print2($_POST);
		// exit();

		$ok = true;

		$db->beginTransaction();
		if($_POST['n'] == 1){		
			$p['tabla'] = 'Studyarea';
			$p['datos']['preguntasId'] = $_POST['pId'];
			$p['geo'] = $_POST['geo'];
			$p['geo']['field'] = 'geometry';
			$rj = atj(inserta($p));
			$r = json_decode($rj,true);
		}else{
			$r['ok'] = 1;
			$r['nId'] = $_POST['saId'];

			$p['tabla'] = 'Studyarea';
			// $p['datos']['id'] = $_POST['saId'];
			$p['where'] = "id = $_POST[saId]";
			$p['geo'] = $_POST['geo'];
			$p['geo']['field'] = 'geometry';
			upd($p);
			// print2($_POST);
			// $db->query("DELETE FROM StudyareaPoints WHERE studyareaId = $_POST[saId]");
		}


		if($r['ok'] == 1){
			$saId = $r['nId'];
		}else{
			$ok = false;
			$err = "Error al crear studyarea, Err: SA:200";
			
		}

		if($ok){
			$db->commit();
			echo '{"ok":"1","saId":"'.$saId.'"}';
		}else{
			$db->rollBack();
			echo '{"ok":"0","err":"'.$err.'"}';
		}

		break;
	case 9:
		$sas = $db->query("SELECT sa.id, ST_AsGeoJSON(sa.geometry) as geometry
			FROM Studyarea sa
			WHERE preguntasId = $_POST[pId]")->fetchAll(PDO::FETCH_ASSOC);

		echo atj($sas);

		break;
	case 10:
		// print2($_POST);
		$ok = true;
		// $db->beginTransaction();

		foreach ($_POST['lIds'] as $lId) {
			if(is_numeric($lId)){
				$db->query("DELETE FROM Studyarea WHERE id = $lId");
			}
		}

		break;
	case 11:
		// print2($_POST);
		try{

			if(is_numeric($_POST['catId'])){
				$db->query("DELETE FROM Categories WHERE id = $_POST[catId]");
			}
			echo '{"ok":1}';
		}catch(PDOException $e){
			echo '{"ok":0}';
		}

		break;
	default:
		# code...
		break;
}



if(!empty($_POST['chkId'])){
	$db->query("DELETE FROM ChecklistEst WHERE checklistId = $_POST[chkId]");
}




?>








