<?php

	if(!function_exists('raiz')){
		include_once '../../../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Projects

	$xml = simplexml_load_file(raiz().'externalFiles/'.$_POST['file']);

	$existe = $xml->Document->Schema;
	$childs = $xml->Document->Schema->children();
	
	$attrs = array();
	$childs = !empty($existe)?$childs:array();
	foreach ($childs as $c) {
		$tmp = array();
		$tmp['nom'] = $c['name']->__toString();
		$tmp['clase'] = $c['type']->__toString();
		$tmp['val'] = $c['name']->__toString();
		$attrs[] = $tmp;
		// echo "-----\n";
		// echo $c['name']->__toString()."\n";
		// echo $c['type']->__toString()."\n";
		// echo "-----\n";
	}

	echo atj($attrs);



?>