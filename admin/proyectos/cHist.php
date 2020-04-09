<?php

include_once '../../lib/j/j.func.php';
// print2($_POST);

$hist = $db->query("SELECT h.*, ua.username, e.nombre as eNom, v.finalizada,
	h.comentario, h.visitasId, CONCAT(ua.nombre,' ',ua.aPat) as uaNom
	FROM estatusHist h
	LEFT JOIN usrAdmin ua ON ua.id = h.usuarioId
	LEFT JOIN Estatus e ON e.id = h.estatus
	LEFT JOIN Visitas v ON v.id = h.visitasId
	WHERE h.clientesId = $_POST[cteId]
	ORDER BY timestamp,h.id")->fetchAll(PDO::FETCH_ASSOC);
	// $estatus[] = array('val'=>0,"nom"=>'Asignar');
	// print2($_POST);
?>

<script type="text/javascript">
	$(function() {
		$('#updEstatus').click(function(event) {
			// console.log('a');
			var dat = {};
			dat.est = $('#estSelUpd').val();
			// console.log(dat);
			if(dat.est != ''){			
				var rj = jsonF('admin/proyectos/json/json.php',{acc:13,dat:dat});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					// $('#tr_'+dat.rotId).empty();
					$('#tr_'+dat.rotId).load(rz+'admin/proyectos/rotacionFila.php',{rotId: dat.rotId},function(){});
					$('#indicadores').load(rz+'admin/proyectos/rotNumerotes.php',{repId:r.repId})
					$('#popUp').modal('toggle');
				}
			}
		});

		$('.verVis').click(function(event) {
			var vId =  $(this).attr('vId');
			// console.log(vId);
			popUpMapa('admin/proyectos/verCuest.php',{vId:vId},function(){},{});
		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4>
			Historial del cliente
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;max-height: 400px;overflow-y: auto;'>
	<br/>
	<table class="table" border="0">
		<thead>			
			<tr>
				<th>Fecha/Hora</th>
				<th style="text-align: left;">Estatus</th>
				<th style="text-align: left;">Usuario</th>
				<th style="text-align: left;">Comentarios</th>
				<th style="text-align: left;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($hist as $h){ ?>
				<tr>
					<td><?php echo $h['timestamp'] ?></td>
					<td style="text-align: left;"><?php echo $h['eNom'] ?></td>
					<td style="text-align: left;"><?php echo $h['uaNom'] ?></td>
					<td style="text-align: justify;"><?php echo $h['comentario'] ?></td>
					<td style="text-align: justify;">
						<?php if ( !empty($h['visitasId']) && ($h['estatus'] == 38 || $h['estatus'] == 48 ) || $h['finalizada'] == 1){ ?>
							<i class="glyphicon glyphicon-folder-open verVis" vId="<?php echo $h['visitasId'];?>"></i>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>		
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
	</div>
</div>


