<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}
	

	$aboutDB = $db->query("SELECT * FROM General WHERE name = 'about' ")->fetchAll(PDO::FETCH_ASSOC)[0];
	$about = strip_tags($aboutDB['texto']);

	$today = date('Y-m-d');
	// print2($today);
	$usrId = $_SESSION['CM']['consultations']['usrId'];
	if(empty($usrId)){
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
		
		// print2($usrId);
		$all = $db->query("SELECT c.* 
			FROM Consultations c
			WHERE id NOT IN (SELECT DISTINCT(consultationsId) FROM ConsultationsAudiences)
			AND initDate <= '$today' AND finishDate >= '$today'
		")->fetchAll(PDO::FETCH_ASSOC);

		$now = $db->query("SELECT c.* 
			FROM Consultations c
			LEFT JOIN ConsultationsAudiencesCache cac ON cac.consultationsId = c.id
			LEFT JOIN UsersAudiences ua ON ua.dimensionesElemId = cac.dimensionesElemId AND ua.usersId = $usrId
			WHERE ua.id IS NOT NULL
			AND c.initDate <= '$today' AND c.finishDate >= '$today'
			GROUP BY c.id
		")->fetchAll(PDO::FETCH_ASSOC);

		// print2($now);

		// $now = array();
		foreach ($all as $c) {
			$now[] = $c;
		}

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
			// console.log('aaa');
			var request = <?php echo !empty($_REQUEST)?atj($_REQUEST):'{}'; ?>;
			$('#content').load(rz+'consultations/home/about.php');
			chUrl(request,'acc','about',true,false);
		});

		$('.imgFondo').css({
			// backgroundColor:'red',
			backgroundImage:'url('+rz+'img/fondoIcono.png)',
			backgroundSize:'100%',
			backgroundRepeat:'no-repeat',
		})

		$.each($('.iconDiv'), function(index, val) {
			var w = $(this).closest('.iconContainer').width()/6;
			// console.log(w);
			$(this).css({fontSize:w+'px'});

		});

		$.each($('img'), function(index, val) {
			var file = $(this).attr('file');
			if(file != undefined){
				$(this).attr({src:rz+'img/'+file})
			}
		});

		$('.consultationCardCont').click(function(event) {
			var cId = this.id.split('_')[1];
			popUp('consultations/home/consultationInfo.php',{elemId:cId});
		});

	});
</script>
<div style="margin-top: 50px;" class="d-none d-md-block"></div>
<div class="title">
	<?php echo TR('about'); ?>...
</div>
<div style="text-align: justify;">
	<div class="reset-this">
		<?php echo substr($about, 0,500); ?> <?php if (strlen($about)>500){ ?>
			...
		<?php } ?>
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
			<div class="col-md-6" style="padding: 10px;">
				<?php include 'consultationCard.php'; ?>
			</div>
		<?php } ?>
	</div>
</div>
