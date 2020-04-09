<?php  

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

include_once '../../../lib/j/j.func.php';
// print2($_POST);
checaAcceso(60);
$uId = $_POST['usrId'];
$usr = $db->query("SELECT * FROM usrAdmin WHERE id = $uId")->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($usr);
$nivel = $usr['nivel'];

	$cuantasVisitadorRealizo = $db->query("SELECT COUNT(*) FROM Visitas 
		WHERE usuarioRealizo = $uId AND etapa = 'visita' ")->fetchAll(PDO::FETCH_NUM)[0][0];

	$cuantasVisitadorProgramo = $db->query("SELECT COUNT(*) FROM Visitas 
		WHERE usuarioProgramado = $uId AND etapa = 'visita' ")->fetchAll(PDO::FETCH_NUM)[0][0];

	$cuantasJefeCuadrilla = $db->query("SELECT COUNT(*) 
		FROM Visitas v
		LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
		WHERE v.etapa = 'instalacion' AND ei.instalador = $uId")->fetchAll(PDO::FETCH_NUM)[0][0];

	$cuantasInstalador = $db->query("SELECT COUNT(*) 
		FROM Visitas v
		LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
		LEFT JOIN EquiposPersonal ep ON ep.equiposId = ei.id AND usuariosId = $uId AND nivel = 44
		WHERE v.etapa = 'instalacion' AND ep.usuariosId = $uId")->fetchAll(PDO::FETCH_NUM)[0][0];

	$cuantasAprendiz = $db->query("SELECT COUNT(*) 
		FROM Visitas v
		LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
		LEFT JOIN EquiposPersonal ep ON ep.equiposId = ei.id AND usuariosId = $uId AND nivel = 42
		WHERE v.etapa = 'instalacion' AND ep.usuariosId = $uId ")->fetchAll(PDO::FETCH_NUM)[0][0];

// echo "cuantasVisitadorRealizo :  $cuantasVisitadorRealizo<br/>";
// echo "cuantasVisitadorProgramo :  $cuantasVisitadorProgramo<br/>";
// echo "cuantasJefeCuadrilla :  $cuantasJefeCuadrilla<br/>";
// echo "cuantasInstalador :  $cuantasInstalador<br/>";
// echo "cuantasAprendiz :  $cuantasAprendiz<br/>";




?>

<div class="nuevo">Estadísticas del usuario</div>

<table class="table">
	<tr>
		<th width="60%">Nombre:</th>
		<td><?php echo "$usr[nombre] $usr[aPat] $usr[aMat]"; ?></td>
	</tr>
	<tr>
		<th width="60%">Username:</th>
		<td><?php echo "$usr[username]"; ?></td>
	</tr>
	<tr>
		<th width="60%">Nivel actual:</th>
		<td><?php echo $nomNivel[$usr['nivel']]; ?></td>
	</tr>
	<tr>
		<th width="60%">Visitas que le han asignado:</th>
		<td style="text-align: right;"><?php echo "$cuantasVisitadorProgramo"; ?></td>
	</tr>
	<tr>
		<th width="60%">Visitas que realizó:</th>
		<td style="text-align: right;"><?php echo "$cuantasVisitadorRealizo"; ?></td>
	</tr>
	<tr>
		<th width="60%">Instalaciones como jefe de cuadrilla:</th>
		<td style="text-align: right;"><?php echo "$cuantasJefeCuadrilla"; ?></td>
	</tr>
	<tr>
		<th width="60%">Instalaciones como instalador:</th>
		<td style="text-align: right;"><?php echo "$cuantasInstalador"; ?></td>
	</tr>
	<tr>
		<th width="60%">Instalaciones como aprendiz:</th>
		<td style="text-align: right;"><?php echo "$cuantasAprendiz"; ?></td>
	</tr>
</table>











