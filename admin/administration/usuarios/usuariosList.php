<?php

	if($_POST['ajax'] == 1){
		Include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(60);// checaAcceso Usuarios

	$usuarios = $db->query("SELECT * FROM usrAdmin WHERE id > 1 ORDER BY nivel DESC, username")->fetchAll(PDO::FETCH_ASSOC);
	// print2($usuarios);
	$nomNivel[0] = "Sin acceso al sistema";
	$nomNivel[10] = "Reconocedor";
	$nomNivel[30] = "Visitador";
	$nomNivel[42] = "Aprendiz";
	$nomNivel[44] = "Instalador";
	$nomNivel[46] = "Jefe de cuadrilla";
	$nomNivel[48] = "Director de instalaciones";
	$nomNivel[49] = "Administrativo";
	$nomNivel[50] = "Administrador";
	$nomNivel[60] = "Super Usuario";
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.edtUsr').click(function(event) {
			var uId = this.id.split('_')[1];
			popUp('admin/administration/usuarios/usuariosAdd.php',{usuarioId:uId},function(){},{});
			$('#privilegiosList').empty();
		});

		$('.ctaUsr').click(function(event) {
			var uId = this.id.split('_')[1];
			$('#privilegiosList').load(rz+'admin/administration/usuarios/cuentas.php',{usrId:uId});
			
		});
	});
</script>

<table class="table">
	<thead>
		<tr>
			<th>Usuario</th>
			<th>Nombre</th>
			<th style="text-align: center;">Nivel</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usuarios as $u){ ?>
			<tr>
				<td><?php echo $u['username']; ?></td>
				<td><?php echo "$u[nombre] $u[aPat] $u[aMat]"; ?></td>
				<td style="text-align: center;"><?php echo $nomNivel[$u['nivel']]; ?></td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-pencil manita edtUsr" id="edtUsr_<?php echo $u['id'];?>"></i>
				</td>
				<td style="text-align: center;">
					<!-- <i class="glyphicon glyphicon-th manita ctaUsr" id="ctaUsr_<?php echo $u['id'];?>"></i> -->
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>