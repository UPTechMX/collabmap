<?php  
	session_start();
	if(!function_exists('raiz')){
		include_once '../lib/j/j.func.php';
		include_once raiz().'/lib/php/usrInt.php';
		$usrId = $_SESSION['IU']['admin']['usrId'];
		$usr = new Usuario($usrId);
	}
	checaAcceso(50);

	$proyectos = $usr->getProyectos();
	// print2($proyectos);


	foreach ($proyectos as $cId => $prys) {
		foreach ($prys as $p) {
			// echo "$p[id]<br/>";
			$reps[$p['id']] = $usr->getRepeticiones($p['id']);
		}
	}
	// print2($reps);
?>

<script type="text/javascript">
	var gpos = {};
	jQuery(document).ready(function($) {
		$('.chRep').change(function(event) {
			var repId = $(this).val();
			var pId = this.id.split('_')[1];

			var rj = jsonF('admin/inicio/json/json.php',{repId:repId,pId:pId,acc:1});
			// console.log(rj);
			var r = $.parseJSON(rj);
			// console.log(r);
			gpos[repId] = r.gpos;

			for(var e in r){
				// console.log(e);
				$('#'+e+'_'+pId).text(r[e]);
			}
			var fechaIni = fechaFin = totalSum = enviadasSum = faltantesSum = recibidasSum = revisadasSum = publicadasSum = canceladasSum = 0;
			
			fechaIni = new Date('2050-12-12');
			var fechaIniPer = 0;
			$.each($('.fechaIni'), function(index, val) {
				fechaIniPer = new Date($(this).text()+' 00:00:00');
				// fechaIniPer = new Date();
				// console.log(fechaIniPer.getTime());
				if(!isNaN(fechaIniPer)){
					fechaIni = Math.min(fechaIni,fechaIniPer);
				}
			});
			// console.log('aaa',fechaIni)
			var ff = new Date(fechaIni);
			fechaIniPer = ff.getFullYear()+'-'+('0'+(ff.getMonth()+1)).slice(-2)+'-'+ff.getDate();
			$('#fechaIniPer').text(fechaIniPer);

			fechaFin = new Date('0');
			var fechaFinPer = 0;
			$.each($('.fechaFin'), function(index, val) {
				fechaFinPer = new Date($(this).text()+' 00:00:00');
				if(!isNaN(fechaFinPer)){
					fechaFin = Math.max(fechaFin,fechaFinPer);
				}
			});
			var ff = new Date(fechaFin);
			fechaFinPer = ff.getFullYear()+'-'+('0'+(ff.getMonth()+1)).slice(-2)+'-'+ff.getDate();
			$('#fechaFinPer').text(fechaFinPer);
			// console.log(fecha);


			$.each($('.total'), function(index, val) {
				totalSum += parseInt($(this).text());
			});
			$('.totalSum').text(totalSum);

			$.each($('.enviadas'), function(index, val) {
				enviadasSum += parseInt($(this).text());
			});
			$('.enviadasSum').text(enviadasSum);
			$('.enviadasSumPerc').text('('+(enviadasSum/totalSum*100).toFixed(2)+'%)');

			$.each($('.faltantes'), function(index, val) {
				faltantesSum += parseInt($(this).text());
			});
			$('.faltantesSum').text(faltantesSum);
			$('.faltantesSumPerc').text('('+(faltantesSum/totalSum*100).toFixed(2)+'%)');

			$.each($('.recibidas'), function(index, val) {
				recibidasSum += parseInt($(this).text());
			});
			$('.recibidasSum').text(recibidasSum);
			$('.recibidasSumPerc').text('('+(recibidasSum/totalSum*100).toFixed(2)+'%)');

			$.each($('.revisadas'), function(index, val) {
				revisadasSum += parseInt($(this).text());
			});
			$('.revisadasSum').text(revisadasSum);
			$('.revisadasSumPerc').text('('+(revisadasSum/totalSum*100).toFixed(2)+'%)');

			$.each($('.publicadas'), function(index, val) {
				publicadasSum += parseInt($(this).text());
			});
			$('.publicadasSum').text(publicadasSum);
			$('.publicadasSumPerc').text('('+(publicadasSum/totalSum*100).toFixed(2)+'%)');

			$.each($('.canceladas'), function(index, val) {
				canceladasSum += parseInt($(this).text());
			});
			$('.canceladasSum').text(canceladasSum);
			$('.canceladasSumPerc').text('('+(canceladasSum/totalSum*100).toFixed(2)+'%)');

			var sumGpos = {};
			$.each($('.chRep'), function(index, val) {
				 for(var g in gpos[$(this).val()]){
				 	// console.log(g,gpos[$(this).val()][g][0]['cuenta']);
				 	if(g == 'noRealizara')
				 		continue;

				 	if(typeof(sumGpos[g]) == 'undefined')
				 		sumGpos[g] = 0;
				 	
				 	sumGpos[g] += parseInt( gpos[$(this).val()][g][0]['cuenta'] );

				 }
			});

			// console.log(gpos);
			var s = [];
			for(var g in sumGpos){
				var tmp = {};
				switch(g){
					case 'creadas':
						tmp.name = 'Creadas';
						break;
					case 'canceladas':
						tmp.name = 'Canceladas';
						break;
					case 'noRealizara':
						tmp.name = 'No se realizarán';
						break;
					case 'enviadas':
						tmp.name = 'Enviadas';
						break;
					case 'noRevisadas':
						tmp.name = 'Recibidas';
						break;
					case 'revisadas':
						tmp.name = 'Revisadas';
						break;
					case 'publicadas':
						tmp.name = 'Publicadas';
						break;
				}
				tmp.y = sumGpos[g];

				s.push(tmp);

			}

			pay($('#grTots'),s,'Totales');
			// console.log(s);
			
		});

		$($('.chRep')[0]).trigger('change');
		


	});
</script>

<div class="nuevo">Resumen</div>
<table class="table">
	<thead>
		<tr style="font-size: x-small;">
			<th>Cliente</th>
			<th>Proyecto</th>
			<th>Repetición</th>
			<th width="100px">Fecha de inicio:</th>
			<th width="100px">Fecha de finalización:</th>
			<th>Ejecutivos:</th>
			<th>Coordinadores:</th>
			<th width="100px">Fecha de entrega al cliente:</th>
			<th width="100px">Fecha máxima de facturación:</th>
			<th>Pub</th>
			<th>Tot</th>
			<th>Env</th>
			<th>Falt</th>
			<th>Rec</th>
			<th>Rev</th>
			<th>Pub</th>
			<th>Canc</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$totalSum = $enviadasSum = $faltantesSum = $recibidasSum = $revisadasSum = $publicadasSum = $canceladasSum = 0;
		foreach ($proyectos as $cId => $prys){ ?>
			<?php
				$pintar = false;
				foreach ($prys as $p){
					$pintar = $p['finalizado'] != 1 ? true : $pintar;
				}
				if(!$pintar)
					continue;
			?>
			<?php 
			foreach ($prys as $p){


				// $rId = $reps[$p['id']][0]['id'];
				$publica = $reps[$p['id']][0]['publica'];
				$rotTotales = $usr->getRotTotales($reps[$p['id']][0]['id']);
				// print2($reps[$p['id']][0]['id']);
				if(empty($reps[$p['id']][0]['id'])){

					$rotTotales['total'] = 0;
					$rotTotales['enviadas'] = 0;
					$rotTotales['faltantes'] = 0;
					$rotTotales['recibidas'] = 0;
					$rotTotales['revisadas'] = 0;
					$rotTotales['publicadas'] = 0;
					$rotTotales['canceladas'] = 0;
						
				}
				$totalSum += $rotTotales['total'];
				$enviadasSum += $rotTotales['enviadas'];
				$faltantesSum += $rotTotales['faltantes'];
				$recibidasSum += $rotTotales['recibidas'];
				$revisadasSum += $rotTotales['revisadas'];
				$publicadasSum += $rotTotales['publicadas'];
				$canceladasSum += $rotTotales['canceladas'];


				// print2($rotTotales);

			?>

				<script type="text/javascript">
					<?php if (!empty($reps[$p['id']][0]['id'])){ ?>
						gpos['<?php echo $reps[$p['id']][0]['id']; ?>'] = <?php echo atj($rotTotales['gpos']); ?>;
					<?php } ?>
				</script>
				<tr id="trPry_<?php echo $p['id'];?>">
					<td width="10%"><?php echo $prys[0]['cNom'] ?></td>
					<td><?php echo $p['nombre'] ?></td>
					<td>
						<select class="form-control chRep" id="chRep_<?php echo $p['id'];?>">
							<?php foreach ($reps[$p['id']] as $r){ ?>
								<option value="<?php echo $r['id'] ?>"><?php echo $r['nombre']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td class="fechaIni" id="fechaIni_<?php echo $p['id'];?>"><?php echo $reps[$p['id']][0]['fechaIni']; ?></td>
					<td class="fechaFin" id="fechaFin_<?php echo $p['id'];?>"><?php echo $reps[$p['id']][0]['fechaFin']; ?></td>
					<td>
						<?php
						$ej = $usr->usuariosPry($p['id'],20);
						foreach ($ej as $e) { ?>
							<span style=""><?php echo "$e[nombre] $e[aPat] $e[aMat] " ?></span><br/>
						<?php } ?>
					</td>
					<td>
						<?php
						$coord = $usr->usuariosPry($p['id'],30);
						foreach ($coord as $e) { ?>
							<span style=""><?php echo "$e[nombre] $e[aPat] $e[aMat] " ?></span><br/>
						<?php } ?>
					</td>
					<td id="fechaMax_<?php echo $p['id'];?>"><?php echo $reps[$p['id']][0]['fechaMax']; ?></td>
					<td id="fechaMaxFact_<?php echo $p['id'];?>"><?php echo $reps[$p['id']][0]['fechaMaxFact']; ?></td>
					<td class="publica" id="publica_<?php echo $p['id']; ?>"	style="text-align: center;">
						<?php echo $publica == 1?'Sí':'No'; ?>
					</td>
					<td class="total" id="total_<?php echo $p['id']; ?>"	style="text-align: center;">
						<?php echo $rotTotales['total']; ?>
					</td>
					<td class="enviadas" id="enviadas_<?php echo $p['id']; ?>" style="text-align: center;">
						<?php echo $rotTotales['enviadas']; ?>
					</td>
					<td class="faltantes" id="faltantes_<?php echo $p['id']; ?>" style="text-align: center;font-weight:bold;">
						<?php echo $rotTotales['faltantes']; ?>
					</td>
					<td class="recibidas" id="recibidas_<?php echo $p['id']; ?>" style="text-align: center;">
						<?php echo $rotTotales['recibidas']; ?>
					</td>
					<td class="revisadas" id="revisadas_<?php echo $p['id']; ?>" style="text-align: center;">
						<?php echo $rotTotales['revisadas']; ?>
					</td>
					<td class="publicadas" id="publicadas_<?php echo $p['id']; ?>" style="text-align: center;">
						<?php echo $rotTotales['publicadas']; ?>
					</td>
					<td class="canceladas" id="canceladas_<?php echo $p['id']; ?>" style="text-align: center;">
						<?php echo $rotTotales['canceladas']; ?>
					</td>
				</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="10" style="text-align: right;">Totales: </th>
			<th style="text-align: center;" class="totalSum"><?php echo $totalSum; ?></th>
			<th style="text-align: center;" class="enviadasSum"><?php echo $enviadasSum; ?></th>
			<th style="text-align: center;" class="faltantesSum"><?php echo $faltantesSum; ?></th>
			<th style="text-align: center;" class="recibidasSum"><?php echo $recibidasSum; ?></th>
			<th style="text-align: center;" class="revisadasSum"><?php echo $revisadasSum; ?></th>
			<th style="text-align: center;" class="publicadasSum"><?php echo $publicadasSum; ?></th>
			<th style="text-align: center;" class="canceladasSum"><?php echo $canceladasSum; ?></th>
		</tr>
	</tfoot>

</table>


