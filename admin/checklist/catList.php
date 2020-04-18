<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	if(empty($spatial)){
		$pId = $_POST['pId'];
	}else{
		$pId = $spatial['id'];
	}

	checaAcceso(50); // checaAcceso Checklist

	$sql = "SELECT c.*, lj.cuenta FROM 
		Categories c
		LEFT JOIN (SELECT p.categoriesId, COUNT(*) as cuenta FROM Problems p GROUP BY p.categoriesId) lj ON lj.categoriesId = c.id
		WHERE preguntasId = $pId ORDER BY name";

	// print2($sql);

	$categories = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>
<table class="table" id="tableCat_<?php echo $pId; ?>" >
	<?php foreach ($categories as $c){ ?>
		<tr>
			<td><?php echo $c['name']; ?></td>
			<?php if (empty($c['cuenta'])){ ?>
				<td class="glyphicon glyphicon-trash manita rojo delCat" id="delCat_<?php echo $c['id']; ?>"></td>
			<?php } ?>
		</tr>
	<?php } ?>

</table>