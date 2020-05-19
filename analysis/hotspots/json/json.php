<?php

include_once '../../../lib/j/j.func.php';
include_once raiz().'lib/php/checklist.php';

checaAcceso(5); // checaAcceso analysis;


switch ($_POST['acc']) {
	case '1':
		if(!is_numeric($_POST['elemId'])){
			exit();
		}
		switch ($_POST['find']) {
			case 'attr':
				$elems = $db->query("SELECT id as val, name as nom, type as clase
					FROM KMLAttributes WHERE KMLId = $_POST[elemId]")->fetchAll(PDO::FETCH_ASSOC);
				break;
			case 'attrOpt':
				$values = $db->query("SELECT DISTINCT(value) as value
					FROM GeometriesAttributes WHERE attributeId = $_POST[elemId]
					ORDER BY value")->fetchAll(PDO::FETCH_ASSOC);
				$elems = array();
				foreach ($values as $v) {
					$tmp = array();
					$tmp['nom'] = $v['value'];
					$tmp['val'] = $v['value'];
					$tmp['clase'] = 'clase';
					$elems[] = $tmp;
				}

				break;
			case 'spatialQ':
			case 'numericQuestions':
				// print2($_POST);
				$findEst = $db->query("SELECT * FROM ChecklistEst WHERE checklistId = $_POST[elemId]")->fetchAll(PDO::FETCH_ASSOC);
				if(empty($findEst)){
					// echo "AQUI";
					$estExt = estructuraEXT($_POST['elemId']);
					$prep = $db->prepare("INSERT INTO ChecklistEst SET checklistId = $_POST[elemId], estructura = ?");
					$prep -> execute(array(atj($estExt)));
					$estj = atj($estExt);
				}else{
					// echo "ACA";
					$estj = $findEst[0]['estructura'];
				}

				$est = json_decode($estj,true);

				$pregsSp = array();
				$pregsNumMult = array();
				foreach ($est['bloques'] as $b) {
					foreach ($b['areas'] as $a) {
						foreach ($a['preguntas'] as $p) {
							if($p['tipo'] == 'sub'){
								foreach ($p['subpregs'] as $sp) {

									// if($sp['tipo'] == 'op' || $sp['tipo'] == 'spatial' || $sp['tipo'] == 'cm'){
									if($sp['tipo'] == 'op' || $sp['tipo'] == 'spatial'){
										$pregsSp[] = $sp;
									}		
									if($sp['tipo'] == 'num' || $sp['tipo'] == 'mult'){
										$pregsNumMult[] = $sp;
									}		
								}
							}
							// if($p['tipo'] == 'op' || $p['tipo'] == 'spatial' || $p['tipo'] == 'cm'){
							if($p['tipo'] == 'op' || $p['tipo'] == 'spatial'){
								$pregsSp[] = $p;
							}
							if($p['tipo'] == 'num' || $p['tipo'] == 'mult'){
								$pregsNumMult[] = $p;
							}		

						}
					}
				}
				$elems = array();

				if($_POST['find'] == 'spatialQ'){
					$lista = $pregsSp;
				}else{
					$lista = $pregsNumMult;
				}

				foreach ($lista as $p) {
					$tmp = array();
					$tmp['val'] = $p['id'];
					$tmp['clase'] = $p['tipo'];
					$tmp['nom'] = $p['pregunta'];
					$elems[] = $tmp;
				}

				break;
			case 'answers':
				$elems = $db->query("SELECT id as val, respuesta as nom, 'clase' as clase
					FROM Respuestas WHERE preguntasId = $_POST[elemId]")->fetchAll(PDO::FETCH_ASSOC);
				break;
			
			default:
				# code...
				break;
		}
		echo atj($elems);
		break;
	default:
		# code...
		break;
}

?>

