<?php
	session_start();
	include_once '../../lib/j/j.func.php';

	// print2($_SESSION);	
?>


<?php  

	include_once '../../lib/j/j.func.php';

	$p = $_POST;
	// print2($_POST);
	$edos = $db->query("SELECT * FROM Estados")->fetchAll(PDO::FETCH_ASSOC);

	if($_POST['usuarioId'] != ''){
		$datC = $db-> query("SELECT * FROM Usuarios WHERE id = $_POST[usuarioId]")->fetch(PDO::FETCH_ASSOC);
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#nEmp .oblig').keyup(function(event) {
			$(this).css({backgroundColor:'rgba(255,255,255,1)'});
		});
		$('#env').click(function(event) {
			var ok = camposObligatorios('#nEmp');
			if($('#pwd').val() != $('#pwd2').val()){
				ok = false;
				alertar('Las contraseñas no coinciden, vuelva a intentarlo',function(){
					$('#pwd').val();
					$('#pwd2').val();
				},{});
			}
			if(ok){
				var rj = jsonF('reportes/pags/json/json.php',{pwd:$('#pwd').val(),acc:1,opt:1});
				console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					alertar('Las contraseña ha sido actualizada correctamente',function(){},{});

				}
			}

		});
	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>
			Cambiar contraseña
		</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
		<tr>
				<td>Contraseña</td>
				<td><input type="password" name="pwd" id="pwd" class="form-control oblig"></td>
				<td></td>
			</tr>
			<tr>
				<td>Repetir contraseña</td>
				<td><input type="password" id="pwd2" class="form-control oblig"></td>
				<td valign="middle" style="font-size: large;vertical-align: middle;">
					<i id="pwdChk" style="display: none;" class="glyphicon"></i>
				</td>
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
