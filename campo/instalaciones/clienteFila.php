<?php  
	if (empty($r)) {
		if (!function_exists('raiz')) {
			include_once '../../lib/j/j.func.php';
		}

		$sql = "SELECT c.*, e.nombre as eNom, e.color, 
			cv.bloqueCalif as avComp, vv.id as vvId, v.fecha, 
			v.id as vId, vInst.id as vInstId, vInst.finalizada as vInstFin, 
			vi.id as viId, vi.finalizada as viFin,
			vEi.id as vEiId, vEi.finalizada as vEiFin,
			vrs.id as vrsId, vrs.finalizada as vrsFin, c.estatusDoc
			FROM Clientes c
			LEFT JOIN Estatus e ON c.estatus = e.id
			LEFT JOIN Visitas v ON v.id = c.visitasId
			LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'visita' 
				AND vv.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vv.clientesId 
					AND z.etapa = 'visita' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vInst ON vInst.clientesId = c.id AND vInst.etapa = 'instalacion' 
				AND vInst.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vInst.clientesId 
					AND k.etapa = 'instalacion' AND (finalizada IS NULL OR finalizada != 1)
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vi ON vi.clientesId = c.id AND vi.etapa = 'impacto' 
				AND vi.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vi.clientesId 
					AND z.etapa = 'impacto' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vEi ON vEi.clientesId = c.id AND vEi.etapa = 'evaluacionInt' 
				AND vEi.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vEi.clientesId 
					AND k.etapa = 'evaluacionInt' 
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vrs ON vrs.clientesId = c.id AND vrs.etapa = 'reparacion' 
				AND vrs.id = (SELECT id FROM Visitas l 
					WHERE l.clientesId = vrs.clientesId 
					AND l.etapa = 'reparacion' AND (finalizada IS NULL OR finalizada != 1)
					ORDER BY l.fechaRealizacion DESC 
					LIMIT 1)

			LEFT JOIN CalculosVisita cv ON cv.visitasId = vv.id AND cv.bloque = 'comp'
			WHERE 1 AND c.Id = $_POST[cId]
			GROUP BY c.id
		";

		$r = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
	}

	if($r['estatusDoc'] >= 10){

		if( ($r['estatus'] >= 10 && $r['estatus'] <= 30	) ){
			$btn = "<span  >Sin visita técnica</span>";
		}elseif( $r['estatus'] == 32 ){
			$btn = "<span  >Sin visita técnica</span>";
		}elseif( $r['estatus'] == 33 ){
			$btn = "<span  >Sin visita técnica</span>";
		}elseif( (($r['estatus'] >= 38 && $r['estatus'] <= 40	) || $r['estatus'] == 42 || $r['estatus'] == 3) && $r['avComp'] == 1 ){
			$btn = "<span class='btn btn-sm btn-shop instalar' vId='$r[vInstId]'>Instalar</span>";
		}elseif( $r['estatus'] == 44 && $r['avComp'] == 1){
			$btn = "<span class='btn btn-sm btn-shop instalar' vId='$r[vInstId]'>Instalar</span>";
		}elseif( ($r['estatus'] >= 45 && $r['estatus'] <= 47) && $r['avComp'] == 1){
			$btn = "<span class='btn btn-sm btn-shop instalar' vId='$r[vInstId]'>Instalar</span>";
		}elseif(  $r['estatus'] == 48){
			// $btn = "<span class='btn btn-sm btn-shop evCuest' vId='$r[vEiId]'>Evaluacion interna</span>";
			if ($r['viFin'] != 1){
				$btn = "<span class='btn btn-sm btn-shop impCuest' vId='$r[viId]'>Impacto</span>";
			}
			if ($r['viFin'] == 1){
				$btn = "<span class='btn btn-sm btn-shop evCuest' vId='$r[vEiId]'>Evaluacion interna</span>";
			}
		}elseif( $r['estatus'] == 60){
			$btn = "<span >Instalación realizada</span>";
		}elseif( $r['estatus'] == 55){
			$btn = "<span class='btn btn-sm btn-shop reparar' vId='$r[vrsId]'>reparar</span>";
		}else{
			$btn = '';
		}

	}else{
		$btn = '';
	}



?>
<td style="text-align: center;">
	<i class="glyphicon glyphicon-book manita histRot" id="histRot_<?php echo $r['id'];?>"></i>
</td>


<td style="background-color: <?php echo $color;?>">
	<?php echo "$r[nombre] $r[aPat] $r[aMat] ";  ?>
		
</td>
<td style="<?php echo $styFecha; ?>" >
	<span class="cfecha"><?php echo $r['colonia']; ?></span>&nbsp;
</td>
<td style="text-align: center;" >
	<?php if (!empty($r['lat']) && !empty($r['lng'])){ ?>
		<i class="glyphicon glyphicon-map-marker manita verUbic" 
			id="ubic_<?php echo $r['id']; ?>" lat="<?php echo $r['lat'] ?>" 
			lng="<?php echo $r['lng'] ?>"></i>
	<?php } ?>
</td>
<td>
	<?php echo empty($r['eNom'])?'- -':$r['eNom']; ?>
</td>
<td style="text-align: right;">
	<?php if (!empty($r['vvId'])){ ?>
		<span class="manita verAvComp" id="<?php echo "vvId_$r[vvId]"; ?>">
			<?php echo number_format($r['avComp']*100,1); ?>%
		</span>
	<?php }else{ ?>
		- -
	<?php } ?>
</td>
<td style="text-align: center;vertical-align: bottom">
	<?php echo $btn; ?>
</td>
<td style="text-align: center;"></td>
