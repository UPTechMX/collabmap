<?php  

	$chkCode = true;
	if( !empty($_GET['code']) ){
		$buscTrgt = $db->prepare("SELECT * FROM Targets WHERE code = ?");
		$buscTrgt->execute(array($_GET['code']));

		$trgt = $buscTrgt->fetchAll(PDO::FETCH_ASSOC);
		if(empty($trgt)){
			$chkCode = false;
		}

	}
?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#nEmp #chPwd').click(function(event) {
			var vis = $('#signupContent #pwd').is(':visible');
			if(!vis){
				$('#signupContent #pwd').val('');
				$('#pwd2').val('');
			}
			$('.trPwd').toggle();
		});

		$('.suCancel').click(function(event) {
			$('#loginContent').show();
			$('#signupContent').hide();

		});

		$('#pwd, #pwd2').keyup(function(event) {
			if($('#signupContent #pwd').val() != ''){
				
				if( $('#pwd2').val() != $('#signupContent #pwd').val() ){
					$('#pwdChk').show().removeClass('glyphicon-thumbs-up').addClass('glyphicon-thumbs-down');
				}else{
					$('#pwdChk').show().addClass('glyphicon-thumbs-up').removeClass('glyphicon-thumbs-down');
				}
				$('#pwd, #pwd2').css({backgroundColor:'white'});
			}
		});


		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#username').blur(function(event) {
			var dat = $('#nEmp').serializeObject();

			var rj = jsonF('register/json/chkUser.php',{username:dat.username});
			// console.log(rj);
			var r = $.parseJSON(rj);

			if(r.cuenta != 0){
				alertar('<?php echo TR('existingUser'); ?>',function(e){},{});
				// $('#username').focus();
				// $('#username').val( $('#username').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

		});

		$('#email').blur(function(event) {
			var email = $(this).val();
			validate = validateEmail(email);
			if(!validate){
				alertar('<?php echo TR('invalidEmail'); ?>',function(e){},{});
			}

		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();

				var trgtCode = '';
			<?php if (!empty($_GET['code'])){ ?>
				trgtCode = "<?php echo $_GET['code']; ?>";
			<?php } ?>

			var allOk = camposObligatorios('#nEmp');

			var rj = jsonF('register/json/chkUser.php',{username:dat.username});
			var r = $.parseJSON(rj);
			// r.cuenta = 2;
			if(r.cuenta != 0){
				alertar('<?php echo TR('existingUser'); ?>',function(e){},{});
				// $('#username').val( $('#username').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

			// if(allOk){
			// 	var email = dat.email;
			// 	validate = validateEmail(email);
			// 	if(!validate){
			// 		alertar('<?php echo TR('invalidEmail'); ?>',function(e){},{});
			// 		allOk = false;
			// 	}
			// }


			if( $('#pwd2').val() != $('#signupContent #pwd').val() ){
				allOk = false;
				$('#pwd, #pwd2').css({backgroundColor:'rgba(255,0,0,.5)'});
			}

			if(allOk){
				var rj = jsonF('register/json/json.php',{data:dat,acc:'signup',opt:1,trgtCode:trgtCode});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#signupContent').load(rz+'register/confirm.php');
				}
			}


		});
		
		soloNumeros($('#username'));

	});
</script>

<div class="" id='pano' style='width:80%;border:solid #00aeef; grey;border-radius: 10px;margin-left: auto;margin-right: auto; padding: 10px'>
	<br/>
	<?php if ($chkCode){ ?>	
		<div id="info">
			<form id="nEmp">
				<table class="table" border="0">
					<tr>
						<td><?php echo TR('phone'); ?></td>
						<td>
							<?php if (isset($_POST['usuarioId'])): ?>
								<span><?php echo $datC['username']; ?></span>
							<?php else: ?>
								<input type="text" value="<?php echo $datC['username']; ?>" name="username" id="username" class="form-control oblig" >
							<?php endif ?>

						</td>
						<td></td>
					</tr>
					<tr>
						<td><?php echo TR('name'); ?></td>
						<td>
							<input type="text" value="<?php echo $datC['name']; ?>" name="name" id="name" class="form-control oblig" >
						</td>
						<td></td>
					</tr>

				</table>		
			</form>		
		</div>
		<div style="text-align: right;">
			<span id="suCancel" class="btn btn-sm btn-cancel suCancel"><?php echo TR('cancel'); ?></span>
			<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
		</div>
	<?php }else{ ?>
		<div>
			<?php echo TR('invalidCode'); ?>
		</div>
		<br/>
		<div style="text-align: right;">
			<span id="suCancel" class="btn btn-sm btn-cancel suCancel"><?php echo TR('cancel'); ?></span>
		</div>

	<?php } ?>
</div>
