<?php

class EvalMath {

	var $suppress_errors = false;
	var $last_error = null;
	var $varFalta = null;

    var $v = array('e'=>2.71,'pi'=>3.14); // variables y constantes
    var $f = array(); // funciones de usuario
    var $vb = array('e', 'pi'); // constantes
    var $fb = array(  // funciones internas
    	'sin','sinh','arcsin','asin','arcsinh','asinh',
    	'cos','cosh','arccos','acos','arccosh','acosh',
    	'tan','tanh','arctan','atan','arctanh','atanh',
    	'sqrt','abs','ln','log');

    function EvalMath() {
      // mejoras pi y e
    	$this->v['pi'] = pi();
    	$this->v['e'] = exp(1);
    }


    /* Funciones abreviadas por si acaso */

    function e($expr) {
    	return $this->evaluate($expr);
    }

    function evalua($expr) {
    	return $this->evaluate($expr);
    }

    // regresa la variable que faltó en la útlima evaluación, si es el caso, y regresa varFalta a null
    function getVarFalta() {
    	$tmp = $this->varFalta;
    	$this->varFalta = null;
    	return $tmp;
    }

    /* Funcion para agregar variables directamente */

    function agregaVar($expr) {
         echo '<br>agregando con expresion: '.$expr; 

    	if (preg_match('/^\s*(\w+)\s*=\s*(.+)$/', trim($expr), $matches)) {
    		// prr($matches);
    		// echo "<br>".$matches[0]."--------AWRT";

    		if($matches[2] < 1)
    			$matches[2] = rtrim(sprintf('%.20f', $matches[2], '0'));

        //                      echo "<br> Zona agregar  Variable  <br>";
    		// checar que sea buena
    		if (($tmp = $this->pfx($this->nfx($matches[2]))) === false) { 
    			echo "Variable mal formada.";
    			return false;
    		}
	        // agregarla a las variables
    		$this->v[$matches[1]] = $tmp; 
	        // regresar el valor
    		return $this->v[$matches[1]]; 
    	} else {
    		echo "<br>No es una asignación correcta de variable: [".$expr."]";
    		$this->trigger("No es una asignación correcta de variable:'".$expr."'");
    		return false;
    	}

    }


	/* funcion para eliminar variables */

	function quitaVar($quita) {
	// elimina la variable
		unset($this->v[$quita]); 
	}


	function evaluate($expr) {
      	// echo "<br> Zona Evaluacion con '$expr' <br>";
		$this->last_error = null;
		$expr = trim($expr);
      	if (substr($expr, -1, 1) == ';') $expr = substr($expr, 0, strlen($expr)-1); // quitar puntos y coma si los hay
      	//===============
      	// si es asignacion de variable
      	if (preg_match('/^\s*(\w+)\s*=\s*(.+)$/', $expr, $matches)) { 
      		// echo "<br> Zona Variable  <br>";
           	if (in_array($matches[1], $this->vb)) { // esto evita asignar a constantes establecidas
           		return $this->trigger("no se puede asignar a constante ya establecida '$matches[1]'");
           	}
           	if (($tmp = $this->pfx($this->nfx($matches[2]))) === false) return false; // checar que el resultado este bien
           	$this->v[$matches[1]] = $tmp; // se mete al array de variables
           	return $this->v[$matches[1]]; // regresa el valor
           	//===============
           	// si es funcion
           } 
           elseif (preg_match('/^\s*([a-zA-Z]\w*)\s*\(\s*([a-zA-Z]\w*(?:\s*,\s*[a-zA-Z]\w*)*)\s*\)\s*=\s*(.+)$/', $expr, $matches)) {
           	// echo "<br> Zona Funcion  <br>";
           	// saca nombre
           	$fnn = $matches[1]; 
           	// que no sea interna
           	if (in_array($matches[1], $this->fb)) { 
           		return $this->trigger("no se pueden redefinir funciones preestablecidas: '$matches[1]()'");
           	}
           	// obtener argumentos
           	$args = explode(",", preg_replace("/\s+/", "", $matches[2]));
           	// ver si se convierte a postfix 
           	if (($stack = $this->nfx($matches[3])) === false) return false; 
           	for ($i = 0; $i<count($stack); $i++) { 
           		$token = $stack[$i];
           		if (preg_match('/^[a-zA-Z]\w*$/', $token) and !in_array($token, $args)) {
           			if (array_key_exists($token, $this->v)) {
           				$stack[$i] = $this->v[$token];
           			} else {
           				return $this->trigger("variable '$token' no definida en la declaracion de funcion");
           			}
           		}
           	}
           	$this->f[$fnn] = array('args'=>$args, 'func'=>$stack);
           	return true;
	        //===============
           } else {
           	// echo "<br> Zona Eval Directa  <br>";
	    	// a evaluar directo
	    	return $this->pfx($this->nfx($expr)); 
	    }
	}

	function varsText() {
		return '<br>Variables establecidas en el evaluador: '.print_r($this->vars(), true).'<br>';
	}

	function vars() {
		$output = $this->v;
		unset($output['pi']);
		unset($output['e']);
		return $output;
	}

	function funcs() {
		$output = array();
		foreach ($this->f as $fnn=>$dat)
			$output[] = $fnn . '(' . implode(',', $dat['args']) . ')';
		return $output;
	}

    //===================== Metoditos Internos ====================\\

    // Convierte de infijo a postfijo para evaluar
	function nfx($expr) {

		$expr = str_replace(' ','',$expr); 

      //echo "<br> Zona notacion Infija  <br>";
		$index = 0;
		$stack = new EvalMathStack;
	    $output = array(); // expresion postfija que se pasara pfx
	      //	$expr = trim($expr);

	    $ops   = array('+', '-', '*', '/', '^', '_');
	    $ops_r = array('+'=>0,'-'=>0,'*'=>0,'/'=>0,'^'=>1); // asociativos a la derecha  
	    $ops_p = array('+'=>0,'-'=>0,'*'=>1,'/'=>1,'_'=>1,'^'=>2); // precedencias

	    $expecting_op = false; 
      
		// checar que esten bien los caracteres
	    if (preg_match("/[^\w\s+*^\/()\.,-]/", $expr, $matches)) { 
	    	echo "<br><strong>expresion: '$expr' - Caracter no valido '{$matches[0]}'</strong>";
	    	return $this->trigger("expresion: '$expr' - Caracter no valido '{$matches[0]}'");
	    }

	    while(1) {
	        $op = substr($expr, $index, 1); // obten primer caracter
	        $ex = preg_match('/^([a-zA-Z]\w*\(?|\d+(?:\.\d*)?|\.\d+|\()/', substr($expr, $index), $match);
	        //===============
	        // es negativo o un menos?
	        	if ($op == '-' and !$expecting_op) { 
        
	        	// meter negativo al stack
	        		$stack->push('_'); 
	        		$index++;
	        	} elseif ($op == '_') { 
	        // no se permite en la expresion, es nuestro negativo
	        		return $this->trigger("caracter ilegal '_'"); 
					//===============
				// es un operador para el stack?
	        	} elseif ((in_array($op, $ops) or $ex) and $expecting_op) { 
	        	// esperando operador pero cae numero o variable o... o parentesis abre
	        		if ($ex) { 
	        		 // es multiplicacion (implicita)
	        			$op = '*'; $index--;
	        		}
				// a operar
	        	while($stack->count > 0 and ($o2 = $stack->last()) and in_array($o2, $ops) and ($ops_r[$op] ? $ops_p[$op] < $ops_p[$o2] : $ops_p[$op] <= $ops_p[$o2])) {
	            // pop del stack al output
	        		$output[] = $stack->pop(); 
	        	}
	        	// agregamos nuestro operador al stack
	        	$stack->push($op); 
	        	$index++;
	        	$expecting_op = false;
				//===============
				// podemos cerrar el parentesis?
	        } elseif ($op == ')' and $expecting_op) { 
	        	// sacamos hasta el ultimo (
	        	while (($o2 = $stack->pop()) != '(') { 
	        		if (is_null($o2)) return $this->trigger("parentesis ')' no esperado");
	        		else $output[] = $o2;
	        	}
	        	// cerrando funcion?

	        	if (preg_match("/^([a-zA-Z]\w*)\($/", $stack->last(2), $matches)) { 
	        		// obtiene nombre de funcion
	        		$fnn = $matches[1]; 
	        		// sacamos el numero de args
	        		$arg_count = $stack->pop(); 
	        		// sacamos del stack y metemos al output
	        		$output[] = $stack->pop(); 
	        		// checar cuenta de argumentos
	        		if (in_array($fnn, $this->fb)) { 
	        			if($arg_count > 1)
	        				return $this->trigger("demasiados argumentos (se dieron mas de 1)");
	        		} elseif (array_key_exists($fnn, $this->f)) {
	        			if ($arg_count != count($this->f[$fnn]['args']))
	        				return $this->trigger("numero incorrecto de argumentos (se dieron " . count($this->f[$fnn]['args']) . " expected)");
	            } else { 
	            	// metimos algo que no era funcion, mala definicion
	            	return $this->trigger("Error interno 112: Func mal definida.");
	            }
	        }
	        $index++;
	        //===============
	        // acabo el argumento de la funcion
	    } elseif ($op == ',' and $expecting_op) { 
	    	while (($o2 = $stack->pop()) != '(') { 
	            if (is_null($o2)) return $this->trigger("No se esperaba una coma ','"); // no hubo (
	            else $output[] = $o2; // sacamos la expresion y la metemos al output
	        }
			// checar si es funcion
	        if (!preg_match("/^([a-zA-Z]\w*)\($/", $stack->last(2), $matches))
	        	return $this->trigger("No se esperaba una ','");
	        	// incrementa num de args
	        	$stack->push($stack->pop()+1); 
	        	// ponemos el ( para usarlo despues
	        	$stack->push('('); 
	        		$index++;
	        		$expecting_op = false;
					//===============
	        	} elseif ($op == '(' and !$expecting_op) {
	          $stack->push('('); // 
	          	$index++;
	          	$allow_neg = true;
	          //===============
	        } elseif ($ex and !$expecting_op) { 
	        // tenemos una func, var, numero?
	        	$expecting_op = true;
	        	$val = $match[1];

	            if (preg_match("/^([a-zA-Z]\w*)\($/", $val, $matches)) { 
	            // es o func o var con mult implicita
	            	if (in_array($matches[1], $this->fb) or array_key_exists($matches[1], $this->f)) { 
						// es func
	            		$stack->push($val);
	            		$stack->push(1);
	            		$stack->push('(');
	            			$expecting_op = false;
	            		} else { 
	                    // es var con mult.
	            			$val = $matches[1];
	            			$output[] = $val;
	            		}
	            	} else { 
						// es var normal o numero
	            		$output[] = $val;
	            	}
	            	$index += strlen($val);
	                                           //===============
	            } elseif ($op == ')') { 
	            // checamos tonterias
	            	return $this->trigger("no se esperaba un  ')'");
	            } elseif (in_array($op, $ops) and !$expecting_op) {
	            	return $this->trigger("no se esperaba el operador '$op'");
	        } else { // otros errores raros
	        	return $this->trigger("64: ocurrio un error inesperado '$expr' -409--"); 
	        }
	        if ($index == strlen($expr)) {
	          if (in_array($op, $ops)) { // terminamos con operador.
	          	return $this->trigger("operador '$op' no tiene operando");
	          } else {
	          	break;
	          }
	      }
	        while (substr($expr, $index, 1) == ' ') { // quitamos espacio en blanco
	        	$index++;                             
	        }

	    } 
	    while (!is_null($op = $stack->pop())) { 
	    // sacar todo del stack y meterlo al output
	    	if ($op == '(') return $this->trigger("esperabamos un ')'"); 
		    // si quedan (s en el stack estan mal balanceados
	    	$output[] = $op;
	    }
	    return $output;
	}



    // evalua postfijo
	function pfx($tokens, $vars = array()) {

		if ($tokens == false) return false;

		$stack = new EvalMathStack;

		foreach ($tokens as $token) { 
        // si binario, sacar dos valores y meter el resultado
        //echo $token;
			if (in_array($token, array('+', '-', '*', '/', '^'))) {
				if (is_null($op2 = $stack->pop())) return $this->trigger("error interno");
				if (is_null($op1 = $stack->pop())) return $this->trigger("error interno");
				switch ($token) {
					case '+':
					$stack->push($op1+$op2); break;
					case '-':
					$stack->push($op1-$op2); break;
					case '*':
					$stack->push($op1*$op2); break;
					case '/':
					if ($op2 == 0) return $this->trigger("division por cero");
					$stack->push($op1/$op2); break;
					case '^':
					$stack->push(pow($op1, $op2)); break;
				}
          // si es unario sacamos uno, operamos y lo metemos de vuelta
			} elseif ($token == "_") {
				$stack->push(-1*$stack->pop());
          // si es funcion, saca args, daselos a la func y metemos resultado
        } elseif (preg_match("/^([a-zA-Z]\w*)\($/", $token, $matches)) { // es funcion
        	$fnn = $matches[1];
        	if (in_array($fnn, $this->fb)) { 
        		// funcion 'interna'
        		if (is_null($op1 = $stack->pop())) return $this->trigger("error interno 113");
        		$fnn = preg_replace("/^arc/", "a", $fnn); 
        		if ($fnn == 'ln') $fnn = 'log';
        		eval('$stack->push(' . $fnn . '($op1));');
        	} elseif (array_key_exists($fnn, $this->f)) {
        		// funcion de usuario
        		// get args

        		$args = array();
        		for ($i = count($this->f[$fnn]['args'])-1; $i >= 0; $i--) {
        			if (is_null($args[$this->f[$fnn]['args'][$i]] = $stack->pop())) return $this->trigger("error interno");
        		}
        		// recursivo
        		$stack->push($this->pfx($this->f[$fnn]['func'], $args)); 
        	}
			// si es variable o num va al stack
        } else {
        	if (is_numeric($token)) {
        		$stack->push($token);
        	} elseif (array_key_exists($token, $this->v)) {
        		$stack->push($this->v[$token]);
        	} elseif (array_key_exists($token, $vars)) {
        		$stack->push($vars[$token]);
        	} else {
        		$this->varFalta = $token;
        		return $this->trigger("Variable '$token' no definida en el estado actual del evaluador (ocurre también si se tienen más de una operación matricial en una misma función)...");
        	}
        }
    }
      // no mas tokens, deberia estar el resultado final nada mas
    if ($stack->count != 1) 
    	return $this->trigger("error interno 115");
    return $stack->pop();
	}

    // para mandar los errores
	function trigger($msg) {
		$this->last_error = $msg;
		if (!$this->suppress_errors) 
			trigger_error($msg, E_USER_WARNING);
		return 'error';
	}
}


  // clase interna - stack
class EvalMathStack {

	var $stack = array();
	var $count = 0;

	function push($val) {
		$this->stack[$this->count] = $val;
		$this->count++;
	}

	function pop() {
		if ($this->count > 0) {
			$this->count--;
			return $this->stack[$this->count];
		}
		return null;
	}

	function numero() {
		return $this->count;
	}

	function prrstack() {
		echo prr($this->$stack);
	}

	function last($n=1) {
		// echo "<br>STACK LAST: ".$this->count." , ".$n;
		if($this->count - $n < 0)
			return null;
		return $this->stack[$this->count-$n];
	}
}
