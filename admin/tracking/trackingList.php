<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}

	include_once raiz().'lib/php/calcCache.php';
	checaAcceso(50); // checaAcceso Tracking


	// print2($_POST);

	$nivel = $_POST['nivelMax']+1;
	$dim = $db->query("
		SELECT * 
		FROM Dimensiones 
		WHERE nivel = $nivel AND elemId = $_POST[trgtId] AND type='structure' 
	")->fetchAll(PDO::FETCH_ASSOC)[0];
	
	// print2($dim);
	$dimElems = $db->query("SELECT d.nivel, de.* 
		FROM DimensionesElem de
		LEFT JOIN Dimensiones d ON d.id = de.dimensionesId
		WHERE de.padre = $_POST[padre] AND de.dimensionesId = $dim[id]")->fetchAll(PDO::FETCH_ASSOC);

	$dims = $db->query("SELECT * FROM Dimensiones WHERE elemId = $_POST[trgtId] AND type='structure'")->fetchAll(PDO::FETCH_ASSOC);


	$chks = $db->query("
		SELECT c.nombre as cName, c.id as cId, f.code as fCode 
		FROM TargetsChecklist tc
		LEFT JOIN Checklist c ON c.id = tc.checklistId
		LEFT JOIN Frequencies f ON f.id = tc.frequency
		WHERE targetsId = $_POST[trgtId]
		ORDER BY f.orden
	")->fetchAll(PDO::FETCH_ASSOC);

	foreach ($chks as $k => $c) {
		$chks[$k]['refDate'] = getRefDate($c['fCode']);
	}

	$manita = empty($_POST['popUp']) ? 'manita':'';
	$estilo = empty($_POST['popUp']) ? 'color:DarkSlateBlue':'';

?>

<?php if ($_POST['popUp'] == 1){ ?>
<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('target'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
<?php } ?>
	<div>
		<table class="table">
			<thead>
				<th><?php echo $dim['nombre']; ?></th>
				<?php foreach ($chks as $c){ ?>
					<th style="text-align: center;">
						<?php echo $c['cName']; ?><br/>
						<?php echo TR($c['fCode']); ?><br/>
						<?php echo $c['refDate']; ?>
					</th>
				<?php } ?>
			</thead>
			<tbody>
				<?php 
				foreach ($dimElems as $e){
				?>
					<tr>
						<td>
							<span class="<?php echo $manita; ?> nomElem" style="<?php echo $estilo; ?>"
								nivel="<?php echo $e['nivel'] ?>" id="<?php echo "elemDim_$e[id]"; ?>">
								<?php echo "$e[nombre]"; ?>
							</span>
						</td>
						<?php 
						foreach ($chks as $c){ 
						?>
							<td style="text-align: center;">
								<?php
									$vis = getTrackingVisitas($e['nivel'],$e['id'],$_POST['trgtId'],$c['cId'],$c['refDate']);
								?>
								<?php 
								if(!empty($vis['tot'])){ 
									$color = $vis['avg'] == 100?'green':'red';
								?>
									<div style="color: <?php echo $color; ?>;font-weight: bold;">
										<?php echo $vis['avg']; ?>%
									</div>
								<?php }else{ ?>
									 - -
								<?php } ?>
							</td>
						<?php } ?>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php if ($_POST['popUp'] == 1){ ?>
</div>
<?php } ?>

