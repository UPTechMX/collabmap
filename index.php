<?php
	$location = 'root';

	if(isset($_GET['pc'])){
		include_once 'publicConsultations/index.php';
	}else{
		include_once 'consultations/index.php';
	}

?>