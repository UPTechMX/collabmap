<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	$today = date('Y-m-d');

	$consInf = $db->query("SELECT c.*, p.name as pName
		FROM Consultations c
		LEFT JOIN Projects p ON p.id = c.projectsId
		WHERE c.id = $_POST[elemId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.imgFondoModal').css({
			// backgroundColor:'red',
			backgroundImage:'url('+rz+'img/fondoIcono.png)',
			backgroundSize:'100%',
			backgroundRepeat:'no-repeat',
		})

		$('#participate').click(function(event) {
			var cId = <?php echo $_POST['elemId']; ?>;
			var request = {};
			request['acc'] = 'consultation';
			request['consultationId'] = cId;
			$('#content').load(rz+'consultations/layout/content.php',request);
			$('#popUp').modal('toggle');
			chUrl(request,'consultationId',cId,true,false);
		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;text-transform: uppercase;">
		<h4 class="modal-title">
			<?php echo $consInf['name']; ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;color: #ffb919;'>
	<div class="row">
		<div class="col-md-2">
			<div class="icono" style="margin-top: 20px;">
				<div style="width:100px;margin-left: auto;margin-right: auto;" class="iconContainer">
					<div style="height: 120px;" class="imgFondoModal" style="background-repeat: no-repeat;">
						<div style="width: 100%;height: 100%;">
							<div style="text-align: center;padding-top: 15px;" class="iconDiv">
								<i class="fas <?php echo $consInf['icon']; ?> fa-4x"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-10">
			<div class="prjName">
				<?php echo $consInf['pName']; ?>
			</div>
			<div class="consultationName" style="text-align: left;">
				<?php echo $consInf['name']; ?>
			</div>
			<div class="dateCard" style="text-align: left;">
				<?php echo $consInf['finishDate']; ?>
			</div>
			<hr/>
			<div class="" style="text-align: left;color: grey;">
				<?php echo $consInf['description']; ?>
			</div>
			<hr/>
			<div style="margin-top: 10px;">
				<span style="font-size: .9em;text-transform: uppercase;" id="participate" class="manita">
					<i class="glyphicon glyphicon-forward"></i><?php echo TR('participate') ?>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop"><?php echo TR('close'); ?></span>
	</div>
</div>
