<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(60);
	$p = $_POST;
	// print2($_POST);
	$edos = $db->query("SELECT * FROM Estados")->fetchAll(PDO::FETCH_ASSOC);

	if($_POST['usuarioId'] != ''){
		$datC = $db-> query("SELECT * FROM usrAdmin WHERE id = $_POST[usuarioId]")->fetch(PDO::FETCH_ASSOC);
	}
?>

<?php

$nivel = $_SESSION['IU']['admin']['nivel'];
if($nivel<60){
	exit('No tienes acceso a esta área');
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#nEmp #chPwd').click(function(event) {
			var vis = $('#pwd').is(':visible');
			if(!vis){
				$('#pwd').val('');
				$('#pwd2').val('');
			}
			$('.trPwd').toggle();
		});


		$('#username').keydown(function(event) {
		});

		$('#pwd, #pwd2').keyup(function(event) {
			if($('#pwd').val() != ''){
				
				if( $('#pwd2').val() != $('#pwd').val() ){
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

			var rj = jsonF('admin/administracion/usuarios/json/buscUsr.php',{username:dat.username});
			var r = $.parseJSON(rj);

			if(r.cuenta != 0){
				alertar('El usuario que intentas ingresar ya existe en la base de datos',function(e){},{});
				// $('#username').focus();
				// $('#username').val( $('#username').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}

		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');

			var rj = jsonF('admin/administracion/usuarios/json/buscUsr.php',{username:dat.username});
			var r = $.parseJSON(rj);
			// r.cuenta = 2;
			if(r.cuenta != 0){
				alertar('El usuario que intentas ingresar ya existe en la base de datos',function(e){},{});
				// $('#username').val( $('#username').val()+'_1' ).css({backgroundColor:'rgba(255,0,0,.5)'});
				allOk = false;
			}
			if( $('#pwd2').val() != $('#pwd').val() ){
				allOk = false;
				$('#pwd, #pwd2').css({backgroundColor:'rgba(255,0,0,.5)'});
			}

			<?php 
				if(isset($_POST['usuarioId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[usuarioId];";
				}else{
					echo 'var acc = 1;';
				}
			?>


			if(allOk){
				var rj = jsonF('admin/administracion/usuarios/json/json.php',{datos:dat,acc:acc,opt:1});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#usuariosList').load(rz+'admin/administracion/usuarios/usuariosList.php',{ajax:1});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['usuarioId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			usuario
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td>Usuario</td>
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
				<td>Nivel</td>
				<td>
					<select id="nivel" class="form-control oblig" name="nivel">
						<option value="0"  <?php echo $datC['nivel'] ==  0?'selected':''; ?> >Sin acceso al sistema</option>
						<option value="10" <?php echo $datC['nivel'] == 10?'selected':''; ?> >Reconocedor</option>
						<option value="30" <?php echo $datC['nivel'] == 30?'selected':''; ?> >Visitador</option>
						<option value="42" <?php echo $datC['nivel'] == 42?'selected':''; ?> >Aprendiz</option>
						<option value="44" <?php echo $datC['nivel'] == 44?'selected':''; ?> >Instalador</option>
						<option value="46" <?php echo $datC['nivel'] == 46?'selected':''; ?> >Jefe de cuadrilla</option>
						<option value="48" <?php echo $datC['nivel'] == 48?'selected':''; ?> >Director de instalaciones</option>
						<option value="49" <?php echo $datC['nivel'] == 20?'selected':''; ?> >Administrativo</option>
						<option value="50" <?php echo $datC['nivel'] == 50?'selected':''; ?> >Administrador</option>
						<option value="60" <?php echo $datC['nivel'] == 60?'selected':''; ?> >Super Usuario</option>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Nombre</td>
				<td>
					<input type="text" value="<?php echo $datC['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Apellido paterno</td>
				<td><input type="text" value="<?php echo $datC['aPat']; ?>" name="aPat" id="aPat" class="form-control oblig"></td>
				<td></td>
			</tr>
			<tr>
				<td>Apellido materno</td>
				<td><input type="text" value="<?php echo $datC['aMat']; ?>" name="aMat" id="aMat" class="form-control oblig"></td>
				<td></td>
			</tr>
			<tr>
				<td>Correo electrónico</td>
				<td><input type="text" value="<?php echo $datC['mail']; ?>" name="mail" id="mail" class="form-control oblig"></td>
				<td></td>
			</tr>
			<?php if (isset($_POST['usuarioId'])): ?>
				<tr>
					<td colspan="3" style="text-align: center;"><span id="chPwd" class="btn btn-sm btn-default">Cambiar contraseña</span></td>
				</tr>
				<tr class="trPwd" style="display: none;">
					<td>Contraseña</td>
					<td><input type="password" name="pwd" id="pwd" class="form-control oblig"></td>
					<td></td>
				</tr>
				<tr class="trPwd" style="display: none;">
					<td>Repetir contraseña</td>
					<td><input type="password" id="pwd2" class="form-control oblig"></td>
					<td valign="middle" style="font-size: large;vertical-align: middle;">
						<i id="pwdChk" style="display: none;" class="glyphicon"></i>
					</td>
				</tr>

			<?php else: ?>
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
			<?php endif ?>

		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
