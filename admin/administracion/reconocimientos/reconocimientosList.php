<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}


if(empty($_POST['paramsBusq'])){
	$elementos = $db->query(" SELECT * FROM Reconocimientos ORDER BY nombre") -> fetchAll(PDO::FETCH_ASSOC);
}else{
	$where = '';
	foreach ($_POST['paramsBusq'] as $k => $p) {
		if($k == 'nombre'){
			$where .= " AND nombre LIKE :nombre ";
		}else{
			// echo "strpos $p : ".strpos($p, '%') ."<br/>";
			if($k != 'coloniaSel'){
				if(strpos($p, '%') !== false){
					$where .= " AND c.$k LIKE :$k";
				}else{
					$where .= " AND c.$k = :$k";
				}
			}
		}
	}

	$sql = "SELECT c.* 
		FROM Reconocimientos c
		WHERE 1 $where ORDER BY nombre
	";

	// echo $sql;
	// print2($sql);
	$prep = $db->prepare($sql);

	unset($_POST['paramsBusq']['coloniaSel']);
	$prep -> execute($_POST['paramsBusq']);
	$elementos = $prep -> fetchAll(PDO::FETCH_ASSOC);

	// exit();
}

$cuenta = count($elementos);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(eleId);
			popUp('admin/administracion/reconocimientos/reconocimientosAdd.php',{eleId:eleId},function(){},{});
		});
		$('.verEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(eleId);
			$('#infoEle').load(rz+'admin/administracion/reconocimientos/reconocimientosInfo.php',{eleId:eleId})
		});

		$('#numBusq').text('<?php echo $cuenta; ?>');

		muestraResultados({id: "contador", cuenta:"contar"});

	});
</script>

<table class="table">
	<thead>
	</thead>
	<tbody>
		<?php foreach ($elementos as $e){ ?>
			<tr id="elem_<?php echo $e['id']; ?>" class="contar">
				<td><?php echo "$e[nombre] "; ?></td>
				<td><i class="glyphicon glyphicon-pencil manita edtEle"></i></td>
				<td><i class="glyphicon glyphicon-eye-open manita verEle"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>