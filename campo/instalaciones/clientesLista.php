<?php  

	if (!function_exists('raiz')) {
		include_once '../../lib/j/j.func.php';
	}

	checaAcceso(30);
	// print2($_POST);

	if(empty($_POST)){
		$clientes = array();
	}else{


		$where = '';
		foreach ($_POST['paramsBusq'] as $k => $p) {
			if($k == 'nombre'){
				$_POST['paramsBusq']['token'] = $_POST['paramsBusq']['nombre'];
				$where .= " AND (CONCAT( IFNULL(c.nombre,''),' ',IFNULL(c.aPat,''),' ',IFNULL(c.aMat,'') ) LIKE :nombre OR c.token LIKE :token) ";
			}else{
				// echo "strpos $p : ".strpos($p, '%') ."<br/>";
				if(strpos($p, '%') !== false){
					$where .= " AND c.$k LIKE :$k";
				}else{
					$where .= " AND c.$k = :$k";
				}
			}
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
			WHERE 1 $where
			GROUP BY c.id
		";
		$ctesPre = $db->prepare($sql);

		// print2($sql);
		// print2($_POST['paramsBusq']);
		$ctesPre -> execute($_POST['paramsBusq']);
		$clientes = $ctesPre -> fetchAll(PDO::FETCH_ASSOC);
	}

	// print2($clientes);
	
?>


<script type="text/javascript">
	$(document).ready(function() {
		$('.verCte').click(function(event) {
			/* Act on the event */
			var lat = $(this).attr('lat');
			var lng = $(this).attr('lng');
			// console.log(vvId,cteId);

			popUp('lib/j/php/verUbic.php',{lat:lat,lng:lng});

		});

		$('.instalar').click(function(event) {
			// console.log('aa');
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId, cId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'instalacion',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}
			console.log(vId);

			// popUpCuest('admin/proyectos/respCuest.php',{vId:vId},function(){})
			setTimeout(function(){
				$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			},100);

		});


		$('#bodyCtes').on('click', '.verAvComp', function(event) {

			var cteId = $(this).closest('tr').attr('id').split('_')[1];
			var vvId = this.id.split('_')[1];
			// console.log(vvId,cteId);

			popUp('admin/proyectos/verAvComp.php',{cteId:cteId,vId:vvId});

		});

		$('#bodyCtes').on('click', '.verUbic', function(event) {

			var lat = $(this).attr('lat');
			var lng = $(this).attr('lng');
			// console.log(vvId,cteId);

			popUp('lib/j/php/verUbic.php',{lat:lat,lng:lng});

		});

		$('#bodyCtes').on('click', '.histRot', function(event) {
			event.preventDefault();
			var cteId = this.id.split('_')[1];
			// console.log(cteId);
			popUp('admin/proyectos/cHist.php',{cteId:cteId},function(){},{});
		});

		$('#bodyCtes').on('click', '.impCuest', function(event) {
			event.preventDefault();
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId,cId);
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
		
		$('#bodyCtes').on('click', '.evCuest', function(event) {
			event.preventDefault();
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
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

		$('#bodyCtes').on('click', '.reparar', function(event) {
			event.preventDefault();
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'reparacion',acc:1});
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

<table class="table">
	<tbody id="bodyCtes">
		<?php foreach ($clientes as $r){ ?>
			<tr id="<?php echo "cte_$r[id]"; ?>"><?php include 'clienteFila.php'; ?></tr>
		<?php } ?>
	</tbody>
</table>