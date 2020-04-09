<?php

	include_once '../j/j.func.php';


	$quienes = $db->query("SELECT u.username, cp.fecha
		FROM chPwd cp
		LEFT JOIN Usuarios u ON u.id = cp.usuariosId
		ORDER BY fecha DESC")->fetchAll(PDO::FETCH_ASSOC);

?>


<table>
	<?php foreach ($quienes as $q){ ?>
		<tr>
			<td><?php echo $q['fecha']; ?></td>
			<td><?php echo $q['username']; ?></td>
		</tr>
	<?php } ?>
</table>

