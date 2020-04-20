<?php  

	$nivel = $_SESSION['CM']['admin']['nivel'];
	// print2($_SESSION);
	$usrId = $_SESSION['CM']['admin']['usrId'];
	$usrInfo = $db->query("SELECT * FROM usrAdmin WHERE id = $usrId")->fetchAll(PDO::FETCH_ASSOC)[0];

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
			popUp('analysis/inicio/chPwd.php',{},function(){},{});
		});
		$('#chChk').click(function(event) {
			// console.log('chPwd');
			popUp('analysis/inicio/chPwd.php',{},function(){},{});
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

			<li id="mEmp" class="nav-item" style="color:grey;">
				<a class="nav-link" ><?php echo strtoupper("$usrInfo[name] $usrInfo[lastname]"); ?></a>				
			</li>
			<li id="mEmp" class="nav-item" style="color:grey;">
				<a href="#" id="chChk" class="nav-link"><?php echo TR('chChk') ?></a>
			</li>

		</ul>
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<a href="#" id="chPwd" class="nav-link"><?php echo TR('chPwd') ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1"><?php echo TR('close_session'); ?></a>
			</li>
		</ul>

	</div>

</nav>




