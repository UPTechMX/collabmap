<?php  

session_start();
include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/checklist.php';
include_once raiz().'lib/php/calcCuest.php';

// print2($_POST);

// $rot = $db->query("SELECT rot.* FROM Visitas v 
// 	LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
// 	WHERE v.id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];

$sql = "SELECT m.tipo,m.* FROM Multimedia m WHERE m.visitasId = $_POST[vId] ";
// echo $sql;
$multimedia = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
// print2($multimedia);

// print2($_POST);
$chk = new Checklist($_POST['vId']);
$vInfo = $chk->getVisita();

// print2($vInfo);
if($vInfo['etapa'] == 'visita' || $vInfo['etapa'] == 'instalacion'){
	$rot['fotografias'] = 1;
}else{
	$rot['fotografias'] = 0;
}

// print2($vInfo);
?>

<style>
  p { clear: both; }

  .audiojs { height: 22px; background: #404040;
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #444), color-stop(0.5, #555), color-stop(0.51, #444), color-stop(1, #444));
    background-image: -moz-linear-gradient(center top, #444 0%, #555 50%, #444 51%, #444 100%);
    -webkit-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); -moz-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);
    -o-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); width:300px;}
  .audiojs .play-pause { width: 15px; height: 20px; padding: 0px 8px 0px 0px; }
  .audiojs p { width: 25px; height: 20px; margin: -3px 0px 0px -1px; }
  .audiojs .scrubber { background: #5a5a5a; width: 310px; height: 10px; margin: 5px; }
  .audiojs .progress { height: 10px; width: 0px; background: #ccc;
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ccc), color-stop(0.5, #ddd), color-stop(0.51, #ccc), color-stop(1, #ccc));
    background-image: -moz-linear-gradient(center top, #ccc 0%, #ddd 50%, #ccc 51%, #ccc 100%); }
  .audiojs .loaded { height: 10px; background: #000;
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #222), color-stop(0.5, #333), color-stop(0.51, #222), color-stop(1, #222));
    background-image: -moz-linear-gradient(center top, #222 0%, #333 50%, #222 51%, #222 100%); }
  .audiojs .time { float: left; height: 25px; line-height: 25px; display:none;}
  .audiojs .error-message { height: 24px;line-height: 24px; }

  .track-details { clear: both; height: 20px; width: 448px; padding: 1px 6px; background: #eee; color: #222; font-family: monospace; font-size: 11px; line-height: 20px;
    -webkit-box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.15); -moz-box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.15); }
  .track-details:before { content: '♬ '; }
  .track-details em { font-style: normal; color: #999; }
</style>


<script type="text/javascript">
	$(document).ready(function() {
		$('#verResultados').click(function(event) {
			// $('#finalizar').trigger('click');
			$('#area_vistaGral').trigger('click');
		});

		$('#verInstalacionSug').click(function(event) {
			// $('#finalizar').trigger('click');
			$('#area_instSug').trigger('click');
		});

		$('#verInstalacion').click(function(event) {
			// $('#finalizar').trigger('click');
			$('#area_inst').trigger('click');
		});

		$('#regresarArch').click(function(event) {
			$('#pregunta').load(rz+'campo/checklist/ultimaPreg.php',{
				vId:'<?php echo $_POST['vId']; ?>'
			});
		});

		<?php if ($rot['fotografias'] == 1){ ?>
			subArch($('#subeFotografias'),1,'fotografia_<?php echo $_POST['vId']; ?>_','jpg,png,gif,jpeg',true,function(e){
				// console.log(e);
				var rj = jsonF('campo/checklist/json/json.php',{dat:e,acc:3,tipo:1,vId:<?php echo $_POST['vId']; ?>})
				console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('<li>')
					.attr({
						class:'list-group-item imgEle',
						id:'imgEle_'+r.nId
					})
					.appendTo('#imgList')
					.html(	
						'<div class="row">'+
							'<div class="col-md-10" id="imgNom_'+r.nId+'">'+
								'<img  class="verImg manita" src="../campo/archivosCuest/'+e.prefijo+e.nombreArchivo+'" height="100px"/>'+
							'</div>'+
							'<div class="col-md-2" style="text-align: right;">'+
								'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
									'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
							'</div>'+
						'</div>'
					)
				}

			});
			
			$('.verImg').click(function(event) {
				verImagen($(this).attr('src'));
			});

		<?php } ?>

		<?php if($vInfo['etapa'] == 'instalacion'){  ?>

			<?php foreach ($fotosInst as $id => $nombre): ?>
				subArchInst('<?php echo $id; ?>',<?php echo $vInfo['id']; ?>)
			<?php endforeach ?>
		<?php } ?>


		$('#multimedia').on('click', '.multDel', function(event) {
			// console.log('aa');
			event.preventDefault();
			var multId = this.id.split('_')[1]
			conf('¿Seguro que deseas eliminar la fotografía?',{multId:multId,ele:$(this)},function(e){

				var rj = jsonF('campo/checklist/json/json.php',{acc:4,vId:<?php echo $_POST['vId'];?>,mId:e.multId});
				console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					if('instalacion' == '<?php echo $vInfo['etapa']; ?>'){
						e.ele.closest('tr').find('.imgSubArch').show();
						e.ele.closest('tr').find('.imgCont').hide();
					}

					e.ele.closest('.imgEle').remove();
				}
			})
		});
		$('#permisoImagen').change(function(event) {
			var dat = {};
			dat.permiso = $(this).is(':checked')?1:0;
			dat.id = <?php echo $vInfo['clientesId']; ?>;
			var vId = <?php echo $_POST['vId']; ?>;
			// console.log(dat);
			var rj = jsonF('campo/checklist/json/json.php',{dat:dat,acc:7,vId:vId});

			// console.log(rj);

		});
	});
	function subArchInst(idFoto,vId){
		var elem = $('#subArch_'+idFoto);
		subArch(elem,1,'fotografia_'+vId+'_'+idFoto+'_','jpg,png,gif,jpeg',false,function(e){
			var rj = jsonF('campo/checklist/json/json.php',{dat:e,acc:3,tipo:1,vId:<?php echo $_POST['vId']; ?>})
			console.log(rj);
			var r = $.parseJSON(rj);
			console.log(idFoto,'fotografia_'+vId+'_'+idFoto+'_');
			if(r.ok == 1){
				$('#subArch_'+idFoto).closest('tr').find('.imgSubArch').hide();
				$('#subArch_'+idFoto).closest('tr').find('.imgCont').show();
				$('#subArch_'+idFoto).closest('tr').find('.imgCont').empty();
				$('<div>')
				.attr({
					class:'imgEle',
					id:'imgEle_'+r.nId
				})
				.appendTo($('#subArch_'+idFoto).closest('tr').find('.imgCont'))
				.html(	
					'<div class="row">'+
						'<div class="col-md-10" id="imgNom_'+r.nId+'">'+
							'<img src="'+rz+'campo/archivosCuest/'+e.prefijo+e.nombreArchivo+'" class="verImg" height="100px"/>'+
						'</div>'+
						'<div class="col-md-2" style="text-align: right;">'+
							'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
								'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
						'</div>'+
					'</div>'
				)

			}

		})
	}

</script>
<div id="multimedia">
	<?php if ($rot['fotografias'] == 1){ ?>
		<div>
			<div class="nomSubArea">Archivos fotográficos</div>
			<div class="row" style="margin-top: 5px;">
				<?php if ($vInfo['etapa'] != 'instalacion'){ ?>				
					<div class="col-md-6">
						<span id="subeFotografias"></span>
					</div>
					<div class="col-md-6">
						<ul id="imgList" class="list-group">
							<?php 
								$multimedia['img'] = isset($multimedia['img'])?$multimedia['img']:array();

								foreach ($multimedia['img'] as $i){
							?>
								<li class="list-group-item imgEle" id="imgEle_<?php echo $i['id'];?>">
									<div class="row">
										<div class="col-md-10" id="imgNom_<?php echo $i['id'];?>">
											<img  class='verImg manita' src="../campo/archivosCuest/<?php echo $i['archivo'];?>" height="100px"/>
										</div>
										<div class="col-md-2" style="text-align: right;">
											<i class="glyphicon glyphicon-trash manita multDel rojo" 
												id="imgDel_<?php echo $i['id'];?>"></i>&nbsp;&nbsp;
										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
				<div class="col-6">
					<?php if ($vInfo['etapa'] == 'instalacion'){ ?>
						<div>
							<input type="checkbox" id="permisoImagen" <?php echo $vInfo['permiso'] == 1?'checked':'' ?>>
							<span>¿El usuario permite el uso de su imágen para fines de difusión y promoción del proyecto?</span>
						</div>
						<br/>
						<table border="0">
							<?php foreach ($fotosInst as $id => $nombre){ ?>
								<?php 
									$sql = "SELECT * 
									FROM Multimedia 
									WHERE archivo LIKE 'fotografia_".$vInfo['id']."_$id"."_%' ";

									$img = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
								?>
								<tr>
									<td>
										<?php echo $nombre; ?><br/>
									</td>
									<td class="imgSubArch"
									style="display:<?php echo empty($img)?'block':'none'; ?>">
										<span id="subArch_<?php echo $id; ?>"></span>
									</td>

									<td id="img_<?php echo $id; ?>" class="imgCont"
									style="display:<?php echo !empty($img)?'block':'none'; ?>" >
										<div class="imgEle" id="imgEle_<?php echo $i['id'];?>">							
											<div class="row">
												<div class="col-md-10" id="imgNom_<?php echo $img['id'];?>">
													<img src="./archivosCuest/<?php echo $img['archivo'];?>" 
														height="100px" class="verImg" />
												</div>
												<div class="col-md-2" style="text-align: right;">
													<i class="glyphicon glyphicon-trash manita multDel rojo" 
														id="imgDel_<?php echo $img['id'];?>"></i>&nbsp;&nbsp;
												</div>
											</div>
										</div>

									</td>
								</tr>
							<?php } ?>
						</table>
					<?php } ?>
				</div>
			</div>

		</div>
	<?php } ?>
	<?php if ( empty($rot['fotografias']) && empty($rot['audio']) && empty($rot['video']) ){ ?>
		<div class="nomSubArea">
			Este cuestionario no requiere subir archivos, da click en "Vista previa" para ver tus respuestas y finalizar.
		</div>
	<?php } ?>
</div>



<div style="text-align:center;width: 96%;margin-top: 5px;">
	<span id="regresarArch" class="btn btn-sm btn-shop">< Regresar</span>	
	<?php if ($vInfo['etapa'] == 'visita'){ ?>
		<span id="verInstalacionSug" class="btn btn-sm btn-shop">Siguiente ></span>
	<?php }elseif($vInfo['etapa'] == 'instalacion'){ ?>
		<span id="verInstalacion" class="btn btn-sm btn-shop">Siguiente ></span>
	<?php }else{ ?>
		<span id="verResultados" class="btn btn-sm btn-shop">Vista previa ></span>
	<?php } ?>
</div>





