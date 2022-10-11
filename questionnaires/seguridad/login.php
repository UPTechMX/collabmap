<?php
	include_once root().'lib/j/j.func.php';
	$location = empty($location)?'questionnaires':$location;
	$rootLoginQuest = aRaizHtml($location);

	// indicate if there is a failed login
	$failedLoginStatus = !empty($failedLogin)? $failedLogin : 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo TR('systemName'); ?></title>
	<!-- Login.php -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  	<!-- Captcha -->
	<!-- LIBRERIAS CSS -->
	<link href="<?php echo $rootLoginQuest; ?>lib/js/bootstrap4/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $rootLoginQuest; ?>lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $rootLoginQuest; ?>lib/css/general.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $rootLoginQuest; ?>questionnaires/seguridad/loginCSS.css" rel="stylesheet" type="text/css" />
	
	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="<?php echo $rootLoginQuest; ?>lib/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo $rootLoginQuest; ?>lib/j/j.js"></script>
	<script src="<?php echo $rootLoginQuest; ?>lib/js/bootstrap4/js/bootstrap.min.js"></script>

</head>

<script type="text/javascript">

	$(document).ready(function() {
		<?php if($failedLoginStatus == 1) { ?>
		
			alertar('<?php echo TR("unregisteredLogin"); ?>',function(e){},{});
			
			// $('#signup').click(function(event) {
				$('#loginContent').hide();
				$('#signupContent').show();
			// });`;
			
		<?php } ?>

		soloNumeros($('#usuario'));

		$("#login").click(function(event) {
			var length = $('#usuario').val().length;
			if(length != 16){
				alertar('<?php echo TR("NIKnum"); ?>');
			}else{
				$('#loginForm').submit();
			}
		});

	});
</script>

<body>
	<div class="container">
		<div class="header">
		</div>
		<!-- <?php include $rootLoginQuest.'general/lang.php'; ?> -->
		<img src="<?php echo $rootLoginQuest; ?>img/marquesina.png"  style="width:100%;" >
		<hr />
		<div class="content" style="margin:5%">
			<div id="loginContent">
				<div class="row">
					<div class="col-lg-8 col-md-4 col-sm-1" style="padding:35px 10px 20px 10px;">
					</div>
					<div class="col-lg-4 col-md-8 col-sm-11">
						<form id="loginForm" name="loginForm" method="post" action="<?php "$_SERVER[PHP_SELF]"; ?>?<?php echo empty($_GET['code'])?'':"code=$_GET[code]";  ?>">
							<div align="center" style="border:solid #00aeef;padding:20px 10px 20px 10px;
								background:#fff;width:100%;border-radius:10px;color:black;">
								<table>
									<tr>
										<td>NIK:</td>
										<td>&nbsp;&nbsp;</td>
										<td>
											<input type="text" name="usuario" id="usuario" maxlength="16" 
												class="form-control" style="border-radius:0px;" />
										</td>
									</tr>
									<tr>
										<td>NIK:</td>
										<td>&nbsp;&nbsp;</td>
										<td><input type="text" name="pwd" id="pwd" class="form-control" style="border-radius:0px;" /></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;&nbsp;</td>
										<td></td>
									</tr>
									<!-- <tr>
										<td><?php echo TR('password'); ?>: </td>
										<td>&nbsp;&nbsp;</td>
										<td><input type="password" name="pwd" id="pwd"  class="form-control" style="border-radius:0px;"/></td>
									</tr> -->
								</table>
								<br/>
								<br/>
								<span id="login" class="btn btn-shop" ><?php echo TR('log_in'); ?></span>
								
								<?php if(false){ ?>
									<div class="signup">
										<br/>
										<span style="color:grey;" class="manita" id="signup"><?php echo TR('sign_up'); ?></span>
									</div>
								<?php } ?>

							</div>
							<input type="hidden" name="code" value="<?= !empty($_GET['code'])?$_GET['code']:""; ?>">
						</form>
					</div>
				</div>
			</div>
			<div id="signupContent" style="display: none;" ><?php include_once raiz().'register/signupForm.php'; ?></div>
		</div>
		<div class="footer"><?php include $rootLoginQuest.'questionnaires/layout/footer.php'; ?></div>

	</div>
	<div class="modal fade" id="alertas" role="dialog" style="overflow-y: auto !important;" >
		<div id="modalAlerta" class="modal-dialog">
			<div class="modal-content" style="border-radius: 0px;" id="alertasCont">
				Cargando...
			</div>
		</div>
	</div>
	<script type="text/javascript">
			var refreshButton = document.getElementById("refresh-captcha");
			var captchaImage = document.getElementById("image-captcha");

			refreshButton.onclick = function(event) {
				event.preventDefault();
				captchaImage.src = 'captcha/image.php?' + Date.now();
			}
		</script>

</body>
</html>