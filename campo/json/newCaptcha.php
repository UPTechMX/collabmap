<?php

	session_start();
	include_once '../../lib/j/j.func.php';

	include raiz()."lib/php/captcha/simple-php-captcha.php";
	$_SESSION['captcha'] = simple_php_captcha();
	// print2($_SESSION['captcha']);
	$img = end(explode("/",$_SESSION['captcha']['image_src']));

	echo '{"img":"'.$img.'"}';

?>