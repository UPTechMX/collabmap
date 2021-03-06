<?php  

	// include_once '../../lib/j/j.func.php';
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

		$('#suCancel').click(function(event) {
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

			var rj = jsonF('consultations/register/json/chkUser.php',{username:dat.username});
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
			var allOk = camposObligatorios('#nEmp');

			var rj = jsonF('consultations/register/json/chkUser.php',{username:dat.username});
			var r = $.parseJSON(rj);
			// r.cuenta = 2;
			if(r.cuenta != 0){
				alertar('<?php echo TR('existingUser'); ?>',function(e){},{});
				// $('#username').val( $('#username').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

			if(allOk){
				var email = dat.email;
				validate = validateEmail(email);
				if(!validate){
					alertar('<?php echo TR('invalidEmail'); ?>',function(e){},{});
					allOk = false;
				}
			}


			if( $('#pwd2').val() != $('#signupContent #pwd').val() ){
				allOk = false;
				$('#pwd, #pwd2').css({backgroundColor:'rgba(255,0,0,.5)'});
			}

			if(allOk){
				var rj = jsonF('consultations/register/json/json.php',{data:dat,acc:'signup',opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					alertar('<?php echo TR('regConf'); ?>');
					var params = chUrl({},'','',false,true);
					$('#content').load(rz+'consultations/home/consultationsHome.php',params);
				}
			}

		});

	});
</script>

<div class="" id='pano' style='width:80%;border:solid #00aeef; grey;border-radius: 10px;margin-left: auto;margin-right: auto; padding: 10px'>
	<br/>
	<div id="signupContent">
		<form id="nEmp">
			<table class="table" border="0">
				<tr>
					<td><?php echo TR('username'); ?></td>
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
				<tr>
					<td><?php echo TR('last_name'); ?></td>
					<td><input type="text" value="<?php echo $datC['lastname']; ?>" name="lastname" id="lastname" class="form-control oblig"></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo TR("email"); ?></td>
					<td><input type="text" value="<?php echo $datC['email']; ?>" name="email" id="email" class="form-control oblig"></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo TR('gender'); ?></td>
					<td>
						<select name="gender" class="form-control">
							<option value="">- - - <?php echo TR('gender'); ?> - - -</option>
							<option value="M" <?php echo $datC['gender'] == 'M'?'selected':''; ?>><?php echo TR('male'); ?></option>
							<option value="F" <?php echo $datC['gender'] == 'F'?'selected':''; ?>><?php echo TR('female'); ?></option>
							
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo TR("age"); ?></td>
					<td><input type="text" value="<?php echo $datC['age']; ?>" name="age" id="age" class="form-control oblig"></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo TR('password'); ?></td>
					<td><input type="password" name="pwd" id="pwd" class="form-control oblig"></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo TR('repeatpwd'); ?></td>
					<td><input type="password" id="pwd2" class="form-control oblig"></td>
					<td valign="middle" style="font-size: large;vertical-align: middle;">
						<i id="pwdChk" style="display: none;" class="glyphicon"></i>
					</td>
				</tr>

			</table>		
		</form>		
	</div>
	<div style="text-align: right;">
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
