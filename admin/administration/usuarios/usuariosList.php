<?php

	if($_POST['ajax'] == 1){
		Include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(60);// checaAcceso Usuarios

	$usuarios = $db->query("SELECT * FROM usrAdmin WHERE id > 1 ORDER BY nivel DESC, username")->fetchAll(PDO::FETCH_ASSOC);
	// print2($usuarios);
	$nomNivel[0] = "noAccess";
	$nomNivel[5] = "analyst";
	$nomNivel[10] = "complaintsFU";
	$nomNivel[50] = "administrator";
	$nomNivel[60] = "superuser";
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
			<th><?php echo TR('user'); ?></th>
			<th><?php echo TR('name'); ?></th>
			<th style="text-align: center;">Nivel</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usuarios as $u){ ?>
			<tr>
				<td><?php echo $u['username']; ?></td>
				<td><?php echo "$u[name] $u[lastname]"; ?></td>
				<td style="text-align: center;"><?php echo TR($nomNivel[$u['nivel']]); ?></td>
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