<?php

	if(empty($r)){
		session_start();
		include_once '../../lib/j/j.func.php';
		// print2($_POST);

		$r = $db->query("SELECT c.*, e.nombre as eNom, e.color,
			cv.bloqueCalif as avComp, vv.id as vvId, v.fecha, v.hora, v.horario, v.etapa,
			v.id as vId, vst.id as vstId, vst.finalizada as vstFin, vrs.id as vrsId, vrs.finalizada as vrsFin
			FROM Clientes c
			LEFT JOIN Estatus e ON c.estatus = e.id
			LEFT JOIN Visitas v ON v.id = c.visitasId
			LEFT JOIN estatusHist canc ON canc.clientesId = c.id AND canc.estatus = 4 
				AND canc.id = (SELECT id FROM estatusHist j 
						WHERE j.clientesId = canc.clientesId 
						AND j.estatus = 4 
						ORDER BY j.timestamp DESC 
						LIMIT 1)
			LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'visita' 
				AND vv.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vv.clientesId 
					AND z.etapa = 'visita' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vst ON vst.clientesId = c.id AND vst.etapa = 'seguimientoTel' 
				AND vst.id = (SELECT id FROM Visitas k 
					WHERE k.clientesId = vst.clientesId 
					AND k.etapa = 'seguimientoTel' AND (finalizada IS NULL OR finalizada != 1)
					ORDER BY k.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN Visitas vrs ON vrs.clientesId = c.id AND vrs.etapa = 'reparacion' 
				AND vrs.id = (SELECT id FROM Visitas l 
					WHERE l.clientesId = vrs.clientesId 
					AND l.etapa = 'reparacion' AND (finalizada IS NULL OR finalizada != 1)
					ORDER BY l.fechaRealizacion DESC 
					LIMIT 1)
			LEFT JOIN CalculosVisita cv ON cv.visitasId = vv.id AND cv.bloque = 'comp'
			WHERE c.id = $_POST[cId] 
			GROUP BY c.id, bloque
			ORDER BY v.fecha")->fetchAll(PDO::FETCH_ASSOC)[0];

		// $vis = $db->query("SELECT * FROM Visitas WHERE rotacionesId = $r[id]")->fetchAll(PDO::FETCH_ASSOC);

	}

	if(true || $r['estatusDoc'] >= 10){

		// if( ($r['estatus'] >= 10 && $r['estatus'] <= 32	) ){
		if( ($r['estatus'] >= 5 && $r['estatus'] <= 31	) ){
			$btn = "<span class='btn btn-sm btn-shop addVis' act='agenda_visita' 
				data-toggle='tooltip' data-placement='top' title='Agendar visita' >
				<i class='glyphicon glyphicon-calendar'></i>
			</span>";
		}elseif( $r['estatus'] == 32 ){
			$btn = "<span class='btn btn-sm btn-shop addVis' act='conf_visita' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Confirmar visita'>
				<i class='glyphicon glyphicon-ok'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-cancel addVis' style='margin:0px 10px;' act='cancel_visita' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Cancelar visita'>
				<i class='glyphicon glyphicon-remove'></i>
			</span>";
		}elseif( $r['estatus'] == 33 ){
			$btn = "<span class='btn btn-sm btn-shop addVis' act='ver_visita' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Ver datos de la visita'>
				<i class='glyphicon glyphicon-eye-open'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-cancel addVis' style='margin:0px 10px;' act='cancel_visita' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Cancelar visita'>
				<i class='glyphicon glyphicon-remove'></i>
			</span>";
		}elseif( $r['estatusDoc'] >= 10 && (($r['estatus'] >= 38 && $r['estatus'] <= 40	) || $r['estatus'] == 42) && $r['avComp'] == 1 ){
			$btn = "<span class='btn btn-sm btn-shop addVis' act='agenda_instalacion' 
				data-toggle='tooltip' data-placement='top' title='Agendar instalación'>
				<i class='glyphicon glyphicon-calendar'></i>
			</span>";
		}elseif( $r['estatusDoc'] >= 10 && $r['estatus'] == 44 && $r['avComp'] == 1){
			$btn = "<span class='btn btn-sm btn-shop addVis' act='conf_instalacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Confirmar instalación'>
				<i class='glyphicon glyphicon-ok'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-cancel addVis' style='margin:0px 10px;' act='cancel_instalacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Cancelar instalación'>
				<i class='glyphicon glyphicon-remove'></i>
			</span>";
		}elseif( $r['estatusDoc'] >= 10 && ($r['estatus'] == 45 || $r['estatus'] == 46) && $r['avComp'] == 1){
			$btn = "<span class='btn btn-sm btn-shop addVis' style='margin:0px 10px;' act='ver_instalacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Ver datos de la instalación'>
				<i class='glyphicon glyphicon-eye-open'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-cancel addVis' act='cancel_instalacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Cancelar instalación'>
				<i class='glyphicon glyphicon-remove'></i>
			</span>";
		}elseif( $r['estatusDoc'] >= 10 && $r['estatus'] == 60 || $r['estatus'] == 48){
			$btn = "<span class='btn btn-sm btn-shop addVis' style='margin:0px 10px;' act='ver_seguimientoTelAdd' vId = '$r[vstId]' 
				data-toggle='tooltip' data-placement='top' title='Seguimiento telefónico'>
				<i class='glyphicon glyphicon-phone-alt'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-shop addVis' act='agenda_reparacion' 
				data-toggle='tooltip' data-placement='top' title='Agendar reparación'>
				<i class='glyphicon glyphicon-wrench'></i>
			</span>";
		}elseif( $r['estatusDoc'] >= 10 && $r['estatus'] == 55){
			$btn = "<span class='btn btn-sm btn-shop addVis' act='conf_reparacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Confirmar reparacion'>
				<i class='glyphicon glyphicon-ok'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-cancel addVis' style='margin:0px 10px;' act='cancel_reparacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Cancelar reparacion'>
				<i class='glyphicon glyphicon-remove'></i>
			</span>";
		}elseif( $r['estatusDoc'] >= 10 && ($r['estatus'] == 56 || $r['estatus'] == 57)){
			$btn = "<span class='btn btn-sm btn-shop addVis' style='margin:0px 10px;' act='ver_reparacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Ver datos de la reparación'>
				<i class='glyphicon glyphicon-eye-open'></i>
			</span>";
			$btn .= "<span class='btn btn-sm btn-cancel addVis' act='cancel_reparacion' vId = '$r[vId]' 
				data-toggle='tooltip' data-placement='top' title='Cancelar reparación'>
				<i class='glyphicon glyphicon-remove'></i>
			</span>";
		}else{
			$btn = '';
		}

	}else{
		$btn = '';
	}

	$pendiente = '';
	$pend1 = $pend2 = $pend3 = '';
	if($r['estatus']>=5 && $r['estatus'] < 33 ){
		$pendiente = 'pendiente';
		$pend1 = 'p1';
	}

	if($r['estatusDoc'] != 10 && $r['estatus'] >= 5){
		$pendiente = 'pendiente';
		$pend2 = 'p2';
	}

	if(!empty($r['vvId']) && $r['avComp'] != 1 && $r['estatus'] >= 5 && $r['estatus'] != 34){
		$pendiente = 'pendiente';
		$pend3 = 'p3';
	}
	
	


?>

<td class="<?php echo "m$r[mId] e$r[estatus] me$r[mId]-$r[estatus] $pendiente";?>">
	<script type="text/javascript">
		$(function() {
			var bgcolor = '<?php echo !empty($r['color'])?$r['color']:'FFF'; ?>';
			var color = '<?php echo $r['color'] == '000000'?'FFF':'000'; ?>';
			$('#tr_'+<?php echo $r['id']; ?>).css({backgroundColor:'#'+bgcolor,color:'#'+color});
			$('#tr_'+<?php echo $r['id']; ?>+'').find('.edtCte,.docCte,.histRot,.verUbic').css({backgroundColor:'#'+bgcolor,color:'#'+color});
			if(color == '000000'){
				$('#tr_'+<?php echo $r['id']; ?>).css({color:'#fff'});
				$('#tr_'+<?php echo $r['id']; ?>).find('.glyphicon').css({color:'#fff'});
			}
			$('#tr_<?php echo $r['id']; ?> [data-toggle="tooltip"]').tooltip()
		});
	</script>
	<i class="glyphicon glyphicon-user manita edtCte" id="edtCte_<?php echo $r['id'];?>" 
		data-toggle='tooltip' data-placement='top' title='Información del usuario'></i>

</td>
<td>
	<i class="glyphicon glyphicon-file manita docCte" id="docCte_<?php echo $r['id']."_".$r['token'];?>"
		data-toggle='tooltip' data-placement='top' title='Autorizar instalación'></i>
</td>

<td style="text-align: center;">
	<i class="glyphicon glyphicon-book manita histRot" id="histRot_<?php echo $r['id'];?>"
		data-toggle='tooltip' data-placement='top' title='Historial del usuario'></i>
</td>


<td style="background-color: <?php echo $color;?>" class="nombres" id="<?php echo $r['token']; ?>">
	<?php echo "$r[nombre] $r[aPat] $r[aMat]";  ?>
	<?php //echo "($pend1 $pend2 $pend3 $r[estatus])";  ?>
</td>
<td style="<?php echo $styFecha; ?>" >
	<span class="cfecha"><?php echo $r['colonia']; ?></span>&nbsp;
</td>
<td style="text-align: center;" >
	<?php if (!empty($r['lat']) && !empty($r['lng'])){ ?>
		<i class="glyphicon glyphicon-map-marker manita verUbic" 
			id="ubic_<?php echo $r['id']; ?>" lat="<?php echo $r['lat'] ?>" 
			lng="<?php echo $r['lng'] ?>"
			data-toggle='tooltip' data-placement='top' title='Ver ubicación del usuario en mapa'></i>
	<?php } ?>
</td>
<td>
	<?php echo empty($r['eNom'])?'- -':$r['eNom']; ?>
</td>
<td style="text-align: right;">
	<?php if (!empty($r['vvId'])){ ?>
		<span class="manita verAvComp" id="<?php echo "vvId_$r[vvId]"; ?>" 
			data-toggle='tooltip' data-placement='top' title='Ver compromisos del usuario'>
			<?php $r['avComp'] = is_numeric($r['avComp'])?$r['avComp']:0; ?>
			<?php echo number_format($r['avComp']*100,1); ?>%
		</span>
	<?php }else{ ?>
		- -
	<?php } ?>
</td>
<td style="text-align: center;vertical-align: bottom;width: 110px;">
	<strong style="margin-bottom: 10px;">
		<?php if ($r['estatus'] == 4){ ?>
			<?php 
				$fCanc = explode(' ', $r['fechaCancelacion'] );
				echo "$fCanc[0]<br/>$fCanc[1]";
			?>
		<?php }else{ ?>
			<?php echo $r['fecha']; ?><br/>
			<?php echo $r['etapa'] == 'visita'?$r['hora']:''; ?>
			<?php echo $r['etapa'] == 'instalacion'?($r['horario'] == 1 ? 'Matutino':'Vespertino'):''; ?>
			<?php echo $r['etapa'] == 'reparacion'?($r['horario'] == 1 ? 'Matutino':'Vespertino'):''; ?>
		<?php } ?>
	</strong><br/>	
	<?php echo $btn; ?>
</td>
<td style="vertical-align: bottom;w">
	<span class="btn btn-sm btn-shop regLlamada">
		<i class='glyphicon glyphicon-earphone' data-toggle='tooltip' data-placement='top' title='Historial de llamadas'></i>
	</span>
</td>
<td style="vertical-align: bottom;">
	
	<?php if ($r['estatus'] >= 48){ ?>	
		<span class="btn btn-sm btn-shop dwlPics">
			<i class='glyphicon glyphicon-download-alt' data-toggle='tooltip' data-placement='top' title='Descargar fotografías'></i>
		</span>
	<?php } ?>
</td>
<td style="text-align: center;"></td>






