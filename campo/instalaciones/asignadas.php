<?php  
session_start();
if(!function_exists('raiz')){
	include_once '../../lib/j/j.func.php';
}
// print2($_SESSION);
include_once raiz().'lib/php/campo.php';
$uId = $_SESSION['CM']['admin']['usrId'];
// print2($uId);
// echo "usuario : $uId<br/>";
$usuario = new campo($uId);


$instalaciones = $usuario -> instHoy('instalaciones confirmadas',45,48);
$instalacionesFut = $usuario -> instFut('instalaciones confirmadas futuras',45,48);
$instalacionesAnt = $usuario -> instAnt('instalaciones confirmadas anteriores',45,48);

$reparaciones = $usuario -> instHoy('reparaciones confirmadas',57,58);
$reparacionesFut = $usuario -> instFut('reparaciones confirmadas futuras',57,58);
$reparacionesAnt = $usuario -> instAnt('reparaciones confirmadas anteriores',57,58);

foreach ($reparaciones as $r) {
	$instalaciones[] = $r;
}
foreach ($reparacionesAnt as $r) {
	$instalacionesAnt[] = $r;
}
foreach ($reparacionesFut as $r) {
	$instalacionesFut[] = $r;
}

// print2($reparacionesFut);

// $capGral = $shopper->getCaps(true);

// print2($instalacionesAnt);
$fecha = date('Y-m-d');
// echo "fecha : $fecha<br/>";

?>

<script type="text/javascript">
	$(function() {
		$('.ingresarVisita').click(function(event) {
			var vId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId);
			// $('#content').empty();
			$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
		});

		$('.verVisita').click(function(event) {
			/* Act on the event */
			var lat = $(this).attr('lat');
			var lng = $(this).attr('lng');
			// console.log(vvId,cteId);

			popUp('lib/j/php/verUbic.php',{lat:lat,lng:lng});

		});

		$('.verHist').click(function(event) {
			/* Act on the event */
			var cteId = $(this).attr('id').split('_')[1];
			popUp('admin/proyectos/cHist.php',{cteId:cteId},function(){},{});
		});

		$('.impCuest').click(function(event) {
			var vId = $(this).attr('vId');
			var cId = $(this).closest('td').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'impacto',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}

			$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			
		});
		
		$('.evCuest').click(function(event) {
			var vId = $(this).attr('vId');
			var cId = $(this).closest('td').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'evaluacionInt',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}

			$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			
		});


	});
</script>
<div id="visAsig">
	<div class="nuevo" style="margin-top: 5px;">	
		Tienes estas instalaciones asignadas para hoy
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>ID Usuario</th>
				<th>ID Junta</th>
				<th>Usuario</th>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Dirección</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instalaciones as $v){ ?>
				<tr id="trRotAsig_<?php echo $v['id']; ?>">
					<td><?php echo $v['token']; ?></td>
					<td><?php echo $v['webId']; ?></td>
					<td><?php echo "$v[nombre] $v[aPat] $v[aMat]"; ?></td>
					<td><?php echo $v['fecha']; ?></td>
					<td><?php echo $v['horario'] == 1?'Matutino':'Vespertino'; ?></td>
					<td>
						<?php echo 
						"$v[calle] $v[numeroExt] $v[numeroInt]<br/>
						$v[colonia], <br/>$v[mNom], $v[eNom]<br/> CP: $v[codigoPostal]
						"; ?>
						
					</td>
					<td id="td_<?php echo $v['cId']; ?>">
						<?php if ($v['estatus'] >= 40 && $v['estatus'] <= 47){ ?>
							<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">
								<?php echo $v['estatus'] == 47?"Continuar":"Comenzar";?>
							</span>
						<?php }elseif ($v['estatus'] >= 57 && $v['estatus'] <= 58){ ?>
							<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">
								<?php echo $v['estatus'] == 58?"Continuar reparacion":"Comenzar reparacion"; ?>
							</span>
						<?php }else{ ?>
							<!-- <span class="btn btn-sm btn-shop evCuest" vId="<?php echo $v['vEiId']; ?>">Evaluacion interna</span> -->
							<?php if ($v['viFin'] != 1){ ?>
								<span class="btn btn-sm btn-shop impCuest" vId="<?php echo $v['viId']; ?>">Impacto</span>
							<?php } ?>
							<?php if ($v['viFin'] == 1){ ?>
								<span class="btn btn-sm btn-shop evCuest" vId="<?php echo $v['vEiId']; ?>">Evaluacion interna</span>
							<?php } ?>
						<?php } ?>
						<span class="btn btn-sm btn-shop verVisita" lat="<?php echo $v['lat']; ?>" lng="<?php echo $v['lng']; ?>">
							Ver en mapa
						</span>
						<span class="btn btn-sm btn-shop verHist" id="cte_<?php echo $v['cId'] ?>" style="margin: 10px 0px;">Historial</span>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php if (count($instalacionesAnt)>0){ ?>
	<div id="visAsigAnt">
		<div class="nuevo" style="margin-top: 5px;">	
			Tienes estas instalaciones pendientes
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>ID Usuario</th>
					<th>ID Junta</th>
					<th>Usuario</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Dirección</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($instalacionesAnt as $v){ ?>
					<tr id="trRotAsig_<?php echo $v['id']; ?>">
						<td><?php echo $v['token']; ?></td>
						<td><?php echo $v['webId']; ?></td>
						<td><?php echo "$v[nombre] $v[aPat] $v[aMat]"; ?></td>
						<td><?php echo $v['fecha']; ?></td>
						<td><?php echo $v['horario'] == 1?'Matutino':'Vespertino'; ?></td>
						<td>
							<?php echo 
							"$v[calle] $v[numeroExt] $v[numeroInt]<br/>
							$v[colonia], <br/>$v[mNom], $v[eNom]<br/> CP: $v[codigoPostal]
							"; ?>
							
						</td>
						<td id="td_<?php echo $v['cId']; ?>">
							<?php if ($v['estatus'] >= 40 && $v['estatus'] <= 47){ ?>
								<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">
									<?php echo $v['estatus'] == 47?"Continuar instalación":"Comenzar instalación"; ?>
								</span>
							<?php }elseif ($v['estatus'] >= 57 && $v['estatus'] <= 58){ ?>
								<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">
									<?php echo $v['estatus'] == 58?"Continuar reparacion":"Comenzar reparacion"; ?>
								</span>
							<?php }else{ ?>
									<!-- <span class="btn btn-sm btn-shop evCuest" vId="<?php echo $v['vEiId']; ?>">Evaluacion interna</span> -->
								<?php if ($v['viFin'] != 1){ ?>
									<span class="btn btn-sm btn-shop impCuest" vId="<?php echo $v['viId']; ?>">Impacto</span>
								<?php } ?>
								<?php if ($v['viFin'] == 1){ ?>
									<span class="btn btn-sm btn-shop evCuest" vId="<?php echo $v['vEiId']; ?>">Evaluacion interna</span>
								<?php } ?>
							<?php } ?>
							<span class="btn btn-sm btn-shop verVisita" lat="<?php echo $v['lat']; ?>" lng="<?php echo $v['lng']; ?>">
								Ver en mapa
							</span>
							<span class="btn btn-sm btn-shop verHist" id="cte_<?php echo $v['cId'] ?>" style="margin: 10px 0px;">Historial</span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>
<?php if (count($instalacionesFut)>0){ ?>
	<div id="visAsigFut">
		<div class="nuevo" style="margin-top: 5px;">	
			Tienes estas instalaciones asignadas para los siguientes días
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>ID Usuario</th>
					<th>ID Junta</th>
					<th>Usuario</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Dirección</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($instalacionesFut as $v){ ?>
					<tr id="trRotAsig_<?php echo $v['id']; ?>">
						<td><?php echo $v['token']; ?></td>
						<td><?php echo $v['webId']; ?></td>
						<td><?php echo "$v[nombre] $v[aPat] $v[aMat]"; ?></td>
						<td><?php echo $v['fecha']; ?></td>
						<td><?php echo $v['horario'] == 1?'Matutino':'Vespertino'; ?></td>
						<td>
							<?php echo 
							"$v[calle] $v[numeroExt] $v[numeroInt]<br/>
							$v[colonia], <br/>$v[mNom], $v[eNom]<br/> CP: $v[codigoPostal]
							"; ?>
							
						</td>
						<td id="td_<?php echo $v['cId']; ?>">
							<?php if ($v['estatus'] >= 40 && $v['estatus'] <= 47){ ?>
								<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">
									<?php echo $v['estatus'] == 47?"Continuar":"Comenzar"; ?>
								</span>
							<?php }elseif ($v['estatus'] >= 57 && $v['estatus'] <= 58){ ?>
								<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">
									<?php echo $v['estatus'] == 58?"Continuar reparacion":"Comenzar reparacion"; ?>
								</span>
							<?php }else{ ?>
								<!-- <span class="btn btn-sm btn-shop evCuest" vId="<?php echo $v['vEiId']; ?>">Evaluacion interna</span> -->
								<?php if ($v['viFin'] != 1){ ?>
									<span class="btn btn-sm btn-shop impCuest" vId="<?php echo $v['viId']; ?>">Impacto</span>
								<?php } ?>
								<?php if ($v['viFin'] == 1){ ?>
									<span class="btn btn-sm btn-shop evCuest" vId="<?php echo $v['vEiId']; ?>">Evaluacion interna</span>
								<?php } ?>
							<?php } ?>
							<span class="btn btn-sm btn-shop verVisita" lat="<?php echo $v['lat']; ?>" lng="<?php echo $v['lng']; ?>">
								Ver en mapa
							</span>
							<span class="btn btn-sm btn-shop verHist" id="cte_<?php echo $v['cId'] ?>" style="margin: 10px 0px;">Historial</span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>
