<?php  

include_once '../../../lib/j/j.func.php';
// print2($_POST);
checaAcceso(60);// checaAcceso externalUsers
$usr = $db->query("SELECT * FROM usrAdmin WHERE id = $_POST[usrId]")->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($usr);


?>
<div class="nuevo"><?php echo "$usr[nombre] $usr[aPat] $usr[aMat] ( $usr[username] ) " ?></div>

<?php  

switch ($usr['nivel']) {
	case '60':
		echo "<strong> Super usuario: Acceso total al sistema </strong>";
		break;
	case '50':
		echo "<strong> Administrador: Acceso total al sistema </strong>";
		break;
	case '40':
		echo "<strong> Instalador: Acceso restringido al sistema </strong>";
		echo "<div id='proyectosLista'>";
			include_once 'usuariosProyectos.php';
		echo "</div>";
		break;

	case '20':
		echo "<strong> Ejecutivo: Acceso restringido al sistema </strong>";
		echo "<div id='proyectosLista'>";
			include_once 'usuariosProyectos.php';
		echo "</div>";
		break;
	case '10':
		echo "<strong> Revisor: Acceso restringido al sistema </strong>";
		echo "<div id='visitasLista'>Para asignar una visita a este revisor, ve a la sección de revisores.</div>";
		echo "<hr/>";
		include_once 'datosBancarios.php';
			// include_once 'usuariosProyectos.php';
		// echo "</div>";
		break;
	
	default:
		# code...
		break;
}


?>