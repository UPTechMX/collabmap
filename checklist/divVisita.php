<?php  
session_start();
include_once '../lib/j/j.func.php';
include_once raiz().'lib/php/checklist.php';
include_once raiz().'lib/php/calcCuest.php';
$chk = new Checklist($_POST['vId']);
$vInfo = $chk->getVisita();

if($vInfo['type'] == 'trgt'){
	$tInfo = $db->query("SELECT * FROM TargetsElems WHERE id = $vInfo[elemId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($tInfo);

}
// print2($vInfo);
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

?>


<div class="divVisita">
	<?php include 'visita.php'; ?>
</div>
<?php $p = $_SESSION['CM']['chk'][$_POST['vId']]['res'][$faltaPreg]; ?>

<div style="text-align:center;width: 96%;margin-top: 5px;">
	<span id="regresar" class="btn btn-sm btn-shop">< <?php echo TR('back'); ?></span>	
	<span id="Enviar" class="btn btn-sm btn-shop"><?php echo TR('sendAnswers'); ?></span>
</div>
<?php
?>
<script type="text/javascript">
	$(function() {
		$('#regresar').click(function(event) {
			$('#area_archivos').trigger('click');

		});
		$('#Enviar').click(function(event) {			

			<?php if ($faltaPreg != null){ ?>
				alertar('<?php echo TR('missAnswer') ?>',function(e){
					$('#pregunta').load(rz+'checklist/pregunta.php',{
						pId: '<?php echo $faltaPreg; ?>',
						aId: '<?php echo $p['area']; ?>',
						chkId:'<?php echo $chk->id; ?>',
						vId:'<?php echo $_POST['vId']; ?>',
						abId:'a_<?php echo $p['bloque']; ?>',
						direccion:'regresar'
					} ,function(){});

				},{})
				// alert('Falta alguna pregunta por contestar, se te enviará a la primera faltante para que sea contestada');
				

			<?php }else{ ?>
				
				<?php if (!$allMult){ ?>
					var c = alertar('<?php echo TR('missFile'); ?>',function(){},{});
				<?php }else{ ?>
					var vis = <?php echo atj($vis); ?>;

					var html = `
						<?php echo TR('sendMessage'); ?>
						<hr/>
					`;
					conf(html,{vis:vis}, function(e){
							var vId = '<?php echo $_POST['vId']; ?>';
							var vInfo = <?php echo atj($vInfo); ?>;

							var rj = jsonF('checklist/json/json.php',{vId:vId,acc:1,opt:1});
							// console.log(rj);
							try{
								var r = $.parseJSON(rj);
							}catch(e){
								console.log('Error de parseo');
								console.log(rj);
								var r = {ok:0};
							}
							if(r.ok == 1){
								if(typeof from != 'undefined'){
									switch(from){
										case 'questionnaires':
											// console.log(from);
											<?php if($vInfo['type'] == 'trgt'){?>
												
												var targetsId = <?php echo $tInfo['targetsId']; ?>;
												var userTargetsId = <?php echo $tInfo['usersTargetsId']; ?>;

												$('#divTrgt_'+targetsId+'_'+userTargetsId)
												.find('.targetTable')
												.load(rz+'questionnaires/targets/targetTable.php',{targetId:targetsId});

											<?php } ?>
											break;
										default:
											break;

									}
								}

								$('#popUpCuest').modal('hide');
							}else{
								console.log(r);
							}

						})
				<?php } ?>

			<?php } ?>

		});

	});
</script>

