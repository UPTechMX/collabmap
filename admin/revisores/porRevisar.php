<?php
if($_POST['ajax'] == 1){
	session_start();
	include_once '../../lib/j/j.func.php';
	$nivel = $_SESSION['IU']['admin']['nivel'];
	$uId = $_SESSION['IU']['admin']['usrId'];
	if($nivel != 10){
		exit('No tienes acceso');
	}

}

 $porRevisar = $db->query("SELECT vu.*, v.aceptada,t.nombre as tNom, m.nombre as mNom, v.fechaIngreso, v.id as vId,
 	rot.fecha, rot.fechaLimite, e.nombre as estatus, r.nombre as rNom, c.nombre as cNom, p.nombre as pNom,t.POS
 	FROM VisitasUsuarios vu
 	LEFT JOIN Visitas v ON v.id = vu.visitasId
 	LEFT JOIN Estatus e ON v.aceptada = e.estatus
 	LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
 	LEFT JOIN Repeticiones r ON r.id = rot.repeticionesId
 	LEFT JOIN Tiendas t ON t.id = rot.tiendasId
 	LEFT JOIN Marcas m ON m.id = t.marcasId
 	LEFT JOIN Clientes c ON m.clientesId = c.id
 	LEFT JOIN Proyectos p ON p.id = r.proyectosId
 	WHERE vu.usuariosId = $uId AND vu.asignada = 1 AND v.aceptada < 90 AND v.aceptada >= 60
 	ORDER BY v.fechaIngreso")->fetchAll(PDO::FETCH_ASSOC);


// print2($porRevisar);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.revVis').click(function(event) {
			var vId = this.id.split('_')[1];
			$('#porRevisar').load(rz+'admin/revisores/visita.php',{div:1,vId:vId,rev:1});
		});
	});
</script>

<div class="nuevo">
	Visitas para revisión
</div>
<table class="table">
	<thead>
		<tr>
			<th>Cliente</th>
			<th>Proyecto</th>
			<th>Repetición</th>
			<th>Marca</th>
			<th>POS</th>
			<th>Tienda</th>
			<th>Fecha de apertura</th>
			<th>Fecha de cierre</th>
			<th>Fecha de entrega</th>
			<th>Estatus</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($porRevisar as $v){ ?>
			<tr>
				<td><?php echo $v['cNom']; ?></td>
				<td><?php echo $v['pNom']; ?></td>
				<td><?php echo $v['rNom']; ?></td>
				<td><?php echo $v['mNom']; ?></td>
				<td><?php echo $v['POS']; ?></td>
				<td><?php echo $v['tNom']; ?></td>
				<td><?php echo $v['fecha']; ?></td>
				<td><?php echo $v['fechaLimite']; ?></td>
				<td><?php echo $v['fechaIngreso']; ?></td>
				<td><?php echo $v['estatus']; ?></td>
				<td><i class="glyphicon glyphicon-eye-open manita revVis" id="revVis_<?php echo $v['vId'];?>"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>