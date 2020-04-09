<?php 
// echo "aaa";
// error_reporting(0);
date_default_timezone_set('America/Mexico_City'); 
// echo date_default_timezone_get();

	$fotosInst['Foto_Recibo_de_Instalación'] = 'Foto Recibo de Instalación';
	$fotosInst['Foto_Identificación_Oficial'] = 'Foto Identificación Oficial';
	$fotosInst['Foto_de_Techo_Canalizaciones'] = 'Foto de Techo-Canalizaciones';
	$fotosInst['Foto_Bajantes'] = 'Foto Bajantes';
	$fotosInst['Foto_Kit_y_Pastillas'] = 'Foto Kit y Pastillas';
	$fotosInst['Foto_Pichancha'] = 'Pichancha, Reductor y Dosificador';
	$fotosInst['Foto_Bomba'] = 'Foto Bomba';
	$fotosInst['Foto_Prueba_de_Bomba'] = 'Foto Prueba de Bomba';
	$fotosInst['Foto_Tren_Filtrado'] = 'Foto Tren Filtrado';
	$fotosInst['Foto_Sistema_Completo_1'] = 'Foto Sistema Completo 1';
	$fotosInst['Foto_Sistema_Completo_2'] = 'Foto Sistema Completo 2';
	$fotosInst['Foto_Familiar'] = 'Foto Familiar';


if(!function_exists('raiz')){
	/**
	* Busca archivo raiz
	*
	* Busca el archivo raiz ubicado en la raiz del proyecto
	*
	* @param void
	* @return string Ruta desde el root del servidor hasta el directorio donde se hizo la llamada
	*
	*/
	function raiz(){
		if (session_status() == PHP_SESSION_NONE) {
		    @session_start();
		}

		if(empty($_SESSION['IU']['raiz'])){
			$dir = getcwd();
			$dirE = explode('/',$dir);
			$ciclos = count($dirE);
			for($i = $ciclos; $i > 0; $i--){
				$dN = '';
				for($j = 0; $j<$i;$j++){
					$dN .= $dirE[$j].'/';
				}
				if(file_exists($dN.'/raiz')){
					$_SESSION['IU']['raiz'] = $dN;
					return $dN;
				}else{
					if($i == 1){
						exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
					}
				}
			}
		}else{
			return $_SESSION['IU']['raiz'];
		}
	}
	$rz = raiz();
	/**
	* Busca archivo raiz
	*
	* Busca el archivo raiz ubicado en la raiz del proyecto
	*
	* @param void
	* @return string Ruta desde el archivo donde se hizo la llamada al raiz del proyecto
	*
	*/
	function aRaiz(){

		session_start();
		if(empty($_SESSION['IU']['aRaiz'])){
			$dir = getcwd();
			$dirE = explode('/',$dir);
			$ciclos = count($dirE);
			$dN = '';
			
			if(file_exists($dir.'/raiz')){
				return './';		
			}
			
			for($i = $ciclos; $i > 0; $i--){
				
				// for($j = 0; $j<$i;$j++){
				$dN .= '../';
				// }
				if(file_exists($dN.'/raiz')){
					$_SESSION['IU']['aRaiz'] = $dN;
					return $dN;
				}else{
					if($i == 1){
						exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
					}
				}
			}
		}else{
			return $_SESSION['IU']['aRaiz'];
		}
	}

	function aRaizHtml(){

		session_start();
		if(empty($_SESSION['IU']['aRaizHtml'])){

			$dirH = $_SERVER['REQUEST_URI'];
			$dirEH = explode('/',$dirH);
			$ciclosH = count($dirEH);
			$directorioH = $dirEH[$ciclosH-2];
			// print2($_SERVER);
			// echo 'dirH = '.$dirH.'<br/>';
			// print2($dirH);
			// print2($directorioH);
			// print2($ciclosH);


			$dir = getcwd();
			$dirE = explode('/',$dir);
			$ciclos = count($dirE);
			// echo 'dir1 = '.$dir.'<br/>';

			// print2($dir);
			// print2($ciclos);
			// print2($ciclos);

			for ($i=0; $i < $ciclos; $i++) { 
				if($dirE[$i] == $directorioH){
					$j = $i;
				}
			}
			// print2($j);
			$dTmp = $dirE[0];
			for ($i=1; $i <= $j; $i++) { 
				$dTmp .= '/'.$dirE[$i];
			}

			$dir = $dTmp;
			// print2($dir);
			$dirE = explode('/',$dir);
			$ciclos = count($dirE);
			$dN = '';
			// echo 'dir = '.$dir.'<br/>';
			if(file_exists($dir.'/raiz')){
				$_SESSION['IU']['aRaizHtml'] = './';
				return './';		
			}
			
			// echo '----<br/>';
			for($i = $ciclos; $i > 0; $i--){
				
				// for($j = 0; $j<$i;$j++){
				$dN .= '../';
				$tmp = '';
				for($h = 0;$h<$i-1;$h++){
					$tmp .= $dirE[$h].'/';
				}
				// print2($tmp);
				// }
				if(file_exists($tmp.'raiz')){
					$_SESSION['IU']['aRaizHtml'] = $dN;
					return $dN;
				}else{
					if($i == 1){
						exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
					}
				}
			}
		}else{
			return $_SESSION['IU']['aRaizHtml'];
		}
	}

}



// include_once 'basepdo.php';
/**
* Funciones del sistema
*
* Librería de métodos del sistema del INECC
*
* @author notland <info@notland.mx>
* @copyright 2015 notLand
*
*/
include_once raiz().'basepdo.php';

/**
* Imprime objetos de manera ordenada
*
* Funcion para imprimir objetos y arreglos de manera ordenada.
* 
* @param mixed[] $algo 
* @return void
*
*/
function print2($algo, $foo = false) {
	echo '<pre>';
	print_r($algo, $foo);
	echo '</pre>';
}

/**
* Codificador
*
* Cambia la codificación de una cadena a utf8
*
* @param string $str cadena a convertir
* @return string $str cadena codificada en utf8
*
*/
function utf8Str($str){
	$str = mb_convert_encoding($str,"UTF-8");
	return $str;
}



/**
* Inserta datos en la base de datos
*
* Inserta $post['datos'] en la tabla $post['tabla'] 
*
* @param array $post $post['tabla'] es la tabla a donde se inseta la información, $post['datos']
* es el arreglo de datos a insertar en la base de datos.
* @return string JSON ok = 1 representa que la información fue almacenada correctamente,
* nId es el id con el que fue almacenada la información
*
*/
function inserta($post){
	// print2($post);
	global $db;
	$t = $post['tabla'];
	if($post['timestamp'] != ""){
		$sql = "INSERT INTO $t SET $post[timestamp] = NOW(),";
	}else{
		$sql = "INSERT INTO $t SET ";
	}
	foreach ($post['datos'] as $k => $v) {
		$sql .= "$k = :$k, ";
	}
	

	$sql = trim($sql,', ');

	// echo $sql;

	try {
		$stmt = $db -> prepare($sql);
		$stmt->execute($post['datos']);
		$nId = $db->lastInsertId();
		$r = '{"ok":"1","nId":"'.$nId.'"}';
	} catch (PDOException $e) {
		$r = '{"ok":"0","e":"'.$e->getMessage(). ' Linea: '.$e->getLine(). '","sql":"'.$sql.'"}';
	}
	return $r;
}

function replace($post){
	// print2($post);
	global $db;
	$t = $post['tabla'];
	if($post['timestamp'] != ""){
		$sql = "REPLACE INTO $t SET $post[timestamp] = NOW(),";
	}else{
		$sql = "REPLACE INTO $t SET ";
	}
	foreach ($post['datos'] as $k => $v) {
		$sql .= "$k = :$k, ";
	}
	

	$sql = trim($sql,', ');

	try {
		$stmt = $db -> prepare($sql);
		$stmt->execute($post['datos']);
		$nId = $db->lastInsertId();
		$r = '{"ok":"1","nId":"'.$nId.'"}';
	} catch (PDOException $e) {
		$r = '{"ok":"0","e":"'.$e->getMessage(). ' Linea: '.$e->getLine(). '","sql":"'.$sql.'"}';
	}
	return $r;
}

/**
* Actualiza datos en la base de datos
*
* Actualiza $post['datos'] en la tabla $post['tabla'] con id $post['id']
*
* @param array $post $post['tabla'] es la tabla a donde se inseta la información, $post['datos']
* es el arreglo de datos a insertar en la base de datos, $post['id'] es el id de los datos a modificar
* @return string JSON ok = 1 representa que la información fue almacenada correctamente,
*
*/
function upd($post){
	// print2($post);
	global $db;
	$t = $post['tabla'];

	if($post['timestamp'] != ""){
		$sql = "UPDATE $t SET $post[timestamp] = NOW(),";
	}else{
		$sql = "UPDATE $t SET ";
	}

	// $sql = "UPDATE $t SET ";
	foreach ($post['datos'] as $k => $v) {
		if($k!='id'){
			$sql .= "$k = :$k, ";
		}
	}
	$sql = trim($sql,', ');
	if(!isset($post['where'])){
		$sql .= ' WHERE id = :id';
	}else{
		$sql .= " WHERE $post[where]";
	}

	try {
		$stmt = $db -> prepare($sql);
		if(isset($post['id'])){
			$post['datos']['id'] = $post['id'];
		}
		$stmt->execute($post['datos']);
		$r .= '{"ok":"1"}';
		// print2($post['datos']);
	} catch (PDOException $e) {
		$r .= '{"ok":"0","err":"'.$e.'","sql":"'.$sql.'"}';
	}
	// echo 'aaaaaa';
	return $r;
}

/**
* Elimina datos en la base de datos
*
* Elimina los elementos de la tabla $post['tabla'] que cumplan $post['where']
*
* @param array $post $post['tabla'] es la tabla a donde se aliminará la información, $post['where']
* es la condición en SQL para eliminar los datos
* @return string JSON ok = 1 representa que la información fue almacenada correctamente,
*
*/
function del($post){
	global $db;
	$tabla = $post['tabla'];
	$where = $post['where'];

	try {
		$sql = "DELETE FROM $tabla WHERE $where";
		$db->exec($sql);
		$r .= '{"ok":"1"}';
	} catch (PDOException $e) {
		$r .= '{"ok":"0","e":"'.$e->getMessage().'","sql":"'.$sql.'"}';
	}

	return $r;
}

/**



* Codifica a JSON
*
* Codifica un arreglo en formato JSON
*
* @param array $arr Arreglo a convertir en json
* @return string JSON codificación del arreglo en JSON
*
*/
function atj($arr){
	if(is_array($arr)){
		array_walk_recursive($arr, function (&$value) {
			// $value = htmlentities($value);
			$value = $value;
		});
		$j = json_encode($arr);
		return $j;
	}else{
		return $arr;
	}
}


function dec($arr){
	return $arr;
}



/**
* Encripta un password de usaurio 
*
* Encripta un password dado para un usuario
*
* @param string $pwd password a encriptar
* @return string password encriptado
*
*/
function encriptaUsr($pwd){
	$options = [
	    'cost' => 10
	    # 'salt' => substr(str_shuffle(MD5(microtime())), 0, 22)
	];
	$pwd = password_hash($pwd, PASSWORD_DEFAULT, $options);
	// echo 'asasas'.$pwd;
	return $pwd;
}

/**
* Verifica si el usuario y la contraseña son correctos 
*
* Verifica en la base de datos si el usuario y contraseña son correctos
*
* @param string $usr nombre de usuario
* @param string $pwd contraseña
* @return bool TRUE si coinciden FALSE si no coinciden
*
*/
function verifPWD($usr,$pwd,$acceso){
	global $db;

	switch ($acceso) {
		case 'admin':
			$stmt = $db->prepare("SELECT * FROM usrAdmin WHERE username = ? AND nivel > 0");
			break;
		case 'ext':
			$stmt = $db->prepare("SELECT u.*, c.logotipo 
				FROM Usuarios u
				LEFT JOIN Clientes c ON c.id = u.clientesId
				WHERE username = ?");
			break;
		case 'shopper':
			$stmt = $db->prepare("SELECT * FROM Shoppers WHERE username = ? AND confirmado = 1");
			break;
		
		default:
			# code...
			break;
	}

	$stmt ->execute(array($usr));
	$usrInf = $stmt -> fetch(PDO::FETCH_ASSOC);

	$r['verif'] = password_verify($pwd,$usrInf['pwd']);
	if($r['verif']){
		$r['usrId'] = $usrInf['id'];
		$r['nombre'] = "$usrInf[nombre] $usrInf[aPat] $usrInf[aMat] ";
		$r['estatusId'] = $usrInf['estatus'];
		$r['clientesId'] = $usrInf['clientesId'];
		$r['logotipo'] = $usrInf['logotipo'];
		if($acceso == 'reportes'){			// echo $sql;
			$r['priv'] = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		}elseif($acceso == 'admin'){
			$r['nivel'] = $usrInf['nivel'];
			// print2($r['priv']);
		}else{
		}
	}

	return $r;
}


function verifPWDExterno($usr,$pwd){
	global $db;

	$stmt = $db->prepare("SELECT * FROM Clientes WHERE token = ?");

	$stmt ->execute(array($usr));

	$usrInf = $stmt -> fetch(PDO::FETCH_ASSOC);

	$r['verif'] = password_verify($pwd,$usrInf['pwd']);
	if($r['verif']){
		$r['clientesId'] = $usrInf['id'];
		$r['token'] = $usrInf['token'];
		$r['nombre'] = "$usrInf[nombre] $usrInf[aPat] $usrInf[aMat] ";
		$r['estatusId'] = $usrInf['estatus'];
		$r['nivel'] = 8;

	}

	return $r;
}


function privUsr($usrId){
	global $db;
	$sql = "SELECT p.siglas,pu.* 
		FROM PrivilegiosUsuarios pu
		LEFT JOIN PrivilegiosPub p ON pu.privilegiosId = p.id
		WHERE usuariosId = $usrId";

	
	$privs = $db->query($sql) -> fetchAll(PDO::FETCH_ASSOC);

	$ps = array();
	foreach ($privs as $p) {
		$ps[$p['siglas']] = $p['nivel'];
	}

	return $ps;
}

function informe($id,$fecha,$periodo,$tipo,$subcond){

	global $db;
	if($subcond){
		$tabla = 'InformesSubcondiciones';
		$campo = 'subcondicionesId';
	}else{
		$tabla = 'Informes';
		$campo = 'condicionesId';
	}

	$sql = "SELECT COUNT(*) FROM $tabla 
		WHERE $campo = $id AND periodo = $periodo AND tipo = $tipo AND (elim IS NULL OR elim != 1)";
		// echo $sql;
	$cuenta = $db -> query($sql) -> fetch(PDO::FETCH_NUM);

	if($cuenta[0]>0){
		$r = 'entregado';
		$valid = $db -> query("SELECT COUNT(*) FROM $tabla 
			WHERE $campo = $id AND periodo = $periodo AND tipo = $tipo AND validacion >= 1 AND (elim IS NULL OR elim != 1)") -> fetch(PDO::FETCH_NUM);
		if($valid[0] > 0){
			if($cuenta[0] == $valid[0]){
				$r = 'validado';
			}else{
				$r = 'revision';
			}
		}
	}else{
		$r = 'falta';
	}
	return $r;
}

function icono($estado){
	switch ($estado) {
		case 'entregado':
			return 'glyphicon-ok manita azul';
			break;
		case 'falta':
			return 'glyphicon-remove manita rojo';
			break;
		case 'revision':
			return 'glyphicon glyphicon-star-empty manita verde';
			break;
		case 'validado':
			return 'glyphicon glyphicon-star manita verde';
			break;
		case 'bloqueado':
			return 'glyphicon glyphicon-star manita verde';
			break;
		
		default:
			# code...
			break;
	}
}

/**
* Sube archivos en la ruta seleccionada
*
* Sube archivos en la ruta seleccionada
*
* @param string $prefijo es el prefijo que va a llevar el archivo al guardarlo;
* @param array $files es el arreglo de archivos a guardar
* @param string $dir Ruta del directorio desde el raiz donde se guardarán los archivos
* @param boolean $evitarNombre Evita usar el nombre original del archivo, sino que solo usa el prefijo y la extensión
* @return string JSON ok = 1 representa que la información fue almacenada correctamente,
* nId es el id con el que fue almacenada la información
*
*/
function subearchivos($prefijo,$files,$dir, $evitarNombre=false){
	$dirO = $dir;
	$dir = raiz().$dir;

	$evitarNombre = ($evitarNombre && strlen($prefijo)>0);

	if(is_array($files['myfile']['name'])){		
		foreach ($files["myfile"]["error"] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $files["myfile"]["tmp_name"][$key];
				$name = $files["myfile"]["name"][$key];
				move_uploaded_file($tmp_name, "$dir/$prefijo"."$name");
				return '{"ok":1,"nombreArchivo":"'.$name.'","dir":"'.$dirO.'","prefijo":"'.$prefijo.'"}';
			}else{
				return '{"ok":0,"nombreArchivo":"'.$name.'","dir":"'.$dirO.'","prefijo":"'.$prefijo.'"}';
			}
		}
	}else{
			// print2($files);
		// foreach ($files["myfile"]["error"] as $key => $error) {
			if ($files['error'] == UPLOAD_ERR_OK) {
				$tmp_name = $files["myfile"]["tmp_name"];
				if($evitarNombre){
					// solo la extension
					$name = ".".pathinfo($files["myfile"]["name"])['extension'];
				}
				else
					$name = $files["myfile"]["name"];

				$check = 0;
				set_error_handler(function() { 
					$check = 1;
				});
				move_uploaded_file($tmp_name, "$dir/$prefijo"."$name");
				restore_error_handler();
				
				if($check==0)
					return '{"ok":1,"nombreArchivo":"'.$name.'","dir":"'.$dirO.'","prefijo":"'.$prefijo.'"}';
				else
					echo "ksjdfladskfjbaldsjflasdfkjnhalsdfjnalsdfnalsjdflajdsf";
					// return '{"ok":0,"nombreArchivo":"'.$name.'", "mensaje":"Error en permisos de escritura" }';

			}else{
				return '{"ok":0,"nombreArchivo":"'.$name.'","dir":"'.$dirO.'","prefijo":"'.$prefijo.'"}';
			}
		// }
	}
}

function buscaPadres($eleId,&$padres){
	global $db;
	$ele = $db->query("SELECT * FROM DimensionesElem WHERE id = $eleId")->fetch(PDO::FETCH_ASSOC);
	$padres[] = array('eId' => $ele['id'],'nombre'=> $ele['nombre'],'nombrePub'=> $ele['nombrePub']);
	if($ele['padre'] == 0){
		return;
	}else{
		buscaPadres($ele['padre'],$padres);
	}
}

function fechaRandom($fechaIni, $fechaFin, $hora){
	$min = strtotime($fechaIni);
	$max = strtotime($fechaFin);

	$val = rand($min, $max);
	if($hora){
		return date('Y-m-d H:i:s', $val);
	}else{
		return date('Y-m-d', $val);
	}
}

function nomDiaSem($dia){
	switch ($dia) {
		case '0':
			return 'dom';
			break;
		case '1':
			return 'lun';
			break;
		case '2':
			return 'mar';
			break;
		case '3':
			return 'mie';
			break;
		case '4':
			return 'jue';
			break;
		case '5':
			return 'vie';
			break;
		case '6':
			return 'sab';
			break;
		default:
			break;
	}
}

function updEstatusVis($vId,$estatus,$uId,$sId,$estatusPago,$comentarios){
	global $db;
	// echo "$vId\n";
	// $estatusPago = empty($estatusPago)?NULL:$estatusPago;

	try {
		$vInf = $db->query("SELECT * FROM Visitas WHERE id = $vId")->fetchAll(PDO::FETCH_ASSOC)[0];
		$visita = $db->prepare("UPDATE Visitas SET aceptada = :estatus WHERE id = :vId");
		
			$visHist = $db->prepare("INSERT INTO VisitasHistorial 
					SET visitasId = :vId, estatus = :estatus,
					timestamp = NOW(), usuariosId = $uId, shoppersId = :sId, comentarios = :comentarios");

		$visUsr = $db->prepare("UPDATE VisitasUsuarios SET estatus = :estatus
			WHERE visitasId = :vId");

		$visRev = $db -> prepare("UPDATE VisitasRevisores SET estatus = :estatus
			WHERE visitasId = :vId");

		// $revId = $db->query("SELECT usuariosId FROM VisitasUsuarios WHERE visitasId = $vId ")->fetchAll(PDO::FETCH_NUM)[0][0];

		$visUsrArr = array("vId" => $vId, "estatus" => $estatus);
		$visUsr -> execute($visUsrArr);
		$visRevArr = array("vId" => $vId, "estatus" => $estatus);
		$visRev -> execute($visRevArr);


		// Mueve asignación a revisores quizás modificar sólo para estatus chiquitos.
		if($estatus < 70){
			$visUsr = $db->prepare("DELETE FROM VisitasUsuarios WHERE visitasId = :vId");
			$visUsrArr = array("vId" => $vId);
			$visUsr -> execute($visUsrArr);
		}

		if($estatus < 20){
			include raiz().'lib/cron/alertaCanc.php';
		}

		if(is_array($estatusPago)){
			$estPago = $db->prepare("UPDATE Pagos SET estatus = :estatus WHERE concepto = :concepto AND visitasId = :vId");
			foreach ($estatusPago as $concepto => $ep) {
				$estPago -> execute(array('vId'=>$vId,'estatus'=>$ep,'concepto'=>$concepto));
			}
		}

		// if($estatus <)
		
		$shopHist = $db->prepare("UPDATE ShoppersHistorial SET estatus = :estatus 
			WHERE visitasId = :vId");

			$arrHistVis = array('vId'=>$vId,'estatus'=>$estatus,'sId'=>$sId, 'comentarios' => $comentarios);
			$arrVis = array('vId'=>$vId,'estatus'=>$estatus);

		$arrShop = array('vId'=>$vId,'estatus'=>$estatus);

		// print2($arrVis);
		$visHist -> execute($arrHistVis);
		$visita -> execute($arrVis);
		$shopHist -> execute($arrShop);

		$rj = updEstatusRot($vInf['rotacionesId'],$estatus,$uId,$sId,$comentarios);
		$r = json_decode($rj,true);

		if($r['ok'] == 1){
			return '{"ok":"1"}';
		}else{
			return '{"ok":"0","err":"Error al actualizar el estatus de la visita Err: EUR237"}';
		}

	} catch (PDOException $e) {
		// print2($e->getMessage());
		// print2($e);
		return '{"ok":"0","err":"Error al actualizar el estatus de la visita Err: EUV234"}';
	}
}

function updRotVis($rotId,$vId,$estatus,$estatusPago,$uId,$sId,$comentarios){
	global $db;
	if(!empty($vId)){
		return updEstatusVis($vId,$estatus,$uId,$sId,$estatusPago,$comentarios);
	}else{
		$vId = $db->query("SELECT visitaAct FROM Rotaciones WHERE id = $rotId")->fetchAll(PDO::FETCH_NUM)[0][0];
		if(!empty($vId)){
			// echo 'BB';
			return updEstatusVis($vId,$estatus,$uId,$sId,$estatusPago,$comentarios);
		}else{
			// echo 'CC';
			return updEstatusRot($rotId,$estatus,$uId,$sId,$comentarios);
		}

	}
}

function filtrosShoppers($repId,$rotId){
	global $db;

	$rotInf = $db -> query("SELECT t.id as tId, t.municipio, t.estado, r.fecha, repeticionesId as repId
		FROM Rotaciones r 
		LEFT JOIN Tiendas t ON t.id = r.tiendasId
		WHERE r.id = $rotId")->fetchAll(PDO::FETCH_ASSOC)[0];

	$filtros = $db->query("SELECT rf.*, p.pregunta, p.gpoResp 
		FROM RotacionesFiltros rf
		LEFT JOIN PreguntasShopper p ON p.id = rf.preguntaId
		WHERE rf.repeticionesId = $repId")->fetchAll(PDO::FETCH_ASSOC); 

	// print2($filtros);

	$r = genereaSQLFiltros($filtros,$rotInf);

	// print2($r);
	return $r;
}

function genereaSQLFiltros($filtros,$rotInf = null){

	// echo "\n\n\n$fechaHoy\n\n\n";
	if($rotInf == null){
		$fechaHoy = date("Y-m-d");

		$rotInf['fecha'] = date("Y-m-d");
	}

	$joinFiltro = '';
	$whereFiltro = '1';
	$arrBusq = array();
	foreach ($filtros as $i => $f){
		switch ($f['preguntaId']) {
			case '19':
				$f['rangoSup'] = empty(intval($f['rangoSup']))?10000:$f['rangoSup'];
				$f['busqueda'] = empty(intval($f['busqueda']))?0:$f['busqueda'];
				// print2($f);
				$fIni = date('Y-m-d',strtotime("-$f[rangoSup] year", strtotime($rotInf['fecha'])));
				$fFin = date('Y-m-d',strtotime("-$f[busqueda] year", strtotime($rotInf['fecha'])));

				// echo "fecha: $rotInf[fecha], fIni: $fIni , fFin : $fFin<br/>";
				$joinFiltro .=" LEFT JOIN InfoShoppers is$i ON s.id = is$i.shoppersId AND is$i.preguntaId = $f[preguntaId]";
				$whereFiltro .= " AND (floor(datediff('$rotInf[fecha]', is$i.respuesta) / 365) >= $f[busqueda] 
					AND  floor(datediff('$rotInf[fecha]', is$i.respuesta) / 365) <= $f[rangoSup]  )";
				// $whereFiltro .= " AND (is$i.respuesta >= '$fIni' AND is$i.respuesta<='$fFin')";

				break;
			case '67':
				$joinFiltro .=" LEFT JOIN InfoShoppers is$i".'busqueda'." 
					ON s.id = is$i".'busqueda'.".shoppersId AND is$i".'busqueda'.".preguntaId = $f[preguntaId]";
				$tmpAuto .= " 0 OR (is$i".'busqueda'.".respuesta = :is$i".'busqueda'.")";
				$arrBusq["is$i"."busqueda"] = $f['busqueda'];

				$joinFiltro .=" LEFT JOIN InfoShoppers is$i".'ALTbusqueda'." 
					ON s.id = is$i".'ALTbusqueda'.".shoppersId AND is$i".'ALTbusqueda'.".preguntaId = 70";
				$tmpAuto .= " OR (is$i".'ALTbusqueda'.".respuesta = :is$i".'ALTbusqueda'.")";
				$arrBusq["is$i"."ALTbusqueda"] = $f['busqueda'];

				$whereFiltro .= " AND ($tmpAuto)";

				if(!empty($f['rangoSup'])){				
					$joinFiltro .=" LEFT JOIN InfoShoppers is$i".'rangoSup'." 
						ON s.id = is$i".'rangoSup'.".shoppersId AND is$i".'rangoSup'.".preguntaId = 69";

					$joinFiltro .=" LEFT JOIN InfoShoppers is$i".'ALTrangoSup'." 
						ON s.id = is$i".'ALTrangoSup'.".shoppersId AND is$i".'ALTrangoSup'.".preguntaId = 72";

					$modelos = explode(',', $f['rangoSup']);
					$tmp = "0";
					foreach ($modelos as $k=>$m) {
						$tmp .= " OR (is$i".'rangoSup'.".respuesta LIKE :is$i".'rangoSup'."$k)";
						$m = trim($m);
						$arrBusq["is$i"."rangoSup$k"] = "%$m%";

						$tmp .= " OR (is$i".'ALTrangoSup'.".respuesta LIKE :is$i".'ALTrangoSup'."$k)";
						$m = trim($m);
						$arrBusq["is$i"."ALTrangoSup$k"] = "%$m%";
					}
					$whereFiltro .= " AND ($tmp)";
				}

				break;
			case '-1':
				$whereFiltro .= " AND s.genero = '$f[busqueda]'";
				break;
			default:
				$joinFiltro .=" LEFT JOIN InfoShoppers is$i 
					ON s.id = is$i.shoppersId AND is$i.preguntaId = $f[preguntaId]";

				$opciones = explode(',', $f['busqueda']);
				$tmp = "0";
				foreach ($opciones as $k=>$m) {
					$tmp .= " OR (is$i.respuesta LIKE :is$i"."_"."$k)";
					$m = trim($m);
					$arrBusq["is$i"."_"."$k"] = "%$m%";
				}
				$whereFiltro .= " AND ($tmp)";

				break;
		}

		
	}
	// echo "$joinFiltro<br/>";
	$whereFiltro = " AND ($whereFiltro)";
	// echo "$whereFiltro<br/>";
	// print2($arrBusq);
	
	$r['arreglo'] = $arrBusq;
	$r['where'] = $whereFiltro;
	$r['joins'] = $joinFiltro;
	$r['numFiltros'] = count($filtros);

	// print2($r);

	return $r;
}

function getShoppersDisp($rotId,$actFilt,$all,$uId = null,$meses = 6){
	global $db;

	if($all){
		$whereAll = " AND 1 ";
	}else{
		$whereAll = " AND 0 ";
	}

	if(!empty($uId)){
		$whereUid = " AND s.id = $uId";
	}else{
		$whereUid = "";
	}

	$rotInf = $db -> query("SELECT t.id as tId, t.municipio, t.estado, r.fecha, 
		repeticionesId as repId, mp.id as mpId, cmp.capacitacionesId as cId
		FROM Rotaciones r 
		LEFT JOIN Tiendas t ON t.id = r.tiendasId
		LEFT JOIN Repeticiones rep ON rep.id = r.repeticionesId
		LEFT JOIN MarcasProyectos mp ON mp.marcasId = t.marcasId AND mp.proyectosId = rep.proyectosId
		LEFT JOIN CapacitacionesMarcasProyectos cmp ON cmp.marcasProyectosId = mp.id
		WHERE r.id = $rotId")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($rotInf);

	$fecha = $rotInf['fecha'];
	$d = new DateTime($rotInf['fecha']);
	$d->modify('first day of this month');
	$fecha = $d->format('Y-m-d');


	// $fecha = date('Y-m-d');
	// $fecha = date('Y-m-d',strtotime("+4 day", strtotime($fecha)));
	$fecha1 = date('Y-m-d',strtotime("-1 month", strtotime($fecha)));
	$d = new DateTime($fecha1);
	$d->modify('last day of this month');
	$fechaFin = $d->format('Y-m-d');

	$restaMeses = $meses === null ?0:$meses;
	$fecha6 = date('Y-m-d',strtotime("-$meses month", strtotime($fecha)));
	
	if($actFilt){
		$filtro = filtrosShoppers($rotInf['repId'],$rotId);
	}else{
		$filtro = filtrosShoppers(-1,-1);
	}
	// print2($filtro);

	$sqlMunc = "SELECT s.id, s.username, s.nombre, s.aPat, s.aMat, sm.municipio as smMunc,
			CONCAT(s.nombre,' ', s.aPat,' ', s.aMat) as nom, s.id as val, 'clase' as clase,
			COUNT( DISTINCT sh1.id) as vCount1, COUNT( DISTINCT sh6.id) as vCount6
			FROM Shoppers s
			LEFT JOIN ShoppersHistorial sh1 ON sh1.shoppersId = s.id AND (sh1.fecha >= '$fecha')

			LEFT JOIN Capacitaciones c ON c.id = '$rotInf[cId]'
			LEFT JOIN ShoppersCapacitacionesCalif scc ON scc.capacitacionesId = c.id AND scc.shoppersId = s.id
			LEFT JOIN ShoppersCapacitaciones sc ON sc.id = scc.intentoId

			LEFT JOIN ShoppersHistorial sh6 ON sh6.shoppersId = s.id 
				AND sh6.tiendasId = $rotInf[tId] AND (sh6.fecha >= '$fecha6' AND sh6.fecha <= '$rotInf[fecha]')
			LEFT JOIN ShoppersMunicipios sm ON sm.shoppersId = s.id AND sm.municipio = $rotInf[municipio]
			$filtro[joins]
			WHERE (scc.fecha IS NULL OR (scc.calificacion*10>=c.minimo) OR sc.intento != 2 OR ( ADDDATE(LAST_DAY(scc.fecha), 1) <= NOW() ) )
			AND (s.username != '' AND s.username IS NOT NULL AND s.estatus >= 10) $filtro[where] $whereUid
			GROUP BY s.id";

	$sqlEdo = $sql = "SELECT s.id, s.username, s.nombre, s.aPat, s.aMat, sm.municipio as smMunc,
			CONCAT(s.nombre,' ', s.aPat,' ', s.aMat) as nom, s.id as val, 'clase' as clase,
			COUNT( DISTINCT sh1.id) as vCount1, COUNT( DISTINCT sh6.id) as vCount6
			FROM Shoppers s
			LEFT JOIN ShoppersHistorial sh1 ON sh1.shoppersId = s.id AND (sh1.fecha >= '$fecha')

			LEFT JOIN Capacitaciones c ON c.id = '$rotInf[cId]'
			LEFT JOIN ShoppersCapacitacionesCalif scc ON scc.capacitacionesId = c.id AND scc.shoppersId = s.id
			LEFT JOIN ShoppersCapacitaciones sc ON sc.id = scc.intentoId

			LEFT JOIN ShoppersHistorial sh6 ON sh6.shoppersId = s.id 
				AND sh6.tiendasId = $rotInf[tId] AND (sh6.fecha >= '$fecha6' AND sh6.fecha <= '$rotInf[fecha]')
			LEFT JOIN Municipios m ON m.estadosId = $rotInf[estado]
			LEFT JOIN ShoppersMunicipios sm ON sm.shoppersId = s.id AND sm.municipio = m.id
			$filtro[joins]
			WHERE (scc.fecha IS NULL OR (scc.calificacion*10>=c.minimo) OR sc.intento != 2 OR ( ADDDATE(LAST_DAY(scc.fecha), 1) <= NOW() ) )
			AND (s.username != '' AND s.username IS NOT NULL AND s.estatus >= 10) AND sm.municipio IS NOT NULL $filtro[where] $whereUid
			GROUP BY s.id";
			// echo $rotInf['fecha'];
	$sqlAll = $sql = "SELECT s.id, s.username, s.nombre, s.aPat, s.aMat, 1 as smMunc,
			CONCAT(s.nombre,' ', s.aPat,' ', s.aMat) as nom, s.id as val, 'clase' as clase,
			COUNT( DISTINCT sh1.id) as vCount1, COUNT( DISTINCT sh6.id) as vCount6
			FROM Shoppers s
			LEFT JOIN ShoppersHistorial sh1 ON sh1.shoppersId = s.id AND (sh1.fecha >= '$fecha')

			LEFT JOIN Capacitaciones c ON c.id = '$rotInf[cId]'
			LEFT JOIN ShoppersCapacitacionesCalif scc ON scc.capacitacionesId = c.id AND scc.shoppersId = s.id
			LEFT JOIN ShoppersCapacitaciones sc ON sc.id = scc.intentoId

			LEFT JOIN ShoppersHistorial sh6 ON sh6.shoppersId = s.id 
				AND sh6.tiendasId = $rotInf[tId] AND (sh6.fecha >= '$fecha6' AND sh6.fecha <= '$rotInf[fecha]')
			$filtro[joins]
			WHERE (scc.fecha IS NULL OR (scc.calificacion*10>=c.minimo) OR sc.intento != 2 OR ( ADDDATE(LAST_DAY(scc.fecha), 1) <= NOW() ) )
			AND (s.username != '' AND s.username IS NOT NULL AND s.estatus >= 10) $filtro[where] $whereAll $whereUid
			GROUP BY s.id";


	$caso = null;
	if($rotInf['municipio'] != ""){
		$sql = $sqlMunc;
		$wLugar = "AND smMunc IS NOT NULL";
		$caso = 0;
	}elseif($rotInf['estado'] != ""){
		$sql = $sqlEdo;
		$wLugar = "AND smMunc IS NOT NULL";
		$caso = 1;
	}else{
		$sql =$sqlAll;
		$wLugar = "";
		$caso = 2;
	}

	// echo $sql;

	if($meses !== null){
		$wVCount = "vCount6 < 1";
	}else{
		$wVCount = '1';
	}

	$stm = $db->prepare("SELECT * FROM ($sql) as busq WHERE $wVCount $wLugar");
	$stm->execute( $filtro['arreglo'] );
	$shoppers = $stm -> fetchAll(PDO::FETCH_ASSOC);

	if(count($shoppers) == 0){
		switch ($caso) {
			case 0:
				$sql = $sqlEdo;
				$wLugar = "AND smMunc IS NOT NULL";
				$caso = 3;
				break;
			case 1:
				$sql = $sqlAll;
				$caso = 4;
				$wLugar = "";
				break;
			default:
				$sql =$sqlAll;
				$wLugar = "";
				$caso = 4;
				break;
		}

		$stm = $db->prepare("SELECT * FROM ($sql) as busq WHERE $wVCount $wLugar");
		$stm->execute( $filtro['arreglo'] );
		$shoppers = $stm -> fetchAll(PDO::FETCH_ASSOC);
	}

	if(count($shoppers) == 0){
		switch ($caso) {
			case 3:
				$sql = $sqlAll;
				$wLugar = "AND smMunc IS NOT NULL";
				$caso = 5;
				break;
			case 4:
				$sql = $sqlAll;
				$wLugar = "";
				$caso = 5;
				break;
			default:
				break;
		}
		$stm = $db->prepare("SELECT * FROM ($sql) as busq WHERE $wVCount $wLugar");
		$stm->execute( $filtro['arreglo'] );
		$shoppers = $stm -> fetchAll(PDO::FETCH_ASSOC);
	}

	// echo $sql;

	switch ($caso) {
		case 0:
			$msg = 'Los siguientes shoppers han seleccionado el municipio del POS para efectuar visitas';
			break;
		case 1:
			$msg = 'El POS no cuenta con datos del municipio, 
				los siguientes shoppers han seleccionado municipios en el mismo estado del POS';
			break;
		case 2:
			$msg = 'El POS no cuenta con información de estado y municipio, se mostrarán todos los shoppers registrados en el sistema';
			break;
		case 3:
			$msg = 'No se encontraron shoppers en el mismo municipio que el POS, 
				se mostrarán todos los shoppers que eligieron un municipio en el mismo estado del POS';
			break;
		case 4:
			$msg = 'No se encontraron shoppers en el mismo estado del POS, se mostrarán todos los shoppers registrados en el sistema';
			break;
		case 5:
			$msg = 'No se encontraron shoppers con selección de municipios cercanos al POS, se mostrarán todos los shoppers';
			break;
		default:
			# code...
			break;
	}

	$r['shoppers'] = $shoppers;
	$r['caso'] = $caso;
	$r['msg'] = $msg;
	$r['numFiltros'] = $filtro['numFiltros'];

	return $r;
}

function cumpleFiltros($filtros,$uId){
	$cumple = false;

	foreach ($filtros as $f) {
		
	}
}

function asignaVisita($usrAdmin,$shoppersId,$estatus,$rotId){
	global $db;
	$p['tabla'] = "Visitas";
	$p['datos']['rotacionesId'] = $rotId;
	$p['datos']['shoppersId'] = $shoppersId;
	$p['datos']['aceptada'] = $estatus;

	$rj = inserta($p);
	$r = json_decode($rj,true);

	unset($p);
	$ok = true;
	if($r['ok'] == 1){
		$ph['tabla'] = "ShoppersHistorial";
		$ph['datos']['rotacionesId'] = $rotId;
		$ph['datos']['shoppersId'] = $shoppersId;
		$ph['datos']['visitasId'] = $r['nId'];
		$ph['datos']['estatus'] = $estatus;
		$rhj = inserta($ph);
		$rh = json_decode($rhj,true);
		// print2($rh);
	}else{
		$ok = false;
		$err = "Error al generar la visita err: EGV021";
	}

	if($ok){
		$vId = $r['nId'];

		$rj = updEstatusVis($vId,$estatus,$usrAdmin,$shoppersId,null,null);
		$r = json_decode($rj,true);
		if($r['ok'] != 1){
			$ok = false;
			$err = "Error al generar la visita err: EUE221".$r['err'];
		}

	}else{
		$ok = false;
		$err = "Error al generar la visita err: EHVS041";
	}

	if($ok){
		$pr['tabla'] = 'Rotaciones';
		$pr['datos']['id'] = $rotId;
		$pr['datos']['visitaAct'] = $vId;
		
		$rrj = upd($pr);

		$rr = json_decode($rrj);

		$ok = $rr->ok;
		$err = $rr->err;
	}

	if($ok){
		$db->query("INSERT INTO Pagos SET visitasId = $vId, concepto = 1, estatus = 0, usuariosId = $usrAdmin");
		$db->query("INSERT INTO Pagos SET visitasId = $vId, concepto = 2, estatus = 0, usuariosId = $usrAdmin");
		$db->query("INSERT INTO Pagos SET visitasId = $vId, concepto = 3, estatus = 0, usuariosId = $usrAdmin");
	}


	if($ok){
		// $db->commit();
		return '{"ok":"1"}';
	}else{
		return '{"ok":"0","err":"'.$err.'"}';
	}
}

function coloresEstatus($estatus){
	switch ($estatus) {
		case 0: // creada
		case 1:// Cancelada
		case 2:// Cancelada por el shopper
		case 3:// Cancelada por cliente (Se realiza nuevamente)
		case 4:// Cancelada por cliente (No se realizará)
		case 19: //Cancelada después de entregada
			return '';
		case 17: //Pedida
		case 20: //Asignada
		case 21: //Asignada desde administración
		case 22: //Pedida
		case 23: //Aceptada
		case 40: //En campo
		case 50: //Monitoreada
		case 60: //Recibida
			return '#FFA500';
		case 70: //Asisgnada a revisión
		case 80: //Revisión
		case 90: //Revisada
			return '#ADFF2F';
		case 91: //Revisada
			return '#B7220E';
		case 100: //Validada/Publicada
			return '#6B8E23';
		default:
			return '';
	}
}

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

function checaAcceso($nivelPerm){
	session_start();
	$nivel = $_SESSION['IU']['admin']['nivel'];
	if($nivel<$nivelPerm){
		session_destroy();
		exit('No tienes acceso');
	}
}


function checaAccesoExt($nivelPerm){
	session_start();
	$nivel = $_SESSION['IU']['externo']['nivel'];
	if($nivel<$nivelPerm){
		exit('No tienes acceso');
	}
}


function defEstatusPago($estatus){
	switch ($estatus) {
		case 1:
		case 3:
		case 10: // NO PAGA REEMBOLSO Y NO PAGA VISITA
			$estatusPago[1] = 1;
			$estatusPago[2] = 1;
			break;
		case 4:
		case 6:
		case 7:
			return null; // NO MODIFICA EL ESTATUS DEL PAGO (SE PAGA)
			break;
		case 8: // PAGA REEMBOLSO PERO NO PAGA VISITA
			$estatusPago[1] = 10;
			$estatusPago[2] = 1;
			break;
		default:
			return null;
			break;
	}
	return $estatusPago;
}

function estatusPagoTexto($estatusPago){
	switch ($estatusPago) {
		case 0:
			return 'En espera de información';
			break;
		case 1:
			return 'Pago cancelado';
			break;
		case 5:
			return 'Por pagar sólo reembolso';
			break;
		case 10:
			return 'Por pagar';
			break;
		case 11:
			return 'Pago rechazado';
			break;
		case 30:
			return 'Pagada';
			break;
		default:
			return "";
			break;
	}
}

function datCodigoPostal($cp,$all = false){
	global $db;
	$cp = str_pad($cp, 5, "0", STR_PAD_LEFT);

	$completo = $all?', cp.*':'';
	// echo $completo;
	$prepare = $db->prepare("
		SELECT cp.d_codigo as CP, cp.d_asenta as colonia, e.nombre as estado,
		cp.c_estado as estadosId, m.nombre as municipio, cp.c_mnpio as c_mnpio, m.id as municipiosId $completo
		FROM CodigosPostales cp
		LEFT JOIN Estados e ON e.id = cp.c_estado
		LEFT JOIN Municipios m ON m.c_mnpio = cp.c_mnpio AND m.estadosId = e.id
		WHERE cp.d_codigo = ? ORDER BY cp.d_asenta
	");
	
	$prepare -> execute(array($cp));
	
	$resp = $prepare -> fetchAll(PDO::FETCH_ASSOC);

	return $resp;
}

function datosEquip($elem){
	global $db;


	$d = $db->query("SELECT a.nombre, COUNT(*) as numDim
		FROM Dimensiones d
		LEFT JOIN DimensionesElem de ON de.dimensionesId = d.id
		LEFT JOIN AreasEquipos a ON a.id = d.areasId
		LEFT JOIN Dimensiones dd ON dd.areasId = a.id
		WHERE de.id = $elem 
		GROUP BY a.id") -> fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($d);

	$numDim = $d['numDim'];



	$LJ = '';
	$fields = '';

	for ($i=0; $i < $numDim ; $i++) {
		if($i == 0){
			// $LJ .= " LEFT JOIN DimensionesElem de0 ON de0.id = ae.dimensionesElemId 
			// 		 LEFT JOIN Dimensiones d0 ON d0.id = de0.dimensionesId ";
			// $fields .= "d0.nombre as d0, de0.nombre as de0";
		}else{
			$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre
					 LEFT JOIN Dimensiones d$i ON d$i.id = de$i.dimensionesId";
			$fields .= ",d$i.nombre as d$i, de$i.nombre as de$i";
		}
	}


	$sql = "SELECT de0.nombre as de0, d0.nombre as d0, de0.variables, de0.unidad  $fields FROM DimensionesElem de0 
		LEFT JOIN Dimensiones d0 ON d0.id = de0.dimensionesId
		$LJ
		WHERE de0.id = $elem";

	// echo $sql."<br/>";
	$dims = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($dims);
	$r['area'] = $d['nombre'];
	$r['numDim'] = $d['numDim'];
	$r['arbol'] = $dims;

	return $r;
}

function updEstCliente($cteId,$estatus,$usrId,$vId=null,$comentario=null){
	global $db;
	// echo "\naaaaa: ($cteId,$estatus,$usrId) \n";

	$pCte['tabla'] = 'Clientes';
	$pCte['datos']['estatus'] = $estatus;
	$pCte['datos']['id'] = $cteId;
	if(!empty($vId)){
		$pCte['datos']['visitasId'] = $vId;
	}

	$rj = upd($pCte);

	$ok = true;
	$r = json_decode($rj,true);
	if($r['ok'] != 1){
		return '{"ok":"0","err":"Error al actualizar el estatus del cliente Err:UEC001 "}';
	}

	$pHist['tabla'] = 'estatusHist';
	$pHist['datos']['clientesId'] = $cteId;
	$pHist['datos']['estatus'] = $estatus;
	$pHist['datos']['usuarioId'] = $usrId;
	if(!empty($vId)){
		$pHist['datos']['visitasId'] = $vId;
	}
	$pHist['datos']['comentario'] = $comentario;
	$pHist['timestamp'] = 'timestamp';


	creaCambio('Clientes', $cteId);

	return atj(inserta($pHist));
}

function updEstVisita($vId,$estatus,$uId){
	global $db;

	$pVis['tabla'] = 'Visitas';
	$pVis['datos']['id'] = $vId;
	$pVis['datos']['estatus'] = $estatus;

	$rj = atj(upd($pVis));
	$r = json_decode($rj,true);
	if($r['ok'] != 1){
		return '{"ok":"0","errFn":"'.$r['err'].'","err":"Error al actualizar el estatus de la visita Err:UEV001"}';
	}

	return '{"ok":"1"}';

	// $p
}

function updEstVisCte($vId,$estatus,$uId,$comentario=''){
	global $db;
	// echo "\nbbbbbbb: ($vId,$estatus,$uId) \n";
	$visInfo = $db->query("SELECT * FROM Visitas WHERE id = $vId")->fetchAll(PDO::FETCH_ASSOC)[0];


	$rj = updEstCliente($visInfo['clientesId'],$estatus,$uId,$vId,$comentario);
	$ok = true;
	$r = json_decode($rj,true);
	if($r['ok'] != 1){
		return '{"ok":"0","err":"'.$r['err'].'"}';
	}

	$rj = atj(updEstVisita($vId,$estatus,$uId));
	$r = json_decode($rj,true);
	if($r['ok'] != 1){
		return '{"ok":"0","errFn":"'.$r['err'].'","err":"Error al actualizar el estatus de la visita Err:UEV002"}';
	}

	return '{"ok":"1"}';
}

function getPryTotales($pId){
	global $db;

	$sql = "SELECT 
		CASE
			WHEN r.estatus >= 0 AND r.estatus < 5 THEN 'canceladas'
			WHEN r.estatus >= 5 AND r.estatus < 11 AND r.estatus != 4 THEN 'enRegistro'
			WHEN r.estatus >= 30 AND r.estatus < 40 THEN 'enVisita'
			WHEN r.estatus >= 40 AND r.estatus < 50 THEN 'enInstalacion'
			WHEN r.estatus >= 60 THEN 'enSeguimiento'
			ELSE 'algo'
		END as eGroup,
		COUNT(*) as cuenta, estatus
		FROM Clientes r 
		WHERE proyectosId = $pId
		GROUP BY eGroup";
	// echo $sql."<br/>";
	$gpos = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

	$total = 0;
	foreach ($gpos as $g) {
		$total += $g[0]['cuenta'];
	}
	$gpos['total'] = $total;
	// $rotTotales = $this->cuentas($gpos);

	// $rotTotales['gpos'] = $gpos;

	// $this->rotTotales[$pId] = $rotTotales;
	// print2($rotTotales);
	return $gpos;
}

function creaCambio($tabla, $id){
	global $db;

	session_start();
	$usrId = $_SESSION['IU']['admin']['usrId'];

	if($tabla == 'Clientes'){
		$cId = $id;
		$pryId = $db->query("SELECT v.proyectosId FROM Clientes v WHERE v.id = $id")->fetchAll(PDO::FETCH_NUM)[0][0];
		$file = raiz().'admin/proyectos/json/cambios/cambios.cmb';
		if(!is_file($file)){
		    file_put_contents($file, '1');
		}
	}elseif($tabla == 'Visitas'){
		$visInf = $db->query("SELECT v.proyectosId, v.clientesId FROM Visitas v WHERE v.id = $id")->fetchAll(PDO::FETCH_NUM)[0]; 
		$pryId = $visInf[0];
		$cId = $visInf[1];

		$file = raiz().'admin/proyectos/json/cambios/cambios.cmb';
		if(!is_file($file)){
		    file_put_contents($file, '1');
		}
	}

	$db->query("INSERT INTO Cambios SET proyectosId = $pryId, clientesId = $cId, timestamp = NOW(), userId = $usrId;  ");
}

function instalaciones($fecha = null,$pryId){
	global $db;

	$LJP = "
		LEFT JOIN Visitas vInst ON vInst.clientesId = c.id AND vInst.etapa = 'instalacion' 
			AND vInst.id = (SELECT id FROM Visitas k 
				WHERE k.clientesId = vInst.clientesId 
				AND k.etapa = 'instalacion' AND (k.estatus >= 44 AND k.estatus <= 60)
				ORDER BY k.fechaRealizacion DESC 
				LIMIT 1)
	";

	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fecha)) {
	    $fBien = true;
	} else {
	    $fBien = false;
	}

	if(!empty($fecha) && $fBien){
		$WP = "
			AND vInst.fecha = '$fecha'
		";
		$WI = "
			AND vInst.fechaRealizacion = '$fecha'
		";
	}else{
		$resp['programadasMat'] = 0;
		$resp['programadas'] = 0;
		$resp['programadasVesp'] = 0;
		$resp['realizadas'] = 0;
		return $resp;
	}

	// echo "pryId: $pryId;\n<br/> fecha:$fecha\n<br/>";
	$sql = "SELECT COUNT(*) 
		FROM Clientes c
		$LJP
		WHERE (c.instalacionRealizada IS NOT NULL) AND c.proyectosId = $pryId $WI";
	// echo $sql."\n<br/>";

	$realizadas = $db->query($sql)->fetchAll(PDO::FETCH_NUM)[0][0];
	

	$sql = "SELECT COUNT(*) 
		FROM Clientes c
		$LJP
		WHERE (c.estatus >= 44 AND c.estatus < 48) AND vInst.horario = 1 AND c.proyectosId = $pryId $WP ";
	
	// echo $sql."\n<br/>";
	$programadasMat = $db->query($sql)->fetchAll(PDO::FETCH_NUM)[0][0];

	$sql = "SELECT COUNT(*) 
		FROM Clientes c
		$LJP
		WHERE (c.estatus >= 44 AND c.estatus < 48) AND c.proyectosId = $pryId $WP ";
	
	// echo $sql."\n<br/>";
	$programadas = $db->query($sql)->fetchAll(PDO::FETCH_NUM)[0][0];

	$sql = "SELECT COUNT(*) 
		FROM Clientes c
		$LJP
		WHERE (c.estatus >= 44 AND c.estatus < 48) AND vInst.horario = 2 AND c.proyectosId = $pryId $WP ";
	
	// echo $sql."\n<br/>";
	$programadasVesp = $db->query($sql)->fetchAll(PDO::FETCH_NUM)[0][0];


	$resp['programadasMat'] = $programadasMat;
	$resp['programadas'] = $programadas;
	$resp['programadasVesp'] = $programadasVesp;
	$resp['realizadas'] = $realizadas;

	return $resp;


}

function imprimeInstalacion($iId){
	global $db;

	$sql = "SELECT * FROM InstalacionesEquipos WHERE instalacionesId = $iId";
	// echo $sql;

	$eqInst = $db -> query($sql)->fetchAll(PDO::FETCH_ASSOC);
	foreach ($eqInst as $e){ 
		$datEle = datosEquip($e['dimensionesElemId']); 
		$html = "<strong> $datEle[area] :</strong><br/>";
		$arbol = $datEle['arbol'];
		for ($i=$datEle['numDim']-1; $i >= 0; $i--) { 

			// echo $i."<br/>";
			$html .= $arbol["d$i"]." : ".$arbol["de$i"];
			if ($i==0) {
				continue;
			}
			$html .= "&nbsp;<i class='glyphicon glyphicon-chevron-right'></i>&nbsp;";
		}
		$componentes .= "$html<br/></br/>";
	}

	return $componentes;
}








?>
