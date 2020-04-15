<?php
/**   
🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪
🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪notLand code🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪
🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪🐪
*/


	if($_POST['pwd']){
		$pwd = $_POST['pwd'];
		echo verificaValido($pwd);
	}


	function verificaValido($pwd){

		$longitudMin = 8;
		$longitudMax = 60;
		$minusculas = true;
		$mayusculas = true;
		$numeros    = true;
		$simbolos   = false;
		$sinConsecutivos = true;


		$errors=[];

		if( strlen($pwd) < $longitudMin) {
			$errors[] = "La contraseña es muy corta, debe tener al menos $longitudMin caracteres";
		}

		if( strlen($pwd) > $longitudMax ) {
			$errors[] = "La contraseña es muy larga, debe tener a lo más $longitudMax caracteres";
		}

		if($numeros && !preg_match("#[0-9]+#", $pwd) ) {
			$errors[] = "La contraseña debe contener al menos un número [ 0 - 9 ]";
		}

		if($minusculas && !preg_match("#[a-z]+#", $pwd) ) {
			$errors[] = "La contraseña debe contener al menos una minúscula";
		}

		if($mayusculas && !preg_match("#[A-Z]+#", $pwd) ) {
			$errors[] = "La contraseña debe contener al menos una MAYÚSCULA"; 
		}

		if($simbolos && !preg_match("#\W+#", $pwd) ) {
			$errors[] = "La contraseña debe contener al menos un símbolo";
		}

		if($sinConsecutivos && !nonConsecutiveCheck($pwd)) {
			$errors[] = "La contraseña no debe tener más de dos símbolos consecutivos o iguales seguidos, ejemplos a evitar '1234' o 'aaaa'";
		}


		if(count($errors) > 0){
			return '{"ok":"0", "message" : "La contraseña no pasa la validación de seguridad mínima", "errores": '.json_encode($errors).'}';
		} else {
			return '{"ok":"1"}';
		}

	}

	// echo "<br>";

	// nonConsecutiveCheck("01asdlkfhqwer,mznxcv.poi2444567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

	function nonConsecutiveCheck($string){ 
		$lleva = 0; 
		$anterior = null;
		$tmp == null;

		// echo $string."<br>";

		for ($n=0;$n<strlen($string);$n++){ 
			$tmp = ord($string[$n]);
			if($tmp == $anterior || $tmp == ($anterior+1)){
				if(++$lleva > 1){
					// echo "salimos en : ".$string[$n];
					return false;
				}
			}
			else
				$lleva=0;

			// echo $string[$n].", $anterior -> ".ord($string[$n])."   [$lleva] <br>";
			
			$anterior = $tmp;

		}
		return true; 
	}



?>