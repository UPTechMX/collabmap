<?php  
session_start();
if(!function_exists('raiz')){
	include_once '../../lib/j/j.func.php';
}
// print2($_SESSION);
include_once raiz().'lib/php/campo.php';
$uId = $_SESSION['IU']['admin']['usrId'];
// print2($uId);
// echo "usuario : $uId<br/>";
$usuario = new campo($uId);


$visitas = $usuario -> visHoy('visitas confirmadas',32,37);
$visitasFut = $usuario -> visFut('visitas confirmadas futuras',32,37);
$visitasAnt = $usuario -> visAnt('visitas confirmadas anteriores',32	,37);

// $capGral = $shopper->getCaps(true);

// print2($visitas);
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

		$('.reagendarVis').click(function(event) {
			var vId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId);
			popUp('campo/visitas/reagendar.php',{'vId':vId,act:'agendar_visita_re'}, function(e){},{});
			// $('#content').empty();
			// $('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
		});

		$('.cancelarVisita').click(function(event) {
			var vId = $(this).closest('tr').attr('id').split('_')[1];
			popUp('campo/visitas/cancelaVis.php',{vId:vId},function(){});
		});

		$(".tomaCap").click(function(event) {
			var capId = this.id.split('_')[1];
			$('#content').load(rz+'campo/capacitaciones/tomaCap.php',{capId: capId});
			
		});

		$('.verVisita').click(function(event) {
			/* Act on the event */
			var lat = $(this).attr('lat');
			var lng = $(this).attr('lng');
			// console.log(vvId,cteId);

			popUp('lib/j/php/verUbic.php',{lat:lat,lng:lng});

		});

		$('.verInfoUsr').click(function(event) {
			/* Act on the event */
			event.preventDefault();
			var cteId = this.id.split('_')[1];

			popUp('admin/proyectos/cteInfo.php',{eleId:cteId},function(){},{});

		});



	});
</script>
<div id="visAsig">
	<div class="nuevo" style="margin-top: 5px;">	
		Tienes estas visitas asignadas para hoy
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
			<?php foreach ($visitas as $v){ ?>
				<tr id="trRotAsig_<?php echo $v['id']; ?>">
					<td><?php echo $v['token']; ?></td>
					<td><?php echo $v['webId']; ?></td>
					<td><?php echo "$v[nombre] $v[aPat] $v[aMat]"; ?></td>
					<td><?php echo $v['fecha']; ?></td>
					<td><?php echo $v['hora']; ?></td>
					<td>
						<?php echo 
						"$v[calle] $v[numeroExt] $v[numeroInt]<br/>
						$v[colonia], <br/>$v[mNom], $v[eNom]<br/> CP: $v[codigoPostal]
						"; ?>
						
					</td>
					<td>
						<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">Visitar</span>
						<span class="btn btn-sm btn-shop verVisita" lat="<?php echo $v['lat']; ?>" lng="<?php echo $v['lng']; ?>">
							Ver en mapa
						</span>
						<br/>
						<span id="verInfo_<?php echo $v['cId'];?>" class="btn btn-shop btn-sm verInfoUsr" >Ver información del usuario</span>
						<br/>
						<span id="reagendarVis" class="btn btn-shop btn-sm reagendarVis" style="margin-top:10px">Reagendar visita</span>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php if (count($visitasAnt)>0){ ?>
	<div id="visAsigAnt">
		<div class="nuevo" style="margin-top: 5px;">	
			Tienes estas visitas pendientes
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
				<?php foreach ($visitasAnt as $v){ ?>
					<tr id="trRotAsig_<?php echo $v['id']; ?>">
						<td><?php echo $v['token']; ?></td>
						<td><?php echo $v['webId']; ?></td>
						<td><?php echo "$v[nombre] $v[aPat] $v[aMat]"; ?></td>
						<td><?php echo $v['fecha']; ?></td>
						<td><?php echo $v['hora']; ?></td>
						<td>
							<?php echo 
							"$v[calle] $v[numeroExt] $v[numeroInt]<br/>
							$v[colonia], <br/>$v[mNom], $v[eNom]<br/> CP: $v[codigoPostal]
							"; ?>
							
						</td>
						<td>
							<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">Visitar</span>
							<span class="btn btn-sm btn-shop verVisita" lat="<?php echo $v['lat']; ?>" lng="<?php echo $v['lng']; ?>">
								Ver en mapa
							</span>
							<br/>
							<span id="verInfo_<?php echo $v['cId'];?>" class="btn btn-shop btn-sm verInfoUsr" >Ver información del usuario</span>
							<br/>
							<span id="reagendarVis" class="btn btn-shop btn-sm reagendarVis" style="margin-top:10px">Reagendar visita</span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>
<?php if (count($visitasFut)>0){ ?>
	<div id="visAsigFut">
		<div class="nuevo" style="margin-top: 5px;">	
			Tienes estas visitas asignadas para los siguientes días
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
				<?php foreach ($visitasFut as $v){ ?>
					<tr id="trRotAsig_<?php echo $v['id']; ?>">
						<td><?php echo $v['token']; ?></td>
						<td><?php echo $v['webId']; ?></td>
						<td><?php echo "$v[nombre] $v[aPat] $v[aMat]"; ?></td>
						<td><?php echo $v['fecha']; ?></td>
						<td><?php echo $v['hora']; ?></td>
						<td>
							<?php echo 
							"$v[calle] $v[numeroExt] $v[numeroInt]<br/>
							$v[colonia], <br/>$v[mNom], $v[eNom]<br/> CP: $v[codigoPostal]
							"; ?>
							
						</td>
						<td>
							<span class="btn btn-sm btn-shop ingresarVisita" style="margin: 10px 0px;">Visitar</span>
							<span class="btn btn-sm btn-shop verVisita" lat="<?php echo $v['lat']; ?>" lng="<?php echo $v['lng']; ?>">
								Ver en mapa
							</span>
							<br/>
							<span id="verInfo_<?php echo $v['cId'];?>" class="btn btn-shop btn-sm verInfoUsr" >Ver información del usuario</span>
							<br/>
							<span id="reagendarVis" class="btn btn-shop btn-sm reagendarVis" style="margin-top:10px">Reagendar visita</span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>
