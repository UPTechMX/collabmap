<?php
	
	$today = date('Y-m-d H:i:s');
	$minDate = date('Y-m-d H:i:s', strtotime($today . ' -1 hour'));

	// print2($today);
	// print2($minDate);



	$stmt = $db->prepare("SELECT * FROM pwdRecover WHERE used IS NULL AND hash = :hash AND usersId = :usersId AND `timestamp` > '$minDate'");
	$arr['hash'] = $_GET['h'];
	$arr['usersId'] = $_GET['u'];
	$stmt -> execute($arr);
	$pwdInfo = $stmt -> fetchAll(PDO::FETCH_ASSOC)[0];

	// print2($pwdInfo);

	if(empty($pwdInfo)){
		exit('Error');
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

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');

			if( $('#pwd2').val() != $('#signupContent #pwd').val() ){
				allOk = false;
				$('#pwd, #pwd2').css({backgroundColor:'rgba(255,0,0,.5)'});
			}

			if(allOk){
				var rj = jsonF('consultations/register/json/json.php',{datos:dat,acc:'chPwd',opt:1});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					alertar('<?php echo TR('pwdChanged'); ?>');
					var params = chUrl({},'','',true,true);
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
			<input type="hidden" name="h" value="<?php echo $_GET['h']; ?>">		
			<input type="hidden" name="u" value="<?php echo $_GET['u']; ?>">		
		</form>		
	</div>
	<div style="text-align: right;">
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
