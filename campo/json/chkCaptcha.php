<?php

	session_start();
	include_once '../../lib/j/j.func.php';

	if($_SESSION['captcha']['code'] == $_POST['captcha']){
		echo '{"ok":"1"}';
	}else{
		echo '{"ok":"0"}';
	}


?>