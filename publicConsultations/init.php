<?php

	$stmt = $db->prepare("SELECT * FROM PublicConsultations WHERE code = ?");
	$stmt -> execute([$_GET['pc']]);

	$pcInfo = $stmt ->fetchAll(PDO::FETCH_ASSOC)[0];


?>


<div class="nuevo"><?php echo $pcInfo['name']; ?></div>
<div><?php echo $pcInfo['description']; ?></div>
<div id="register"><?php include 'register.php'; ?></div>