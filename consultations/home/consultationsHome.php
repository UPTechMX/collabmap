<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	$today = date('Y-m-d');
	// print2($today);
	if(empty($_SESSION['CM']['consultations']['usrId'])){
		// TODAS LAS CONSULTAS ABIERTAS;

		$now = $db->query("SELECT * 
			FROM Consultations c
			WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
			AND initDate <= '$today' AND finishDate >= '$today'
		")->fetchAll(PDO::FETCH_ASSOC);

		$future = $db->query("SELECT * 
			FROM Consultations c
			WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
			AND initDate >= '$today'
		")->fetchAll(PDO::FETCH_ASSOC);

		$past = $db->query("SELECT * 
			FROM Consultations c
			WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
			AND finishDate <= '$today'
		")->fetchAll(PDO::FETCH_ASSOC);

	}else{
		// CONSULTAS DEL USUARIO
	}
	// print2($now);
	// print2($future);
	// print2($past);
	

?>
<i class="fa fa-bacon"></i>
<script type="text/javascript">
	$(document).ready(function() {
		$('#iWantMore').click(function(event) {
			// var url = window.location.href;
			console.log('aaa');
			var request = <?php echo !empty($_REQUEST)?atj($_REQUEST):'{}'; ?>;
			$('#content').load(rz+'consultations/home/about.php');
			chUrl(request,'acc','about');
		});

	});
</script>
<div style="margin-top: 50px;" class="d-none d-md-block"></div>
<div class="title">
	<?php echo TR('about'); ?>...
</div>
<div style="text-align: justify;">
	<div>
		Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.	
	</div>
	<div style="margin-top: 10px;">
		<span class="sidebarElement" style="font-size: .9em;" id="iWantMore">
			<i class="glyphicon glyphicon-forward"></i><?php echo TR('iWantMore') ?>
		</span>
	</div>
</div>
