<?php 

include 'lib/j/j.func.php';

$db->beginTransaction();

$visitasN = $dbN -> query("SELECT * FROM Visitas 
	WHERE finishdate >= '2020-06-02 06:48:09' AND finalizada = 1 AND type = 'trgt' AND estatus IS NULL ")->fetchAll(PDO::FETCH_ASSOC);

// print2($visitasN);
$i = 1;
foreach ($visitasN as $v) {
	$teN = $dbN->query("SELECT te.*, '$v[checklistId]' as chkId,
		u.username, u.name as uName,
		de.nombre as deNombre, de.padre as padre, de.dimensionesId as dimensionesId
		FROM TargetsElems te
		LEFT JOIN Users u ON u.id = te.usersId
		LEFT JOIN DimensionesElem de ON de.id = te.dimensionesElemId
		LEFT JOIN UsersTargets ut ON ut.id = te.usersTargetsId
		WHERE te.id = $v[elemId]
	")->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($teN);
	// continue;
	if(!empty($teN['padre']) && !empty($teN['deNombre'])){
		$sql = "SELECT * FROM DimensionesElem WHERE padre = $teN[padre] AND nombre = '$teN[deNombre]'";
		// echo "$sql <br/>";
		$de = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
		if(!empty($de['id'])){
			// print2($teN);
			// print2($de);
			$user = $db->query("SELECT * FROM Users WHERE username = '$teN[username]'")->fetchAll(PDO::FETCH_ASSOC)[0];			
			if(!empty($user['id'])){
				$ut = $db->query("SELECT * FROM UsersTargets WHERE usersId = $user[id] AND targetsId = 21")->fetchAll(PDO::FETCH_ASSOC)[0];
				$te = $db->query("SELECT * FROM TargetsElems 
					WHERE UsersTargetsId = $ut[id] AND targetsId = 21")->fetchAll(PDO::FETCH_ASSOC)[0];
				$utId = $ut['id'];
				$uId = $user['id'];
				if(!empty($te)){
					// echo "TE---<br/>";
					// print2($te);
					// echo "----TE<br/>";
					$teId = $te['id'];
				}else{
					$pte['tabla'] = 'TargetsElems';
					$pte['datos']['dimensionesElemId'] = $de['id'];
					$pte['datos']['targetsId'] = 21;
					$pte['datos']['usersTargetsId'] = $utId;
					$pte['datos']['usersId'] = $uId;

					$rpte = json_decode(atj(inserta($pte)),true);
					$teId = $rpte['nId'];
				}
			}else{
				// echo "SIN USER-----<br/>";
				// print2($teN);
				$te = $db->query("SELECT te.*, u.username, u.name as uName
					FROM TargetsElems te
					LEFT JOIN Users u ON u.id = te.usersId
					WHERE dimensionesElemId = $teN[dimensionesElemId]")->fetchAll(PDO::FETCH_ASSOC)[0];

				$st = $db->query("SELECT kec.nombre as kecName, kel.nombre as kelName, rv.nombre as rvName, rt.nombre as rtName 
					FROM DimensionesElem rt 
					LEFT JOIN DimensionesElem rv ON rv.id = rt.padre
					LEFT JOIN DimensionesElem kel ON kel.id = rv.padre
					LEFT JOIN DimensionesElem kec ON kec.id = kel.padre
					WHERE rt.id = $teN[dimensionesElemId]")->fetchAll(PDO::FETCH_ASSOC)[0];
				// print2($te);

				// echo "-----SIN USER<br/>";
				if($te['username'] == '1234567890123456' || $te['username'] == '3374050101730002'){
					$pu['tabla'] = 'Users';
					$pu['datos']['username'] = $te['username'];
					$pu['datos']['name'] = $te['uName'];
					$pu['datos']['confirmed'] = 1;
					$pu['datos']['validated'] = 1;

					$rpu = json_decode(atj(inserta($pu)),true);
					$uId = $rpu['nId'];

					$put['table'] = 'UsersTargets';
					$put['datos']['usersId'] = $uId;
					$put['datos']['targetsId'] = 21;

					$rput = json_decode(atj(inserta($put)),true);
					$utId = $rput['nId'];

					$pte['tabla'] = 'TargetsElems';
					$pte['datos']['dimensionesElemId'] = $de['id'];
					$pte['datos']['targetsId'] = 21;
					$pte['datos']['usersTargetsId'] = $utId;
					$pte['datos']['usersId'] = $uId;

					$rpte = json_decode(atj(inserta($pte)),true);
					$teId = $rpte['nId'];


				}else{
					echo "NIK Old : $te[username] / $te[uName], NIK New $teN[username] / $teN[uName]<br/>
					$st[kecName] - $st[kelName] - $st[rvName] - $st[rtName]<br/><br/>";

					continue;
				}


				// $rpu = json_decode(atj(inserta($pu)),true);
				// $uId = $rpu['id'];
			}
			// print2($rpte);
			// echo $i++."<br/>";
		}else{

			// continue;
			$p['table'] = 'DimensionesElem';
			$p['datos']['dimensionesId'] = 31;
			$p['datos']['nombre'] = $teN['deNombre'];
			$p['datos']['padre'] = $teN['padre'];
			$r = json_decode(atj(inserta($p)),true);

			$deId = $r['nId'];

			$user = $db->query("SELECT * FROM Users WHERE username = '$teN[username]'")->fetchAll(PDO::FETCH_ASSOC)[0];
			if(!empty($user['id'])){
				$ut = $db->query("SELECT * FROM UsersTargets WHERE usersId = $user[id] AND targetsId = 21")->fetchAll(PDO::FETCH_ASSOC)[0];
				$utId = $ut['id'];
				$uId = $user['id'];

			}else{
				$pu['tabla'] = 'Users';
				$pu['datos']['username'] = $teN['username'];
				$pu['datos']['name'] = $teN['uName'];
				$pu['datos']['confirmed'] = 1;
				$pu['datos']['validated'] = 1;

				$rpu = json_decode(atj(inserta($pu)),true);
				$uId = $rpu['nId'];

				$put['table'] = 'UsersTargets';
				$put['datos']['usersId'] = $uId;
				$put['datos']['targetsId'] = 21;

				$rput = json_decode(atj(inserta($put)),true);
				$utId = $rput['nId'];

			}


			$pte['tabla'] = 'TargetsElems';
			$pte['datos']['dimensionesElemId'] = $deId;
			$pte['datos']['targetsId'] = 21;
			$pte['datos']['usersTargetsId'] = $utId;
			$pte['datos']['usersId'] = $uId;

			$rpte = json_decode(atj(inserta($pte)),true);
			$teId = $rpte['nId'];


		}

		$pv['tabla'] = 'Visitas';
		$pv['datos'] = $v;
		unset($pv['datos']['id']);
		$pv['datos']['estatus'] = 'import';

		// echo print2($pv);
		$rpv = json_decode(atj(inserta($pv)),true);
		// print2($rpv);
		$vId = $rpv['nId'];

		// echo "- - - $vId - - -<br/>";

		$rv = $dbN->query("SELECT rv.*, t.siglas as tipo
			FROM RespuestasVisita rv
			LEFT JOIN Preguntas p ON p.id = rv.preguntasId
			LEFT JOIN Tipos t ON t.id = p.tiposId
			WHERE rv.visitasId = $v[id]")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rv as $r) {
			$rvIdN = $r['id'];
			$tipo = $r['tipo'];
			unset($r['id']);
			unset($r['tipo']);

			$r['visitasId'] = $vId;
			$pr['tabla'] = 'RespuestasVisita';
			$pr['datos'] = $r;

			$rpr = json_decode(atj(inserta($pr)),true);
			$rvId = $rpr['nId'];

			if($tipo == 'op' || $tipo == 'spatial' || $tipo == 'cm'){
				$prbN = $dbN->query("SELECT id,type,name,description,categoriesId,respuestasVisitaId,
					photo, AsText(geometry) as geometry
					FROM Problems
					WHERE respuestasVisitaId = $rvIdN
				") -> fetchAll(PDO::FETCH_ASSOC)[0];

				// print2($prbN);
				
				$pprb['tabla'] = 'Problems';
				$pprb['datos']['type'] = $prbN['type'];
				$pprb['datos']['name'] = $prbN['name'];
				$pprb['datos']['description'] = $prbN['description'];
				$pprb['datos']['categoriesId'] = $prbN['categoriesId'];
				$pprb['datos']['respuestasVisitaId'] = $rvId;
				$pprb['datos']['photo'] = $prbN['photo'];

				$pprb['geo']['wkt'] = $prbN['geometry'];
				$pprb['geo']['field'] = 'geometry';
				// echo inserta($pprb);
			}

			// print2($pr);

		}

		// print2($pv);


	}

	$dbN->query("UPDATE Visitas SET estatus = '-1' WHERE id = $v[id]");

}



$db->rollBack();