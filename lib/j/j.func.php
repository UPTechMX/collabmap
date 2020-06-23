<?php 
// echo "aaa";
// error_reporting(0);
date_default_timezone_set('America/Mexico_City'); 
// echo date_default_timezone_get();

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

		if(empty($_SESSION['CM']['raiz'])){
			$dir = getcwd();
			$dirE = explode('/',$dir);
			$ciclos = count($dirE);
			for($i = $ciclos; $i > 0; $i--){
				$dN = '';
				for($j = 0; $j<$i;$j++){
					$dN .= $dirE[$j].'/';
				}
				if(file_exists($dN.'/raiz')){
					$_SESSION['CM']['raiz'] = $dN;
					return $dN;
				}else{
					if($i == 1){
						exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
					}
				}
			}
		}else{
			return $_SESSION['CM']['raiz'];
		}
	}
	$rz = raiz();

	include_once $rz.'lib/php/i18n_setup.php';

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
		if(empty($_SESSION['CM']['aRaiz'])){
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
					$_SESSION['CM']['aRaiz'] = $dN;
					return $dN;
				}else{
					if($i == 1){
						exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
					}
				}
			}
		}else{
			return $_SESSION['CM']['aRaiz'];
		}
	}

	function aRaizHtml($location){

		session_start();
		if(empty($_SESSION['CM']['aRaizHtml'][$location])){

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
			$dir = $j == 0?'.':$dTmp;
			// print2($dir);
			$dirE = explode('/',$dir);
			$ciclos = count($dirE);
			$dN = '';
			// echo 'dir = '.$dir.'<br/>';
			if(file_exists($dir.'/raiz')){
				// echo "$location - - - -<br/>";
				
				$_SESSION['CM']['aRaizHtml'][$location] = './';
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
					$_SESSION['CM']['aRaizHtml'][$location] = $dN;
					return $dN;
				}else{
					if($i == 1){
						exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
					}
				}
			}
		}else{
			return $_SESSION['CM']['aRaizHtml'][$location];
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
	
	$post['datos'] = is_array($post['datos'])?$post['datos']:array();
	foreach ($post['datos'] as $k => $v) {
		$sql .= "$k = :$k, ";
	}
	
	if(!empty($post['geo'])){
		$geo = $post['geo'];
		if(empty($geo['wkt'])){
			switch ($geo['type']) {
				case 'marker':
					$latlng = json_decode($geo['latlngs'],true);
					$lat = $latlng['lat'];
					$lng = $latlng['lng'];
					if(is_numeric($lat) && is_numeric($lng)){
						$sql .= "$geo[field] = ST_GeometryFromText('Point($lng $lat)'), ";
					}
					break;
				case 'polygon':
					$latlngs = json_decode($geo['latlngs'],true);
					foreach ($latlngs as $latlng) {
						$coords = "(";
						$lat1 = "";
						foreach($latlng as $k => $ll){
							// print2($ll);
							$lat = $ll['lat'];
							$lng = $ll['lng'];
							if(is_numeric($lat) && is_numeric($lng)){
								if($coords == "("){
									$lat1 = $lat;
									$lng1 = $lng;
									// echo ")))((((";
								}

								$coords .= "$lng $lat, ";

							}	
						}
						$coords .= ($lat1 != "" && ($lat1 != $lat || $lng1 != $lng) )?
							"$lng1 $lat1, ":"";
						$coords = trim($coords,', ');
						$coords .= ")";

					}
					
					if($coords != ""){
			// $stmt = $db->query("INSERT INTO Studyarea SET geometry = ST_GeometryFromText('Polygon((0 0,0 3,3 0,0 0),(1 1,1 2,2 1,1 1))')");
						$sql .= "$geo[field] = ST_GeometryFromText('Polygon($coords)'), ";
						// echo "ST_GeometryFromText('Polygon($coords)')" ."\n\n";
						// echo "sql1: $sql\n";
						// $sql = "INSERT INTO Studyarea SET geometry = ST_GeometryFromText('Polygon((0 0,0 3,3 0,0 0),(1 1,1 2,2 1,1 1))')";
						// echo "sql2: $sql\n";2
					}
					break;
				case 'polyline':
					$latlng = json_decode($geo['latlngs'],true);
					
					$coords = "";
					foreach($latlng as $k => $ll){
						
						$lat = $ll['lat'];
						$lng = $ll['lng'];
						if(is_numeric($lat) && is_numeric($lng)){
							if($coords != ""){
								$coords .= ", ";
							}

							$coords .= "$lng $lat";

						}	
					}
					if($coords != ""){
						$sql .= "$geo[field] = ST_GeometryFromText('LineString($coords)',4326), ";
					}
					break;
				
				default:
					# code...
					break;
			}
		}else{
			$sql .= "$geo[field] = ST_GeometryFromText('$geo[wkt]',4326), ";
			// print2($sql);
		}

	}


	$sql = trim($sql,', ');

	// echo "\n\nsql: $sql\n\n";

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
	
	if(!empty($post['geo'])){
		$geo = $post['geo'];
		if(empty($geo['wkt'])){
			switch ($geo['type']) {
				case 'marker':
					$latlng = json_decode($geo['latlngs'],true);
					$lat = $latlng['lat'];
					$lng = $latlng['lng'];
					if(is_numeric($lat) && is_numeric($lng)){
						$sql .= "$geo[field] = ST_GeometryFromText('Point($lng $lat)'), ";
					}
					break;
				case 'polygon':
					$latlngs = json_decode($geo['latlngs'],true);
					foreach ($latlngs as $latlng) {
						$coords = "(";
						$lat1 = "";
						foreach($latlng as $k => $ll){
							// print2($ll);
							$lat = $ll['lat'];
							$lng = $ll['lng'];
							if(is_numeric($lat) && is_numeric($lng)){
								if($coords == "("){
									$lat1 = $lat;
									$lng1 = $lng;
									// echo ")))((((";
								}

								$coords .= "$lng $lat, ";

							}	
						}
						$coords .= $lat1 != ""?"$lng1 $lat1, ":"";
						$coords = trim($coords,', ');
						$coords .= ")";

					}
					
					if($coords != ""){
			// $stmt = $db->query("INSERT INTO Studyarea SET geometry = ST_GeometryFromText('Polygon((0 0,0 3,3 0,0 0),(1 1,1 2,2 1,1 1))')");
						$sql .= "$geo[field] = ST_GeometryFromText('Polygon($coords)'), ";
						// echo "sql1: $sql\n";
						// $sql = "INSERT INTO Studyarea SET geometry = ST_GeometryFromText('Polygon((0 0,0 3,3 0,0 0),(1 1,1 2,2 1,1 1))')";
						// echo "sql2: $sql\n";2
					}
					break;
				case 'polyline':
					$latlng = json_decode($geo['latlngs'],true);
					
					$coords = "";
					foreach($latlng as $k => $ll){
						
						$lat = $ll['lat'];
						$lng = $ll['lng'];
						if(is_numeric($lat) && is_numeric($lng)){
							if($coords != ""){
								$coords .= ", ";
							}

							$coords .= "$lng $lat";

						}	
					}
					if($coords != ""){
						$sql .= "$geo[field] = ST_GeometryFromText('LineString($coords)'), ";
					}
					break;
				
				default:
					# code...
					break;
			}

		}else{
			$sql .= "$geo[field] = ST_GeometryFromText('$geo[wkt]',4326), ";
		}

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
	$post['datos'] = is_array($post['datos'])?$post['datos']:array();
	foreach ($post['datos'] as $k => $v) {
		if($k!='id'){
			$sql .= "$k = :$k, ";
		}
	}
	if(!empty($post['geo'])){
		$geo = $post['geo'];
		if(empty($geo['wkt'])){
			switch ($geo['type']) {
				case 'marker':
					$latlng = json_decode($geo['latlngs'],true);
					$lat = $latlng['lat'];
					$lng = $latlng['lng'];
					if(is_numeric($lat) && is_numeric($lng)){
						$sql .= "$geo[field] = ST_GeometryFromText('Point($lng $lat)'), ";
					}
					break;
				case 'polygon':
					$latlngs = json_decode($geo['latlngs'],true);
					// print2($latLngs);
					foreach ($latlngs as $latlng) {
						$coords = "(";
						$lat1 = "";
						foreach($latlng as $k => $ll){
							// print2($ll);
							$lat = $ll['lat'];
							$lng = $ll['lng'];
							if(is_numeric($lat) && is_numeric($lng)){
								if($coords == "("){
									$lat1 = $lat;
									$lng1 = $lng;
									// echo ")))((((";
								}

								$coords .= "$lng $lat, ";

							}	
						}
						$coords .= $lat1 != ""?"$lng1 $lat1, ":"";
						$coords = trim($coords,', ');
						$coords .= ")";

					}
					
					if($coords != ""){
			// $stmt = $db->query("INSERT INTO Studyarea SET geometry = ST_GeometryFromText('Polygon((0 0,0 3,3 0,0 0),(1 1,1 2,2 1,1 1))')");
						$sql .= "$geo[field] = ST_GeometryFromText('Polygon($coords)'), ";
						// echo "sql1: $sql\n";
						// $sql = "INSERT INTO Studyarea SET geometry = ST_GeometryFromText('Polygon((0 0,0 3,3 0,0 0),(1 1,1 2,2 1,1 1))')";
						// echo "sql2: $sql\n";2
					}
					break;
				case 'polyline':
					$latlng = json_decode($geo['latlngs'],true);
					
					$coords = "";
					foreach($latlng as $k => $ll){
						
						$lat = $ll['lat'];
						$lng = $ll['lng'];
						if(is_numeric($lat) && is_numeric($lng)){
							if($coords != ""){
								$coords .= ", ";
							}

							$coords .= "$lng $lat";

						}	
					}
					if($coords != ""){
						$sql .= "$geo[field] = ST_GeometryFromText('LineString($coords)'), ";
					}
					break;
				
				default:
					# code...
					break;
			}

		}else{
			$sql .= "$geo[field] = ST_GeometryFromText('$geo[wkt]',4326), ";
		}

	}

	$sql = trim($sql,', ');

	// echo "<br/> $sql <br/>";
	if(!isset($post['where'])){
		$sql .= ' WHERE id = :id';
	}else{
		$sql .= " WHERE $post[where]";
	}
	// echo "\n SAL: $sql \n";
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
		case 'questionnaires':
			$stmt = $db->prepare("SELECT u.*
				FROM Users u
				WHERE username = ?");
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
		if($acceso == 'admin'){
			$r['nombre'] = "$usrInf[nombre] $usrInf[aPat] $usrInf[aMat] ";
			$r['estatusId'] = $usrInf['estatus'];
			$r['nivel'] = $usrInf['nivel'];
			// print2($r['priv']);
		}elseif($acceso == 'questionnaires'){
			if($usrInf['validated'] == 1){
				$r['name'] = "$usrInf[name] $usrInf[lastname]";
				$r['validated'] = $usrInf['validated'];
			}else{
				$r['name'] = "";
				$r['validated'] = 0;
				$r['usrId'] = "";
			}
		}
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

function cumpleFiltros($filtros,$uId){
	$cumple = false;

	foreach ($filtros as $f) {
		
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
	$nivel = $_SESSION['CM']['admin']['nivel'];
	if($nivel<$nivelPerm){
		session_destroy();
		exit('No tienes acceso');
	}
}


function checaAccesoQuest(){
	session_start();
	$nivel = $_SESSION['CM']['questionnaires']['validated'];
	if($nivel != 1){
		exit('No tienes acceso');
	}
}

function checaAccesoConsult( $cId = 0 ){
	global $db;
	session_start();
	$usrId = $_SESSION['CM']['consultations']['usrId'];
	// print2('aaa');
	// print2($usrId);
	if(empty($usrId) && empty($cId)){
		exit('No tienes acceso');
	}

	if(!empty($cId)){
		if( !is_numeric($cId) ){
			exit('No tienes acceso');
		}
		$today = date('Y-m-d');

		$all = $db->query("SELECT c.* 
			FROM Consultations c
			WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
			AND initDate <= '$today' AND finishDate >= '$today'
			AND c.id = $cId
		")->fetchAll(PDO::FETCH_ASSOC);

		if(count($all) == 0){
			$now = $db->query("SELECT c.* 
				FROM Consultations c
				LEFT JOIN ConsultationsAudiencesCache cac ON cac.consultationsId = c.id
				LEFT JOIN UsersAudiences ua ON ua.dimensionesElemId = cac.dimensionesElemId AND ua.usersId = $usrId
				WHERE ua.id IS NOT NULL
				AND c.initDate <= '$today' AND c.finishDate >= '$today' AND c.id = $cId
				GROUP BY c.id
			")->fetchAll(PDO::FETCH_ASSOC);
			if(count($now) == 0){
				exit('No tienes acceso');
			}
		}
	}

}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}


function getLJTrgt($nivelMax,$padre,$elemId,$type='structure'){
	global $db;
	$numDim = $db->query("SELECT COUNT(*) FROM Dimensiones 
		WHERE elemId = $elemId AND type='$type' ")->fetchAll(PDO::FETCH_NUM)[0][0];

	$LJ = '';
	$nivelMax = isset($nivelMax)?$nivelMax:0;
	$padre = isset($padre)?$padre:0;
	for ($i=$nivelMax; $i <$numDim ; $i++) { 
		if($i == $nivelMax){
			$LJ .= " LEFT JOIN DimensionesElem de$i ON te.dimensionesElemId = de$i.id";
		}else{
			$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
		}
		if($i == $numDim - 2){
		}
		if($i == $numDim - 1){
			$fields = ", de$i.nombre as nombreHijo, de$i.id as idHijo";
			$wDE = " de$i.padre = $padre";
			// echo "I: $i";
		}
	}

	$return['LJ'] = $LJ;
	$return['wDE'] = $wDE;
	$return['fields'] = $fields;
	$return['numDim'] = $numDim;

	return $return;
}

function getTrackingVisitas($nivelMax,$padre,$targetsId,$chkId,$refDate){
	global $db;

	$minDate = "$refDate 00:00:00";
	// echo "MINDATE<br/>";
	// print2($minDate);


	$numDim = $db->query("SELECT COUNT(*) FROM Dimensiones 
		WHERE elemId = $targetsId AND type='structure' ")->fetchAll(PDO::FETCH_NUM)[0][0];

	$LJ = '';
	$nivelMax = isset($nivelMax)?$nivelMax:0;
	$padre = isset($padre)?$padre:0;

	if($nivelMax < $numDim){
		for ($i=$nivelMax; $i <$numDim ; $i++) {
			
			if($i == $nivelMax){
				$LJ .= " DimensionesElem de$i";
				// $LJ .= " LEFT JOIN DimensionesElem de$i ON te.dimensionesElemId = de$i.id";
			}else{
				$LJ .= " LEFT JOIN DimensionesElem de$i ON de$i.id = de".($i-1).".padre";
			}
			if($i == $numDim - 2){
			}
			if($i == $numDim - 1){
				$fields = " de$nivelMax.nombre as nombreHijo, de$nivelMax.id as idHijo";
				$wDE = " de$i.padre = $padre";
			}
		}

		$sql = "
			SELECT de$nivelMax.id as dimElemId, $fields, v.id as vId, v.finishDate, v.elemId
				FROM $LJ
				LEFT JOIN TargetsElems te ON te.dimensionesElemId = de$nivelMax.id
				LEFT JOIN Visitas v ON v.elemId = te.id AND v.type = 'trgt' AND v.checklistId = $chkId
					AND v.id = (SELECT id FROM Visitas z 
						WHERE z.elemId = v.elemId AND z.type='trgt' AND z.checklistId = $chkId
						AND finalizada = 1 AND finishDate >= '$minDate'
						ORDER BY z.finishDate DESC 
						LIMIT 1)
			WHERE $wDE
			ORDER BY de$nivelMax.id, v.finishDate DESC
		";
	}else{
		$sql = "
			SELECT de.id as dimElemId, v.id as vId, v.finishDate, v.elemId
				FROM DimensionesElem de 
				LEFT JOIN TargetsElems te ON te.dimensionesElemId = de.id
				LEFT JOIN Visitas v ON v.elemId = te.id AND v.type = 'trgt' AND v.checklistId = $chkId
					AND v.id = (SELECT id FROM Visitas z 
						WHERE z.elemId = v.elemId AND z.type='trgt' AND z.checklistId = $chkId
						AND finalizada = 1 AND finishDate >= '$minDate'
						ORDER BY z.finishDate DESC 
						LIMIT 1)
			WHERE de.id = $padre
			ORDER BY v.finishDate DESC
		";

	}


	// echo $sql."<br/>";
	$visElems = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
	// print2($visElems);
	$complete = true;
	$tot = 0;
	$noNum = 0;
	foreach ($visElems as $vis) {
		if( empty($vis[0]['vId']) ){
			$noNum++;
			$complete = false;
		}
		$tot++;
	}
	$noAvg = ($tot!=0?$noNum/$tot:1)*100;
	$noAvg = number_format($noAvg,0);
	$avg = 100-$noAvg;

	$resp['complete'] = $complete;
	$resp['refDate'] = $refDate;
	$resp['avg'] = $avg;
	$resp['tot'] = $tot;
	return $resp;
}

function getToken($request){
	$headers = $request->getHeaders();
	$auth = $headers['HTTP_AUTHORIZATION'][0];
	$token = substr($auth, 7);

	return $token;
}

function tokenVerif($token,$usrId){

	$cToken = '$2y$10$'.$token;
	$v = password_verify("UPT_$usrId"."_1",$cToken);

	// echo "\n\nToken: $cToken\n\nusrId : $usrId\n\n";
	// echo "V: $v";
	return $v;

}

function getRefDate($frequency){

	$today = date('Y-m-d');

	switch ($frequency) {
		case "daily":
			$minDate = date('Y-m-d', strtotime($today . ' -1 day'));
			break;
		case "weekly":
			$minDate = date('Y-m-d', strtotime($today . ' -1 week'));
			break;
		case "2weeks":
			$minDate = date('Y-m-d', strtotime($today . ' -2 week'));
			break;
		case "3weeks":
			$minDate = date('Y-m-d', strtotime($today . ' -3 week'));
			break;
		case "monthly":
			$minDate = date('Y-m-d', strtotime($today . ' -1 month'));
			break;
		case "2months":
			$minDate = date('Y-m-d', strtotime($today . ' -2 month'));
			break;
		case "3months":
			$minDate = date('Y-m-d', strtotime($today . ' -3 month'));
			break;
		case "4months":
			$minDate = date('Y-m-d', strtotime($today . ' -4 month'));
			break;
		case "6months":
			$minDate = date('Y-m-d', strtotime($today . ' -6 month'));
			break;
		case "yearly":
			$minDate = date('Y-m-d', strtotime($today . ' -1 year'));
			break;
		default:
			# code...
			break;
	}
	return $minDate;
}

function getNextDate($frequency,$date){

	$today = date('Y-m-d');
	// echo "freq: $frequency, date:$date<br/>";

	switch ($frequency) {
		case "oneTime":
			$nextDate = date('Y-m-d', strtotime($todayRep . ' +100 year'));
			break;
		case "daily":
			$nextDate = date('Y-m-d', strtotime($date . ' +1 day'));
			break;
		case "weekly":
			$nextDate = date('Y-m-d', strtotime($date . ' +1 week'));
			break;
		case "2weeks":
			$nextDate = date('Y-m-d', strtotime($date . ' +2 week'));
			break;
		case "3weeks":
			$nextDate = date('Y-m-d', strtotime($date . ' +3 week'));
			break;
		case "monthly":
			$nextDate = date('Y-m-d', strtotime($date . ' +1 month'));
			break;
		case "2months":
			$nextDate = date('Y-m-d', strtotime($date . ' +2 month'));
			break;
		case "3months":
			$nextDate = date('Y-m-d', strtotime($date . ' +3 month'));
			break;
		case "4months":
			$nextDate = date('Y-m-d', strtotime($date . ' +4 month'));
			break;
		case "6months":
			$nextDate = date('Y-m-d', strtotime($date . ' +6 month'));
			break;
		case "yearly":
			$nextDate = date('Y-m-d', strtotime($date . ' +1 year'));
			break;
		default:
			# code...
			break;
	}
	return $nextDate;
}


function delDirContent($path,$type){
	try {
		$files = glob($path . '/*');
		foreach($files as $file){
			if($type == 'dirs' || $type == 'all'){
			    if(is_dir($file)){
			 		deleteDirectory($file);   	
			    }
			}
			if($type == 'files' || $type == 'all'){
				if(is_file($file)){
				    unlink($file);
				}
			}
		}
	} catch (Exception $e) {
		return $e;
	}
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

function getOffspring($dimElemId,&$arr){
	global $db;

	$childs = $db->query("SELECT * FROM DimensionesElem WHERE padre = $dimElemId")->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($childs) == 0){
		// echo "\naquí\n";
		return $arr;
	}else{
		foreach ($childs as $c) {
			$arr[] = $c;
			getOffspring($c['id'],$arr);
		}
	}

	return $arr;

}






