<?php 



?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#loginContNavBar, #loginCont').on('click', '.edtProfile', function(event) {
			event.preventDefault();
			event.preventDefault();
			var request = <?php echo !empty($_REQUEST)?atj($_REQUEST):'{}'; ?>;
			$('#content').load(rz+'consultations/profile/index.php');
			chUrl(request,'acc','edtProfile',true);
		});

	});
</script>

<div style="margin: 40px 10px;padding-left: 10%;padding-top: 10px;">
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?">
		<img src="<?php echo $htmlRoot; ?>img/cmLogo.png" style="width: 95%;" />
	</a>
</div>

<div style="text-align: center;margin-top: 100px;">
	<span class="sidebarElement"><?php echo TR('news'); ?></span><br/>
</div>


<div id="loginCont"><?php include 'seguridad/login.php'; ?></div>
