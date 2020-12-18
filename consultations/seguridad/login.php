<?php


	session_start();
	if($_REQUEST['logout'] == 1){
		unset($_SESSION['CM']['consultations']);
		unset($_SESSION['CM']['questionnaires']);
	}
	unset($_GET['logout']);
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}


?>

<?php if (empty($_SESSION['CM']['consultations']['usrId'])){ ?>
	<script type="text/javascript">
		$(document).ready(function() {


			$('.loginSend').click(function(event) {
				var dat = $(this).closest('.loginForm').serializeObject();
				var allOk = camposObligatorios('.loginForm');

				// console.log(dat);
				if(allOk){
					var rj = jsonF('consultations/seguridad/acceso.php',dat);
					var r = $.parseJSON(rj);

					if(r.ok == 1){
						var get = chUrl({},'','',true);
						
						// console.log(get);
						$('#content').load(rz+'consultations/layout/content.php',get);
						$('#loginCont').load(rz+'consultations/seguridad/login.php');
						$('#loginContNavBar').load(rz+'consultations/seguridad/login.php');
						
					}
				}
			});


		});
	</script>
	<div style="text-align: center;margin-top: 20px;">
		<span class="sidebarElement loginBtn negro"><?php echo TR('log_in'); ?></span>
		<div class="loginInfoCont" style="padding: 20px;display: none;">
			<hr>
			<form class="loginForm">
				<div>
					<span class="loginElement azul" style="color:blue;"><?php echo TR('username'); ?></span>
					<input type="text" class="form-control oblig" name="usuario" style="margin-top: 10px;" />
				</div>
				
				<div style="margin-top: 10px;">
					<span class="loginElement azul" style="color:blue;"><?php echo TR('password'); ?></span>
					<input type="password" class="form-control oblig" name="pwd" style="margin-top: 10px;" />
				</div>
				<div style="margin-top: 10px;">
					<span class="btn btn-shop loginSend" style="text-transform: uppercase;"><?php echo TR('log_in'); ?></span>
				</div>
				<div style="margin-top: 40px;">
					<span class="loginElement sign_up" style="text-transform: uppercase;"><?php echo TR('sign_up'); ?></span>
				</div>
				<!-- <div style="margin-top: 40px;">
					<span class="loginElement forgotPwd" style="text-transform: uppercase;"><?php echo TR('forgotPwd'); ?></span>
				</div> -->
			</form>
		</div>
	</div>
<?php }else{ ?>
	<script type="text/javascript">
		$(document).ready(function() {
			var url = window.location.href;
			// console.log(url.substring(url.length -3,url.length))
			if(url.substring(url.length -3,url.length) == 'php' || url[url.length -1] == '/'){
				url += '?';
			}else{
				if(url[url.length -1] == '?')
					url += '';
				else
					url += '&';
			}

			$('.facilitator').click(function(event) {
				var request = {acc:'facilitator'};
				$('#content').load(rz+'questionnaires/targets/index.php');
				chUrl(request,'acc','facilitator',true,true);
				location.reload();
			});

			
			$('.logoutBtn').attr({href:url+'logout=1'});

		});
	</script>
	<div style="text-align: center;margin-top: 40px;">
		<span class="sidebarElement facilitator <?= $_REQUEST['acc'] == 'facilitator'?'verde':'azul'; ?>"  style="text-transform: uppercase;">
			<?php echo TR('facilitator'); ?></span><br/>
	</div>

	<div style="text-align: center;margin-top: 30px;">

		<div class="titleL2Bkg" style="height: 25px;width: 25px;
			margin-left: auto;margin-right: auto;border-radius: 50%;color: white;" >
			<i class="fas fa-user manita edtProfile" style="margin-right: auto;margin-left: auto;margin-top: 3px;"></i>
		</div>
		<div style="text-transform: uppercase; margin-top: 20px;" class="azul">
			<?= $_SESSION['CM']['consultations']['name']; ?>
		</div>
		<div style="margin-top: 10px;">
			<span class="loginElement azul">
				<a class="logoutBtn" href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1"  style="text-transform: uppercase;">
					<?php echo TR('close_session'); ?></a>
			</span>
		</div>
	</div>

<?php } ?>





