<?php

if(!isset($_POST['include'])){
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(50); // checaAcceso Checklist



// print2($_POST);
// exit();

$chklist = $db->query("SELECT * FROM Checklist WHERE id = $_POST[checklistId]")->fetch(PDO::FETCH_ASSOC);

$p['tabla'] = 'Checklist';
$p['datos']	= $chklist;
unset($p['datos']['id']);
$p['datos']['nombre'] = $p['datos']['nombre']."_Copy";
// $p['datos']['repeticionesId'] = $_POST['repId'];

$db->beginTransaction();
try {
	// $db->commit();
	// print2($p);
	$rj = inserta($p);
	$r = json_decode($rj,true);
	$ok = true;
	if($r['ok'] == 1 && $ok){
		$chklist['nId'] = $r['nId'];
		$bloques = $db->query("SELECT * FROM Bloques 
			WHERE checklistId = $_POST[checklistId] AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($bloques as $b) {
			$pb['tabla'] = 'Bloques';
			$pb['datos'] = $b;
			unset($pb['datos']['id']);
			$pb['datos']['checklistId'] = $chklist['nId'];
			$rbj = inserta($pb);
			$rb = json_decode($rbj,true);
			if($rb['ok'] == 1 && $ok){
				$b['nId'] = $rb['nId'];
				$conds = $db->query("SELECT * FROM Condicionales 
					WHERE eleId = $b[id] AND aplicacion = 'bloque'")->fetchAll(PDO::FETCH_ASSOC);
				foreach ($conds as $c) {
					$pc['tabla'] = 'Condicionales';
					$pc['datos']=$c;
					unset($pc['datos']['id']);
					$pc['datos']['eleId'] = $b['nId'];
					inserta($pc);
					// print2($c);
				}
				$areas = $db->query("SELECT * FROM Areas 
					WHERE bloquesId = $b[id] AND (elim != 1 OR elim IS NULL)")->fetchAll(PDO::FETCH_ASSOC);
				foreach ($areas as $a) {
					$pa['tabla'] = 'Areas';
					$pa['datos'] = $a;
					unset($pa['datos']['id']);
					$pa['datos']['bloquesId'] = $b['nId'];
					$raj = inserta($pa);
					$ra = json_decode($raj,true);
					if($ra['ok'] == 1 && $ok){
						$a['nId'] = $ra['nId'];
						$conds = $db->query("SELECT * FROM Condicionales 
							WHERE eleId = $a[id] AND aplicacion = 'area'")->fetchAll(PDO::FETCH_ASSOC);
						foreach ($conds as $c) {
							$pc['tabla'] = 'Condicionales';
							$pc['datos']=$c;
							unset($pc['datos']['id']);
							$pc['datos']['eleId'] = $a['nId'];
							inserta($pc);
							// print2($c);
						}
						$pregs = $db->query("SELECT p.* FROM Preguntas p
							WHERE areasId = $a[id] AND p.subareasId IS NULL AND (elim != 1 OR elim IS NULL)
							ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
						// print2($pregs);
						foreach ($pregs as $p) {
							$pp['tabla'] = 'Preguntas';
							$pp['datos'] = $p;
							unset($pp['datos']['id']);
							$pp['datos']['areasId'] = $a['nId'];
							$rpj = inserta($pp);
							$rp = json_decode($rpj,true);
							if($rp['ok'] == 1 && $ok){
								$p['nId'] = $rp['nId'];
								// print2($pp);
								$conds = $db->query("SELECT * FROM Condicionales 
									WHERE eleId = $p[id] AND aplicacion = 'preg'")->fetchAll(PDO::FETCH_ASSOC);
								foreach ($conds as $c) {
									$pc['tabla'] = 'Condicionales';
									$pc['datos']=$c;
									unset($pc['datos']['id']);
									$pc['datos']['eleId'] = $p['nId'];
									inserta($pc);
									// print2($c);
								}
								$resps = $db->query("SELECT * FROM Respuestas 
									WHERE preguntasId = $p[id] AND (elim != 1  OR elim IS NULL) 
									ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

								foreach ($resps as $r) {
									$pr['tabla'] = 'Respuestas';
									$pr['datos'] = $r;
									unset($pr['datos']['id']);
									$pr['datos']['preguntasId'] = $p['nId'];
									$rrj = inserta($pr);
									$rr = json_decode($rrj,true);
									if($rr['ok'] == 1){
										$r['nId'] = $rr['nId'];
										$conds = $db->query("SELECT * FROM Condicionales 
											WHERE eleId = $r[id] AND aplicacion = 'resp'")->fetchAll(PDO::FETCH_ASSOC);
										foreach ($conds as $c) {
											$pc['tabla'] = 'Condicionales';
											$pc['datos']=$c;
											unset($pc['datos']['id']);
											$pc['datos']['eleId'] = $r['nId'];
											inserta($pc);
											// print2($c);
										}
									}
								}
								$subpregs = $db->query("SELECT p.* FROM Preguntas p
									WHERE subareasId = $p[id] 
									ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
								foreach ($subpregs as $sp) {
									$psp['tabla'] = 'Preguntas';
									$psp['datos'] = $sp;
									unset($psp['datos']['id']);
									$psp['datos']['areasId'] = $a['nId'];
									$psp['datos']['subareasId'] = $p['nId'];
									$rsj = inserta($psp);
									$rs = json_decode($rsj,true);

									// print2($sp);
									// print2($psp);
									if($rs['ok'] == 1 && $ok){
										$sp['nId'] = $rs['nId'];
										$conds = $db->query("SELECT * FROM Condicionales 
											WHERE eleId = $sp[id] AND aplicacion = 'preg'")->fetchAll(PDO::FETCH_ASSOC);
										foreach ($conds as $c) {
											$pc['tabla'] = 'Condicionales';
											$pc['datos']=$c;
											unset($pc['datos']['id']);
											$pc['datos']['eleId'] = $sp['nId'];
											inserta($pc);
											// print2($c);
										}

										$resps = $db->query("SELECT * FROM Respuestas 
											WHERE preguntasId = $sp[id] AND (elim != 1  OR elim IS NULL) 
											ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

										foreach ($resps as $r) {
											$pr['tabla'] = 'Respuestas';
											$pr['datos'] = $r;
											unset($pr['datos']['id']);
											$pr['datos']['preguntasId'] = $sp['nId'];
											$rrj = inserta($pr);
											$rr = json_decode($rrj,true);
											if($rr['ok'] == 1){
												$r['nId'] = $rr['nId'];
												$conds = $db->query("SELECT * FROM Condicionales 
													WHERE eleId = $r[id] AND aplicacion = 'resp'")->fetchAll(PDO::FETCH_ASSOC);
												foreach ($conds as $c) {
													$pc['tabla'] = 'Condicionales';
													$pc['datos']=$c;
													unset($pc['datos']['id']);
													$pc['datos']['eleId'] = $r['nId'];
													inserta($pc);
													// print2($c);
												}
											}
										}
									}else{
										$ok = false;
									}
								}

							}else{
								$ok = false;
							}
						}

					}else{
						$ok = false;
					}

				}
			}else{
				$ok = false;
			}
			// print2($b);
		}

	}

	$imagenes = $db->query("SELECT * FROM ChecklistImagenes WHERE checklistId = $_POST[checklistId]")->fetchAll(PDO::FETCH_ASSOC);
	foreach ($imagenes as $img) {
		$pImg['tabla'] = 'ChecklistImagenes';
		$pImg['datos']	= $img;
		unset($pImg['datos']['id']);
		$pImg['datos']['checklistId'] = $chklist['nId'];
		$rj = inserta($pImg);
		$r = json_decode($rj,true);
		if($r['ok'] != 1){
			$ok = false;
		}

	}

	$conds = $db->query("SELECT * FROM Condicionales WHERE eleId = $_POST[checklistId] AND aplicacion = 'chk' ")->fetchAll(PDO::FETCH_ASSOC);
	foreach ($conds as $c) {
		$cc['tabla'] = 'Condicionales';
		$cc['datos']=$c;
		unset($cc['datos']['id']);
		$cc['datos']['eleId'] = $chklist['nId'];
		inserta($cc);
		// print2($c);
	}


	if($ok){
		$db->commit();
	}else{
		$db->rollBack();
	}
	// $db->rollBack();
	echo '{"ok":1,"nId":'.$chklist['nId'].'}';
} catch (PDOException $e) {
	echo '{"ok":0}';
	$db->rollBack();
}

// if()

?>