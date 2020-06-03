<?php  

	$nivel = $_SESSION['CM']['admin']['nivel'];
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
			<?php if ($nivel >=49){ ?>
				<!-- <li id="mEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=pry">Proyectos</a>
				</li> -->
			<?php } ?>

				<!-- <li id="clientEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=mapaStatus">Logística</a>
				</li> -->

			<?php if ($nivel >=50){ ?>	
				<li id="clientEmp" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=extUsr"><?php echo TR('externalUsers'); ?></a>
				</li>
			<?php } ?>
			<?php if ($nivel >=10){ ?>	
				<li id="complaints" class="nav-item">
					<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=complaints"><?php echo TR('complaints'); ?></a>
				</li>
			<?php } ?>
			<?php if ($nivel >=49){ ?>
<!-- 				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
					data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Usuarios/Preregistro<span class="caret"></span>
				</a>
				
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<?php 
				}
				if ($nivel >=49){ ?>
						<a class="dropdown-item" id="financ" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=cte">
							Usuarios
						</a>
						<a class="dropdown-item" id="admPry" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=pReg">
							Preregistro
						</a>
					<?php } ?>
					<div class="dropdown-divider"></div>

				</div>

			</li>
 -->
			<?php if ($nivel >=50){ ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php echo TR('administration'); ?><span class="caret"></span>
					</a>

					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<?php if ($nivel >=50){ ?>
							<?php if ($nivel >=60){ ?>
							<a class="dropdown-item" id="usrInt" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=usrInt">
								<?php echo TR('internalUsers'); ?>
							</a>
							<?php } ?>
							<a class="dropdown-item" id="cons" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=cons">
								<?php echo TR('consultations'); ?>
							</a>
							<a class="dropdown-item" id="trgs" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=trg">
								<?php echo TR('targets'); ?>
							</a>
							<a class="dropdown-item" id="prjs" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=prjs">
								<?php echo TR('projects'); ?>
							</a>
							<a class="dropdown-item" id="chk" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=chk">
								<?php echo TR('surveys'); ?>
							</a>
							<a class="dropdown-item" id="pubC" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=pubC">
								<?php echo TR('publicCons'); ?>
							</a>
						<?php } ?>

					</div>

				</li>
			<?php } ?>
			<!-- <li id="clientEmp" class="nav-item">
				<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=analisis">Analisis</a>
			</li> -->


		</ul>
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<a href="#" id="chPwd" class="nav-link"><?php echo TR('chPwd'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1"><?php echo TR('close_session'); ?></a>
			</li>
		</ul>

	</div>

</nav>




