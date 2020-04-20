<?php

///// LIBRERIAS PHP
// include_once '../lib/J/J.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>INECC Login</title>

<!-- LIBRERIAS CSS -->
<!-- <link href="<?php echo aRaiz(); ?>lib/css/oneColLiqCtrHdr.css" rel="stylesheet" type="text/css" /> -->
<link href="<?php echo aRaiz(); ?>lib/j/j.css" rel="stylesheet" type="text/css" />
<!-- <link href="<?php echo aRaiz(); ?>lib/D/D.css" rel="stylesheet" type="text/css" /> -->
<script src="<?php echo aRaiz(); ?>lib/js/jquery-2.1.3.js"></script>

<!-- <script src="lib/D/D.js"></script> -->

</head>

<body>
	<div class="container">
		<div class="header">
			<div style="position:relative;border:solid 0px">
				<div style = "height:auto; overflow: hidden;" align = "center">
					<a href="#" style="padding:20px"><img src="./seguridad/img/logoAME.png" alt="Insert Logo Here" name="Insert_logo" height="100px" id="Insert_logo" style=" display:block;" /></a>
				</div>
			</div>
		</div>
		<div class="content" id="contenedor">
			<div>
				<div class="contenedor" id="contenedorLista">
					<div class="principal" id="metas">
						<div class="contLog">
							<div class="loginSAG">
								<img src="<?php echo aRaiz(); ?>seguridad/img/sagarpa.png" height="45px"/>
							</div>
						
							<div class="loginImg">
								<img src="<?php echo aRaiz(); ?>seguridad/img/login.png" width="100%" style="border-bottom-left-radius:13px;border-bottom-right-radius:13px;opacity:0.9;"/>
								<div class="textoLog">PROTOCOLOS</div>
							</div>
						</div>
					</div>
					<div class="divDerecha" id="informacion">
						<form id="form1" name="form1" method="post" action="<?php $_SERVER['PHP_SELF'] ?>?">
							<div align="center" style="border:none;padding:20px 10px 20px 10px;background:#f2e4d9;width:88%;border-radius:10px;position:relative;">
								<div style="border:none 1px;text-align:left;margin-left:10px;margin-bottom:10px">Usuario:<br/><input type="text" class="textAME" name="usuario" id="usuario" style="height:16px;width:90%;margin-top:5px"/></div>
								<div style="border:none 1px;text-align:left;margin-left:10px;margin-bottom:10px">Contraseña:<br/><input type="password" class="textAME" name="pwd" id="pwd"  style="height:16px;width:90%;margin-top:5px"/></div>
								<div style="border:none 1px;text-align:left;margin-left:10px;margin-bottom:10px;text-align:center"><input type="submit" name="button" id="button" value="Entrar" class="botonAME" style="width:70px"/></div>
								<?php
									if($_POST['usuario'] != "" || $_POST['pwd']) {
										echo '<p style="color:red;font-size:small">Contraseña incorrecta</p>';
									}
								?>
							</div>
						</form>
						
					</div>
				</div>
				
			</div>			
		</div>
		<div class="footer"><?php include_once 'layout/footer.php'; ?></div>
	</div>
</body>
</html>
  
