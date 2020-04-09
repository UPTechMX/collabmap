<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(49);

	$proyectos = $db->query("SELECT * FROM Proyectos WHERE (inactivo != 1 OR inactivo IS NULL)")->fetchAll(PDO::FETCH_ASSOC);
	// print2($proyectos);

?>

<script type="text/javascript" src="../lib/js/mapa.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.verPry').click(function(event) {
			var pryId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(pryId);
			$('#clientesList').load(rz+'admin/proyectos/clientesList.php',{pryId:pryId});
			$('#indicadores').load(rz+'admin/proyectos/rotNumerotes.php',{pryId:pryId});
			$('#informeFond').load(rz+'informes/getParaReporte.php',{proy:pryId});
			$('#tabs').show();

		});
	});
</script>


<div class="row">
	<div class="col-5">
		<div class="nuevo mayusculas">Proyectos</div>
		<div>
			<table class="table">
				<?php foreach ($proyectos as $p){ ?>
					<tr id="pry_<?php echo $p['id'];?>">
						<td>
							<?php if (!empty($p['logotipo'])){ ?>
								<img src="../archivos/imgPry/<?php echo $p['logotipo']; ?>" style="height: 50px;">
							<?php } ?>
						</td>
						<td><?php echo $p['nombre']; ?></td>
						<td style="text-align: right;">
							<i class="glyphicon glyphicon-chevron-right manita verPry"></i>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<div class="col-7" id="datosPry">
		<div id="indicadores"></div>
	</div>
</div>
<ul class="nav nav-tabs" style="display: none;" id="tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="pill" href="#usuarios">Usuarios</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="pill" href="#indorme">Resumen</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane  active" id="usuarios"><div id="clientesList"></div></div>
  <div class="tab-pane  fade" id="indorme"><div id="informeFond"></div></div>
</div>


<br><br>

