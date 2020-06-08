<?php

	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	$about = $db->query("SELECT * FROM General WHERE name = 'about' ")->fetchAll(PDO::FETCH_ASSOC)[0];



?>

<div style="color:#2a6bd5; text-align: left;">
	<div class="consultationName" style="font-size: 2em;font-weight: bold;text-align: left;">
		<?php echo TR('about'); ?>
	</div>
</div>

<div style="margin-top: 20px;text-align: justify;"><?php echo $about['texto']; ?></div>