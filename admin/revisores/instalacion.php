<?php

session_start();

include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';

// print2($_POST);

$chk = new Checklist($_POST['vId']);
$chk->getGeneral($_POST['vId']);
$chk->getVisita($_POST['vId']);

$vInfo = $chk -> getVisita();

$instalaciones = $db->query("SELECT i.id, i.nombre, i.costo
	FROM Instalaciones i 
	WHERE proyectosId = $vInfo[proyectosId]
	ORDER BY i.nombre")->fetchAll(PDO::FETCH_ASSOC);


// print2($vInfo);

$instSel = $vInfo['etapa'] == 'instalacion' ? $vInfo['instalacionRealizada'] : $vInfo['instalacionSug'];

$componentes = $db->query("SELECT cc.dimensionesElemId,cc.* FROM ClientesComponentes cc
	WHERE clientesId = $vInfo[clientesId]")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);


?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.oblig').keyup(function(event) {
			$(this).prev('.livespell_textarea').css({backgroundColor:'rgba(255,255,255,1)'});
			$(this).css({backgroundColor:'rgba(255,255,255,1)'});
		});

		$( "#fecha" ).datepicker({ changeYear: true });
		$( "#fecha" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
		// $('#fecha').val('<?php echo $chk->visita['fecha']; ?>');

		$('#env').click(function(event) {
			var instalacionRealizada = $("input[name='instalacionRealizada']:checked").val();
			if(typeof instalacionRealizada == 'undefined'){
				alertar('Debes seleccionar un tipo de instalación',function(){},{})
			}else{

				allOk = camposObligatorios('#formEq_'+instalacionRealizada);
				var componentes = $('#formEq_'+instalacionRealizada).serializeObject();

				console.log(componentes)
				if(allOk){				
					var rj = jsonF('admin/revisores/json/json.php',{
							cId:<?php echo $vInfo['clientesId']; ?>,
							instalacionRealizada:instalacionRealizada,acc:6,opt:2,
							costo:$('#formEq_'+instalacionRealizada+' #costo').val(),
							vId:'<?php echo $_POST['vId']; ?>',
							componentes:componentes,
							etapa:"<?php echo $vInfo['etapa']; ?>",
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
						$('#visita').load(rz+'admin/proyectos/revision.php',{
							vId:<?php echo $_POST['vId'];?>,
							hash:'<?php echo $_POST['hash']; ?>',
							rev:1,
							
						});
						setTimeout(function(){
							$('#popUp').modal('toggle');
						},500)

					}
				}else{
					alertar('Debes especificar las cantidades de los elementos instalados',function(){},{})
				}
			}
		});

		<?php if ($vInfo['etapa'] == 'instalacion') { ?>

			$("input[name='instalacionRealizada']").change(function(event) {
				var instalacionRealizada = $("input[name='instalacionRealizada']:checked").val();
				$('.cantidad').hide();
				$('#formEq_'+instalacionRealizada+' .cantidad').show();
			});

			$("input[name='instalacionRealizada']").trigger('change');

		<?php } ?>

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>
			Datos generales
		</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<!-- <br/><?php print2($vInfo); ?> -->
<?php foreach ($instalaciones as $f){ ?>
	<div class="row" style="margin: 8px 0px;border:none 1px;">
		<div class="col-2" style="text-align: justify;border:none 1px;">

			<input type="radio" name="instalacionRealizada" 
				value="<?php echo $f['id']; ?>" <?php echo $f['id'] == $instSel?'checked':''; ?> />
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

</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
