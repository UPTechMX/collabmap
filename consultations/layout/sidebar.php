<?php 



?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#loginContNavBar, #loginCont').on('click', '.edtProfile', function(event) {
			event.preventDefault();
			var request = <?php echo !empty($_REQUEST)?atj($_REQUEST):'{}'; ?>;
			$('#content').load(rz+'consultations/profile/index.php');
			chUrl(request,'acc','edtProfile',true,false);
		});

		$('.news').click(function(event) {
			var request = {acc:'news'};
			$('#content').load(rz+'consultations/news/index.php');
			chUrl(request,'acc','news',true,true);
		});
		$('.participate').click(function(event) {
			var request = {acc:'participate'};
			$('#content').load(rz+'consultations/home/consultationsHome.php');
			chUrl(request,'acc','participate',true,true);
		});

		$('.sign_up').click(function(event) {
			// console.log('aa');
			var request = {};
			request['acc'] = 'sign_up';
			// chUrl(request,'acc','sign_up',true,true);
			$('#content').load(rz+'consultations/layout/content.php',request);
		});
		$('.forgotPwd').click(function(event) {
			console.log('aa');
			var request = {};
			request['acc'] = 'forgotPwd';
			// chUrl(request,'acc','sign_up',true,true);
			$('#content').load(rz+'consultations/layout/content.php',request);
		});

	});
</script>

<div style="margin: 40px 10px;padding-left: 10%;padding-top: 10px;">
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?">
		<img src="<?php echo $htmlRoot; ?>img/cmLogo.png" style="width: 95%;" />
	</a>
</div>

<div style="text-align: center;margin-top: 100px;">
	<span class="sidebarElement news"><?php echo TR('news'); ?></span><br/>
</div>
<div style="text-align: center;margin-top: 40px;">
	<span class="sidebarElement participate"><?php echo TR('participate'); ?></span><br/>
</div>


<div id="loginCont"><?php include 'seguridad/login.php'; ?></div>
