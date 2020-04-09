<?php
	include_once '../../lib/j/j.func.php';

	session_start();
	include raiz()."lib/php/captcha/simple-php-captcha.php";
	$_SESSION['captcha'] = simple_php_captcha();
	// print2($_SESSION['captcha']);
	$img = end(explode("/",$_SESSION['captcha']['image_src']));
	// print2($img);
	// print2($_GET);

	$stmt = $db->prepare("SELECT c.*, s.email 
		FROM cambiarPwd c
		LEFT JOIN Shoppers s ON s.id = c.shoppersId
		WHERE c.hash = ? AND s.email = ?");
	$stmt->execute(array($_GET['h'],$_GET['em']));
	$liga = $stmt -> fetchAll(PDO::FETCH_ASSOC)[0];
	
	// print2($liga);
	$hora = time();
	$activo = $hora <= $liga['expira'];

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



		<?php if($activo){ ?>
			$('#registrar').click(function(event) {
				var dat = $('#nvoShopper').serializeObject();
				dat.em = '<?php echo $_GET['em']; ?>';
				dat.h = '<?php echo $_GET['h']; ?>';
				var allOk = camposObligatorios('#nvoShopper');
				// console.log(dat);

				if( $('#pwd2').val() != $('#pwd').val() ){
					allOk = false;
					$('#pwd, #pwd2').css({backgroundColor:'rgba(255,0,0,.5)'});
				}

				if($('#pwd').val().length < 6 && allOk){
					alertar('La contraseña debe contener al menos 6 caracteres',function(e){},{});
					allOk = false;
				}

				if(allOk){

					// console.log(dat);
					var rj = jsonF('campo/json/json.php',{datos:dat,acc:3});
					console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('#forma').remove();
						$('#finalizado').html('<div class="nuevo">Actualización de contraseña</div>'+
							'<div style="text-align: justify;">'+
								'la contraseña se ha actualizado correctamente'+
							'</div>');
					}
				}
			});
		<?php } ?>
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
				<div class="nuevo">Cambiar contraseña</div>
					<?php if ($activo){ ?>
						<form id="nvoShopper">
							<table class="">
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
										<div style="text-align: right;">
											<span class="btn btn-shop" id="registrar">Cambiar contraseña</span>
										</div>
									</td>
								</tr>
							</table>

						</form>
					<?php }else{ ?>
						La liga para cambio de contraseña ha expirado.
					<?php } ?>
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