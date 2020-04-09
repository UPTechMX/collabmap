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



			if(allOk){
				var rj = jsonF('campo/json/json.php',{datos:dat,acc:1});
				console.log(rj);
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
			<div class="nuevo">Aviso de Privacidad</div>
			<div style="text-align: justify;width:70%;margin-left: auto;margin-right: auto;" >
				
				En cumplimiento a lo dispuesto por los Artículos 15, 16 y 17 de la <strong>Ley Federal de Protección de Datos Personales en Posesión de Particulares</strong> publicada en el Diario Oficial de la Federación el día 5 de Julio de 2010 (la “Ley”), y Artículo 25 del Reglamento de la citada Ley, se extiende el presente Aviso de Privacidad.<br/><br/>

				Shoppers Consulting, S.C., está comprometido con la protección de sus datos personales, al ser responsable de su uso, manejo y confidencialidad, y al respecto le informa lo siguiente:<br/><br/>

				¿Para qué fines utilizamos sus datos personales?<br/><br/>

				Los datos personales que recabamos sobre usted son necesarios para verificar y confirmar su identidad; administrar y crear un perfil, para el desempeño de sus funciones.<br/><br/>

				En caso de que no manifieste su negativa, se entenderá que autoriza el uso de su información personal para dichos fines.<br/><br/>

				¿Qué datos personales utilizamos para los fines anteriores?<br/><br/>

				Requerimos datos de identificación, laborales, académicos, patrimoniales, financieros, en su caso, migratorios, los cuales se obtienen a partir de los documentos requisitados.<br/><br/>

				Asimismo, los datos personales que utilizamos para elaborar los perfiles de Shoppers son los siguientes: Identificación del cliente, demográficos, historial de consumos y tipo de producto o servicio financiero contratado con la Institución.<br/><br/>

				Asimismo, Shoppers Consulting, S.C., podrá comunicar sus datos personales atendiendo requerimientos de información de las autoridades.<br/><br/>

				Shoppers Consulting, S.C.<br/>
				Teléfonos de Contacto: 55704037<br/>
				Nuestro Portal: www.shoppersconsulting.com<br/>

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