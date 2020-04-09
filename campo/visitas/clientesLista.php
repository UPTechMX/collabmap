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

		$sql = "SELECT c.*, vv.id as vvId, vv.estatus as vvEstatus, vv.finalizada as vvFin, c.lat, c.lng
			FROM Clientes c
			LEFT JOIN Visitas vv ON vv.clientesId = c.id AND vv.etapa = 'visita' 
				AND vv.id = (SELECT id FROM Visitas z 
					WHERE z.clientesId = vv.clientesId 
					AND z.etapa = 'visita' 
					ORDER BY z.fechaRealizacion DESC 
					LIMIT 1)
			WHERE 1 $where
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

		$('.creaVisita').click(function(event) {
			// console.log('aa');
			var vId = $(this).attr('vId');
			var cId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(vId.length,typeof vId, cId);
			if(vId.length == 0){
				// console.log('cId');
				var rj = jsonF('campo/instalaciones/json/json.php',{cId:cId,etapa:'visita',acc:1});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					vId = r.nId;
				}
			}
			// console.log(vId);

			// popUpCuest('admin/proyectos/respCuest.php',{vId:vId},function(){})
			setTimeout(function(){
				$('#content').load(rz+'campo/checklist/cuestionario.php',{vId:vId} ,function(){});
			},100);

		});

	});
</script>

<table class="table">
	<?php foreach ($clientes as $c){ ?>
		<tr id="<?php echo "cte_$c[id]"; ?>">
			<td><?php echo "$c[nombre] $c[aPat] $c[aMat] "; ?></td>
			<td>
				<?php echo !empty($c['vvId'])?'Ya existe una visita realizada':'';  ?>
			</td>
			<td>
				<?php if (!empty($c['vvId'])){ ?>
					
				<span class="btn btn-sm btn-shop creaVisita" style="margin: 10px 0px;" vId = "<?php echo $c['vvId']; ?>">Editar</span>
				<?php }else{ ?>
				<span class="btn btn-sm btn-shop creaVisita" style="margin: 10px 0px;" vId = "<?php echo $c['vvId']; ?>">Visitar</span>
				<?php } ?>
				<span class="btn btn-sm btn-shop verCte" lat="<?php echo $c['lat']; ?>" lng="<?php echo $c['lng']; ?>">
					Ver en mapa
				</span>
			</td>
		</tr>
	<?php } ?>
</table>