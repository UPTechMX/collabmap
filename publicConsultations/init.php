<?php

	$stmt = $db->prepare("SELECT * FROM PublicConsultations WHERE code = ?");
	$stmt -> execute([$_GET['pc']]);

	$pcInfo = $stmt ->fetchAll(PDO::FETCH_ASSOC)[0];


?>


<h1 class="azul"><?php echo $pcInfo['name']; ?></h1 class="azul">
<div class="negro"><?php echo $pcInfo['description']; ?></div>
<div id="register"><?php include 'register.php'; ?></div>