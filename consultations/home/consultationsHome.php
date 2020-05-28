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

		// $future = $db->query("SELECT * 
		// 	FROM Consultations c
		// 	WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
		// 	AND initDate >= '$today'
		// ")->fetchAll(PDO::FETCH_ASSOC);

		// $past = $db->query("SELECT * 
		// 	FROM Consultations c
		// 	WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
		// 	AND finishDate <= '$today'
		// ")->fetchAll(PDO::FETCH_ASSOC);

	}else{
		// CONSULTAS DEL USUARIO
	}
	// print2($now);
	// print2($future);
	// print2($past);
	

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#iWantMore').click(function(event) {
			// var url = window.location.href;
			console.log('aaa');
			var request = <?php echo !empty($_REQUEST)?atj($_REQUEST):'{}'; ?>;
			$('#content').load(rz+'consultations/home/about.php');
			chUrl(request,'acc','about');
		});

		$('.imgFondo').css({
			// backgroundColor:'red',
			backgroundImage:'url('+rz+'img/fondoIcono.png)',
			backgroundSize:'100%',
		})

		$.each($('img'), function(index, val) {
			var file = $(this).attr('file');
			if(file != undefined){
				$(this).attr({src:rz+'img/'+file})
			}
		});

		$('.consultationCardCont').click(function(event) {
			var cId = this.id.split('_')[1];
			console.log(cId);
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
	<!-- <i class="fas fa-bacon"></i> -->
</div>

<?php $color='#2a6bd5'; ?>
<div class="consultationsContainer">
	<div class="sectionBanner">
		<?php echo TR('ongoingConsultations') ?>
	</div>
	<div class="imgFondo icoSeccionFondo" style="">
		<img src="" width="40" style="margin-left: 2px;margin-top: 2px;" file="inProgress.svg" />
	</div>
	<div class="row" style="margin-top: 20px;">
		<?php foreach ($now as $c){ ?>
			<div class="col-md-4" style="padding: 10px;">
				<?php include 'consultationCard.php'; ?>
			</div>
		<?php } ?>
	</div>
</div>
