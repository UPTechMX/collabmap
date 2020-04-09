<?php  

session_start();
include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/checklist.php';
include_once raiz().'lib/php/calcCuest.php';


// print2($_POST);
// exit();
$chk = new Checklist($_POST['vId']);
$vInfo = $chk->getVisita();
$cteInfo = $db->query("SELECT * FROM Clientes WHERE id = $vInfo[clientesId]")->fetchAll(PDO::FETCH_ASSOC)[0];

$instalaciones = $db->query("SELECT i.id, i.nombre, i.costo
	FROM Instalaciones i 
	WHERE proyectosId = $vInfo[proyectosId]
	ORDER BY i.nombre")->fetchAll(PDO::FETCH_ASSOC);

$componentes = $db->query("SELECT cc.dimensionesElemId,cc.* FROM ClientesComponentes cc
	WHERE clientesId = $vInfo[clientesId]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

// print2($componentes);
?>

<script type="text/javascript">
	$(document).ready(function() {

		$("input[name='instalacionRealizada']").change(function(event) {
			var instalacionRealizada = $("input[name='instalacionRealizada']:checked").val();
			$('.cantidad').hide();
			$('#formEq_'+instalacionRealizada+' .cantidad').show();
		});

		$("input[name='instalacionRealizada']").trigger('change');

		$('#verResultados').click(function(event) {
			// $('#finalizar').trigger('click');
			var instalacionRealizada = $("input[name='instalacionRealizada']:checked").val();
			if(typeof instalacionRealizada == 'undefined'){
				alertar('Debes seleccionar un tipo de instalación',function(){},{})
			}else{

				allOk = camposObligatorios('#formEq_'+instalacionRealizada);
				var componentes = $('#formEq_'+instalacionRealizada).serializeObject();

				// console.log(componentes)
				if(allOk){				
					var rj = jsonF('campo/checklist/json/json.php',{
							cId:<?php echo $cteInfo['id']; ?>,
							instalacionRealizada:instalacionRealizada,acc:6,opt:2,
							costo:$('#formEq_'+instalacionRealizada+' #costo').val(),
							vId:'<?php echo $_POST['vId']; ?>',
							componentes:componentes
						})
					try{
						var r = $.parseJSON(rj);
						console.log(r);
					}catch(e){
						console.log('Error de parseo');
						console.log(rj);
						var r = {ok:0};
					}
					if(r.ok == 1){
						$('#area_vistaGral').trigger('click');
					}
				}else{
					alertar('Debes especificar las cantidades de los elementos instalados',function(){},{})
				}
			}
		});

		$('#regresarInstSug').click(function(event) {
			$('#area_archivos').trigger('click');
		});

	});
</script>

<div class="nuevo" style="margin-top: 10px;">Instalaciones disponibles en este proyecto</div>
<?php foreach ($instalaciones as $f){ ?>
	<div class="row" style="margin: 8px 0px;border:none 1px;">
		<div class="col-2" style="text-align: justify;border:none 1px;">
			<input type="radio" name="instalacionRealizada" 
				value="<?php echo $f['id']; ?>" <?php echo $f['id'] == $cteInfo['instalacionRealizada']?'checked':''; ?> />
			<strong><?php echo $f['nombre']; ?></strong>
		</div>
		<div class="col-10" style="border:none 1px;">
			<form id="formEq_<?php echo $f['id'];?>">
				<?php  
				$equipos = $db -> query("SELECT * FROM InstalacionesEquipos 
					WHERE instalacionesId = $f[id]")->fetchAll(PDO::FETCH_ASSOC);
				foreach ($equipos as $e){ 
					$datEle = datosEquip($e['dimensionesElemId']);
					// print2($datEle);
					$html = "<strong> $datEle[area] :</strong><br/>";
					$arbol = $datEle['arbol'];
					for ($i=$datEle['numDim']-1; $i >= 0; $i--) { 

						// echo $i."<br/>";
						$html .= $arbol["d$i"]." : ".$arbol["de$i"];
						if ($i==0) {
							continue;
						}
						$html .= "&nbsp;<i class='glyphicon glyphicon-chevron-right'></i>&nbsp;";
					}
					if ($datEle['arbol']['variables'] == 1) {
						$unidad = $datEle['arbol']['unidad'];
						$html .= "<input type='text' class='form-control oblig cantidad' placeholder='$unidad' style='display:none;'
						name='eq_$e[dimensionesElemId]' value='".$componentes[$e['dimensionesElemId']][0]['cantidad']."' />";
					}
				?>
					<?php echo "$html<br/><br/>" ?>
				<?php } ?>
				<input type="hidden" id="costo" value="<?php echo $f['costo'] ?>">
			</form>
		</div>
	</div>
	<hr/>
<?php } ?>



<div style="text-align:center;width: 96%;margin-top: 5px;">
	<span id="regresarInstSug" class="btn btn-sm btn-shop">< Regresar</span>	
	<span id="verResultados" class="btn btn-sm btn-shop">Vista previa ></span>
</div>