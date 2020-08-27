<script type="text/javascript">
	$(document).ready(function() {
		$('#imgLogoBar').attr({src:rz+'img/'+$('#imgLogoBar').attr('file')});
	});
</script>

<nav class="navbar navbar-expand-lg navbar-light bg-light d-block d-md-none" style="margin-bottom: 0px;">
	<div style="padding: 0px 10px;">
		<?php include raiz().'general/lang.php'; ?>
	</div>

	<button class="navbar-toggler" type="button" data-toggle="collapse" 
		data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?">
		<img src="" file="cmLogo.png" width="50%" style="margin-left: 30px;" id='imgLogoBar' />
	</a>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<div style="text-align: center;margin-top: 20px;">
			<span class="sidebarElement news"><?php echo TR('news'); ?></span>
		</div>
	</div>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<div style="text-align: center;margin-top: 20px;">
			<span class="sidebarElement participate"><?php echo TR('participate'); ?></span>
		</div>
	</div>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<div id="loginContNavBar"><?php include raiz().'consultations/seguridad/login.php'; ?></div>
	</div>
</nav>
