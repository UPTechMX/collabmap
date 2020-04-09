<?php  

session_start();
include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/checklist.php';
include_once raiz().'lib/php/calcCuest.php';
$chk = new Checklist($_POST['vId']);
$vInfo = $chk->getVisita();

// $rot = $db->query("SELECT rot.* FROM Visitas v 
// 	LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
// 	WHERE v.id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];
// print2($rot);

$sql = "SELECT m.tipo,m.* FROM Multimedia m WHERE m.visitasId = $_POST[vId] ";
$allMultimedia = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
if($vInfo['etapa'] == 'visita' || $vInfo['etapa'] == 'instalacion'){
	$rot['fotografias'] = 1;
}
$allMult = true;
// print2($rot);
if($rot['fotografias'] == 1 && !is_array($allMultimedia['img'])){
	$allMult = false;
}

$vis = $vInfo;

if($vis['etapa'] == 'visita'){
	$inst = $db->query("SELECT instalacionSug FROM Clientes WHERE id = $vis[clientesId]")->fetchAll(PDO::FETCH_NUM)[0][0];
}elseif($vis['etapa'] == 'instalacion'){
	$inst = $db->query("SELECT instalacionRealizada FROM Clientes WHERE id = $vis[clientesId]")->fetchAll(PDO::FETCH_NUM)[0][0];
}
$instOk = !empty($inst);
?>


<div class="divVisita">
	<?php include 'visita.php'; ?>
</div>
<?php $p = $_SESSION['IU']['chk'][$_POST['vId']]['res'][$faltaPreg]; ?>

<div style="text-align:center;width: 96%;margin-top: 5px;">
	<span id="regresar" class="btn btn-sm btn-shop">< Regresar</span>	
	<span id="Enviar" class="btn btn-sm btn-shop">Enviar respuestas</span>
</div>
<?php
?>
<script type="text/javascript">
	$(function() {
		$('#regresar').click(function(event) {
			<?php if ($vInfo['etapa'] == 'visita'){ ?>
				$('#area_instSug').trigger('click');
			<?php }elseif($vInfo['etapa'] == 'instalacion'){ ?>
				$('#area_inst').trigger('click');
			<?php }else{ ?>
				$('#area_archivos').trigger('click');
			<?php } ?>

		});
		$('#Enviar').click(function(event) {

			

			<?php if ($faltaPreg != null){ ?>
				alertar('Falta alguna pregunta por contestar, se te enviará a la primera faltante para que sea contestada',function(e){
					$('#pregunta').load(rz+'campo/checklist/pregunta.php',{
						pId: '<?php echo $faltaPreg; ?>',
						aId: '<?php echo $p['area']; ?>',
						chkId:'<?php echo $chk->id; ?>',
						vId:'<?php echo $_POST['vId']; ?>',
						abId:'a_<?php echo $p['bloque']; ?>',
						direccion:'regresar'
					} ,function(){});

				},{})
				// alert('Falta alguna pregunta por contestar, se te enviará a la primera faltante para que sea contestada');
				

			<?php }elseif( ($vis['etapa'] == 'visita' || $vis['etapa'] == 'instalacion') && !$instOk){ ?>
				var c = alertar('Falta seleccionar instalación',function(){},{});
			<?php }else{ ?>
				
				<?php if (!$allMult){ ?>
					var c = alertar('Faltan archivos multimedia',function(){},{});
				<?php }else{ ?>
					var vis = <?php echo atj($vis); ?>;

					if(parseInt(vis.estatus) < 40){

						var html = `
							Las respuestas de esta visita serán enviadas para revisión y ya no podrán ser modificadas.<br/>
							Cualquier comentario un ejecutivo se pondrá en conctacto contigo.<br/>
							<hr/>
							<div>
								<span>¿El cliente es viable para instalación?</span>
								<select id="viable" class="form-control">
									<option value="">- - Selecciona una opción - - </option>
									<option value="1">Sí</option>
									<option value="0">No</option>
								</select>
							</div>
							<div style="margin-top:10px;">
								<div class="nuevo">Comentarios</div>
								<textarea id="comentarios" class="form-control"></textarea>
							</div>
						`;
					}else{
						var html = `
							Las respuestas de esta visita serán enviadas para revisión y ya no podrán ser modificadas.<br/>
							Cualquier comentario un ejecutivo se pondrá en conctacto contigo.<br/>
							<hr/>
						`;

					}
					conf(html,{vis:vis}, function(e){
							var vId = '<?php echo $_POST['vId']; ?>';
							if(parseInt(e.vis.estatus) < 40){
								var viable = $('#viable').val();
								var comentarios = $('#comentarios').val();
							}else{
								var viable = 1;
							}
							var vInfo = <?php echo atj($vInfo); ?>;
							if(viable != ''){
								// console.log('aaa');
								var rj = jsonF('campo/checklist/json/json.php',{vId:vId,acc:1,opt:1,viable:viable,comentarios:comentarios});
								// console.log(rj);
								try{
									var r = $.parseJSON(rj);
								}catch(e){
									console.log('Error de parseo');
									console.log(rj);
									var r = {ok:0};
								}
								if(r.ok == 1){
									if(vInfo.etapa == 'seguimientoTel'){
										$('#popUpCuest').modal('hide');
									}else{
										location.reload();
									}
								}else{
									console.log(r);
								}
							}
						})
				<?php } ?>

			<?php } ?>

		});

	});
</script>

