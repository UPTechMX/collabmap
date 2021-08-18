<?php

	include_once '../../lib/j/j.func.php';
	
	checaAcceso(50);// checaAcceso externalUsers

	$usuariosPrep = $db->prepare("SELECT * 
		FROM Users u
		WHERE CONCAT(IFNULL(u.name,''),' ',IFNULL(u.lastname,'')) LIKE :name OR u.email LIKE :email OR u.username LIKE :username
		");

	$searchParams['name'] = "%$_POST[search]%";
	$searchParams['email'] = "$_POST[search]%";
	$searchParams['username'] = "%$_POST[search]%";

	$usuariosPrep->execute($searchParams);
	$usuarios = $usuariosPrep->fetchAll(PDO::FETCH_ASSOC);


	// $usuarios = $db->query("SELECT * FROM Users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);
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
			popUp('admin/externalUsers/usuariosAdd.php',{usuarioId:uId},function(){},{});
			$('#privilegiosList').empty();
		});

		$('.tgtUsr').click(function(event) {
			var uId = this.id.split('_')[1];
			// console.log(uId);
			$("#userInfo").load(rz+'admin/externalUsers/userInfo.php',{usrId: uId});
		});
	});
</script>

<table class="table" style="font-size:.9em;" >
	<thead>
		<tr>
			<th><?php echo TR('username'); ?></th>
			<th><?php echo TR('name'); ?></th>
			<!-- <th><?php echo TR('email'); ?></th> -->
			<th style="text-align: center;"></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usuarios as $u){ ?>
			<tr>
				<td><?php echo $u['username']; ?></td>
				<td><?php echo "$u[name] $u[lastname]"; ?></td>
				<!-- <td><?php echo substr("$u[email]", 0,20); ?></td> -->
				<td style="text-align: center;"><?php echo $nomNivel[$u['nivel']]; ?></td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-pencil manita edtUsr" id="edtUsr_<?php echo $u['id'];?>"></i>
				</td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-th manita tgtUsr" id="tgtUsr_<?php echo $u['id'];?>"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>