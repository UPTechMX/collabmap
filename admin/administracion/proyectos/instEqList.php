<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$equipos = $db -> query("SELECT * FROM InstalacionesEquipos WHERE instalacionesId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);
	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delInstEq').click(function(event) {
			var elemId = $(this).closest('tr').attr('dimEle');
			// console.log(eleId);
			conf('¿Estás seguro que deseas eliminar este equipo de la instalación?',{elemId:elemId},function(e){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:3,opt:6});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('.trEq_'+e.elemId).remove();
				}
			})

		});
	});
</script>

<table class="table">
	<?php 
	foreach ($equipos as $e){ 
		$datEle = datosEquip($e['dimensionesElemId']); 
		// print2($datEle);
	?>
		<tr id="trEq_<?php echo $e['dimensionesElemId']; ?>" dimEle="<?php echo $e['id']; ?>" class="trEq_<?php echo $e['id']; ?>" ">
			<?php
				$html = "<strong> $datEle[area] </strong>";
				$arbol = $datEle['arbol'];
				for ($i=$datEle['numDim']-1; $i >= 0; $i--) { 

					// echo $i."<br/>";
					$html .= "&nbsp;<i class='glyphicon glyphicon-chevron-right'></i>&nbsp;";
					$html .= $arbol["d$i"]." : ".$arbol["de$i"];
				}
				
			?>
			<td><i class="glyphicon glyphicon-trash manita rojo delInstEq"></i></td>
			<td><?php echo $html; ?></td>
		</tr>
	<?php } ?>
</table>
