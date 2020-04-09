<?php
	include_once '../../lib/j/j.func.php';

	session_start();
	include raiz()."lib/php/captcha/simple-php-captcha.php";
	$_SESSION['captcha'] = simple_php_captcha();
	// print2($_SESSION['captcha']);
	$img = end(explode("/",$_SESSION['captcha']['image_src']));
	// print2($img);
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


		$('#pwd, #pwd2').keyup(function(event) {
			if($('#pwd').val() != ''){
				
				if( $('#pwd2').val() != $('#pwd').val() ){
					$('#pwdChk').show().removeClass('glyphicon-thumbs-up').addClass('glyphicon-thumbs-down');
				}else{
					$('#pwdChk').show().addClass('glyphicon-thumbs-up').removeClass('glyphicon-thumbs-down');
				}
				$('#pwd, #pwd2').css({backgroundColor:'white'});
			}
		});

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		$('#username').blur(function(event) {
			var dat = $('#nvoShopper').serializeObject();

			var rj = jsonF('campo/json/buscUsr.php',{username:dat.username});
			// console.log(rj);
			var r = $.parseJSON(rj);

			if(r.cuenta != 0){
				alertar('El usuario que intentas ingresar ya existe en la base de datos',function(e){},{});
				allOk = false;
			}

		});

		$('#email').blur(function(event) {
			var dat = $('#nvoShopper').serializeObject();

			var rj = jsonF('campo/json/buscMail.php',{email:dat.email});
			// console.log(rj);
			var r = $.parseJSON(rj);

			if(r.cuenta != 0){
				alertar('El e-mail que intentas ingresar ya existe en la base de datos',function(e){},{});
				allOk = false;
			}
			if(!validateEmail(dat.email) && dat.email != ''){
				alertar('El e-mail que intentas ingresar no tiene estructura de un mail válido "usuario@dominio.com"',function(e){},{});
				allOk = false;

			}

		});

		$('#newCaptcha').click(function(event) {
			var ncj = jsonF('campo/json/newCaptcha.php',{});
			// console.log(ncj);
			var nc = $.parseJSON(ncj);
			// console.log(nc);
			$('#captcha').attr('src','../../lib/php/captcha/'+nc.img);
		});

		$('#registrar').click(function(event) {
			var dat = $('#nvoShopper').serializeObject();
			var allOk = camposObligatorios('#nvoShopper');
			// console.log(dat);

			var rj = jsonF('campo/json/buscUsr.php',{username:dat.username});
			var r = $.parseJSON(rj);
			if(r.cuenta != 0){
				alertar('El usuario que intentas ingresar ya existe en la base de datos',function(e){},{});
				$('#username').val('').css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

			var rj = jsonF('campo/json/buscMail.php',{email:dat.email});
			var r = $.parseJSON(rj);
			if(r.cuenta != 0){
				alertar('El e-mail que intentas ingresar ya existe en la base de datos',function(e){},{});
				$('#email').val('').css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

			if( $('#pwd2').val() != $('#pwd').val() ){
				allOk = false;
				$('#pwd, #pwd2').css({backgroundColor:'rgba(255,0,0,.5)'});
			}

			if($('#pwd').val().length < 6 && allOk){
				alertar('La contraseña debe contener al menos 6 caracteres',function(e){},{});
				allOk = false;
			}


			var ccj = jsonF('campo/json/chkCaptcha.php',{captcha:$('#cp').val()});
			var cc = $.parseJSON(ccj);
			if(cc.ok != 1 && allOk){
				alertar('El captcha no coincide',function(e){},{});
				allOk = false;
			}

			if(!$('#avisoPriv').is(':checked')){
				allOk = false;
			}



			if(allOk){
				var rj = jsonF('campo/json/json.php',{datos:dat,acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#forma').remove();
					$('#finalizado').load(rz+'campo/registro/finalizado.php',{dat});
				}
			}
		});
	});
</script>

<body>
	<div class="container">
		<div class="header">
		</div>
		<img src="<?php echo aRaiz(); ?>img/marquesina.png"  style="width:100%;" >
		<div class="content">
			<div class="row" id="forma">
				<div class="col-md-4 col-md-offset-4">
				<div class="nuevo">Registro de nuevo shopper al sistema</div>
					<form id="nvoShopper">
						<table class="">
							<tr height="40px">
								<td>Username:</td>
								<td>&nbsp;</td>
								<td><input type="text" class="form-control oblig" name="username" id="username"></td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Nombre:</td>
								<td>&nbsp;</td>
								<td><input type="text" class="form-control oblig" name="nombre" id="nombre" ></td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Apellido paterno:</td>
								<td>&nbsp;</td>
								<td><input type="text" class="form-control oblig" name="aPat" id="aPat" ></td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Apellido materno:</td>
								<td>&nbsp;</td>
								<td><input type="text" class="form-control oblig" name="aMat" id="aMat" ></td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Género:</td>
								<td>&nbsp;</td>
								<td>
									<select class="form-control oblig" name="genero" id="genero">
										<option value="H">Hombre</option>
										<option value="M">Mujer</option>
									</select>
								</td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Correo electrónico:</td>
								<td>&nbsp;</td>
								<td><input type="text" class="form-control oblig" name="email" id="email" ></td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Contraseña:</td>
								<td>&nbsp;</td>
								<td><input type="password" class="form-control oblig" name="pwd" id="pwd" ></td>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr height="40px">
								<td>Repetir contraseña:</td>
								<td>&nbsp;</td>
								<td><input type="password" class="form-control oblig" id="pwd2"></td>
								<td>&nbsp;</td>
								<td><i class="glyphicon" id="pwdChk"></i></td>
							</tr>
							<tr height="40px">
								<td colspan="5">
									<input type="checkbox" id="avisoPriv"/>
									<span style="font-size: x-small;">
										He leído y estoy de acuerdo con el 
										<strong><a href="aviso.php" target="_blank">avíso de privacidad</a></strong> 
										de Shoppers Consulting S.C.
									</span>
								</td>
							</tr>
							<tr height="40px">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<img src="../../lib/php/captcha/<?php echo $img; ?>" id="captcha">
								</td>
								<td>&nbsp;</td>
								<td>
									Ingresa los caracteres de la imágen <i class="glyphicon glyphicon-refresh manita" id="newCaptcha"></i>
									<input type="text" class="form-control oblig" id="cp">
								</td>
								<td>&nbsp;</td>
								<td></td>

							</tr>
							<tr height="40px">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>

							<tr height="40px">
								<td colspan="5">
									<div style="text-align: right;">
										<span class="btn btn-shop" id="registrar">Registrar</span>
									</div>
								</td>
							</tr>
						</table>

					</form>
				</div>
				<div class="col-md-8" style="padding:35px 10px 20px 10px;">
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-md-offset-4" id="finalizado"></div>
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