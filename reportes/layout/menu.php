<?php

	$prys = $db->query("SELECT * FROM Proyectos")->fetchAll(PDO::FETCH_ASSOC);

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
			popUp('reportes/pags/chPwd.php',{},function(){},{});
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
			<li id="clientEmp" class="nav-item">
				<div style="position: relative;display: block;padding: 10px 15px;margin-top: 3px;">
					Bienvenido :  <?php echo $_SESSION['IU']['pub']['nombre']; ?>
				</div>
			</li>
			<!-- <li id="clientEmp" class="nav-item">
				<div style="position: relative;display: block;padding: 10px 15px;margin-top: 3px;">
					<select id="prySel" class="form-control" style="display: inline;">
						<option value="">- - Selecciona un proyecto - -</option>
						<?php foreach ($prys as $p){ ?>
							<option value="<?php echo $p['id']; ?>"><?php echo $p['nombre']; ?></option>
						<?php } ?>
					</select>
				</div>
			</li> -->
		</ul>
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
		</ul>
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

