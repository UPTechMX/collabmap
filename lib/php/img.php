<?php

if(!function_exists('raiz')){
	include '../j/j.func.php';
}

$contents=  file_get_contents($rz.$_POST['img']);

header("Content-Type: $_POST[contentType]");
header("Content-Length: " . strlen($contents));
header("Cache-Control: public", true);
header("Pragma: public", true);

echo $contents;
exit;

?>