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
			popUp('questionnaires/home/chPwd.php',{},function(){},{});
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
				<?php echo strtoupper($_SESSION['CM']['questionnaires']['name']); ?>
			</li>
		</ul>
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<a  href="<?php echo $_SERVER['PHP_SELF'];?>?Act=questionnaire" class="nav-link"><?php echo TR('mainPage') ?></a>
			</li>
			<li class="nav-item">
				<a  href="https://bit.ly/2N5ymkf" target="_blank" class="nav-link"><?php echo TR('reportProblems') ?></a>
			</li>
			<li class="nav-item">
				<a  href="<?php echo $_SERVER['PHP_SELF'];?>?Act=tutorials" id="tutorials" class="nav-link"><?php echo TR('tutorials') ?></a>
			</li>
			<!-- <li class="nav-item">
				<a href="#" id="chPwd" class="nav-link"><?php //echo TR('chPwd') ?></a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1"><?php echo TR('close_session'); ?></a>
			</li>
		</ul>

	</div>

</nav>




