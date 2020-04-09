<?php  

	$nivel = $_SESSION['IU']['admin']['nivel'];
	// print2($_SESSION);
?>

<script type="text/javascript">
	$(document).ready(function() {
		// console.log(usr);
		setTimeout(function(){ $('#M<?php echo $_GET['Act']; ?>').addClass('active'); }, 1);
		var inc = '<?php echo $_GET['Act']; ?>';
		if(inc == ''){
			setTimeout(function(){ $('#Minic').addClass('active'); }, 1);
		}

		$('#chPwd').click(function(event) {
			// console.log('chPwd');
			popUp('admin/inicio/chPwd.php',{},function(){},{});
		});

	});
</script>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<button class="navbar-toggler" type="button" data-toggle="collapse" 
		data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<?php if ($nivel == 30 || $nivel >= 50){ ?>			
				<li id="mEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=vis">Visitas</a>
				</li>
			<?php } ?>
			<?php if ($nivel == 46 || $nivel >= 50){ ?>			
				<li id="mEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=inst">Instalaciones</a>
				</li>
			<?php } ?>
			<?php if ($nivel == 10 || $nivel >= 50){ ?>			
				<li id="mEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=reco">Reconocimientos</a>
				</li>
			<?php } ?>
			<?php if ($nivel >= 50){ ?>			
				<li id="mEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=seg">Seguimiento</a>
				</li>
			<?php } ?>
		</ul>
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<a href="#" id="chPwd" class="nav-link">Cambiar contraseña</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1">Cerrar sesión</a>
			</li>
		</ul>

	</div>

</nav>




