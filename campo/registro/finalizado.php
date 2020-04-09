<?php

	include_once '../../lib/j/j.func.php';

	// print2($_POST);
	$dat = $_POST['dat'];
?>

<div class="nuevo">
	Registro finalizado
</div>
<div style="text-align: justify;">
	Gracias por registrarte <strong><?php echo "$dat[nombre] $dat[aPat] $dat[aMat]" ?></strong>.<br/>
	Será enviado un correo a <strong><?php echo $dat['email'] ?></strong> el cual contendrá 
	una liga para finalizar tu registro y activar tu cuenta.
	<br/>
	<br/>
	Es posible que el correo se haya envíado a la carpeta de spam.
</div>