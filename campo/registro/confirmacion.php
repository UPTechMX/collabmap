<?php
	include_once '../../lib/j/j.func.php';

	session_start();
	// print2($_GET);

	$verif = $db->prepare("SELECT * FROM Shoppers WHERE email = ? AND hashConf = ?");
	$arrVerif = [$_GET['em'],$_GET['h']];
	$verif -> execute($arrVerif);
	$usuario = $verif -> fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($usuario);

	if (!empty($usuario) && $usuario['confirmado'] == ''){
		$db->query("UPDATE Shoppers SET confirmado = 1, estatus = 1, registro = NOW() WHERE id = $usuario[id]");
	}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>ShoppersConsulting</title>
	<!-- LIBRERIAS CSS -->
	<link href="<?php echo aRaiz(); ?>lib/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaiz(); ?>lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaiz(); ?>seguridad/loginCSS.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaiz(); ?>lib/css/general.css" rel="stylesheet" type="text/css" />
	
	
	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="<?php echo aRaiz(); ?>lib/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo aRaiz(); ?>lib/js/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo aRaiz(); ?>lib/j/registro.js"></script>

</head>

<script type="text/javascript">
	$(function() {

	});
</script>

<body>
	<div class="container">
		<div class="header">
		</div>
		<img src="<?php echo aRaiz(); ?>img/marquesina.png"  style="width:100%;" >
		<div class="content">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<?php if (!empty($usuario) && $usuario['confirmado'] == ''){ ?>
						<div class="nuevo">Activación de cuenta</div>
						<strong>La cuenta ha sido activada de manera exitosa.</strong><br/>
						Ingresa al <strong><a href="../">sitio de inicio</a></strong> para ingresar al sistema

					<?php }else{ ?>
						<div class="nuevo">Ha habido un error</div>
						Este usuario no existe o ya fue activado anteriormente. <br/>
						Ponte en contacto con el personal de Shoppers consulting para resolver el problema.
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="footer"><?php include aRaiz().'admin/layout/footer.php'; ?></div>
	</div>
	<div class="modal fade" id="alertas" role="dialog" style="">
		<div id="modal" class="modal-dialog">
			<div class="modal-content" style="border-radius: 0px;" id="alertasCont">
				Cargando...
			</div>
		</div>
	</div>

</body>
</html>