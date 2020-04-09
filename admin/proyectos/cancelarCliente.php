<?php  

	include_once '../../lib/j/j.func.php';
	session_start();
	checaAcceso(49);
	$usrId = $_SESSION['CM']['admin']['usrId'];
	// echo $usrId;
	$fechaHoy = date("Y-m-d");
	// echo $fechaHoy;

	// print2($_POST);
	$datCte = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";

	// echo $elem;

?>

<script type="text/javascript">
	$(document).ready(function() {

		var comentarios = new Quill('#comentarios', {
		  theme: 'snow'
		});


		$('#env').click(function(event) {
			var dat = {};
			dat.cteId = <?php echo $_POST['cteId']; ?>;
			dat.comentarios = comentarios.root.innerHTML.trim();

			var stripComent =  strip(dat.comentarios);
			var allOk = true;
			if(stripComent.length < 10){
				alertar('El comentario deben contener al menos 10 caracteres',function(){},{});
				allOk = false;
				// console.log('aa')
			}else{
				if(allOk){
					conf('¿Deseas cancelar el registro de este cliente?',{dat:dat},function(e){
						var cteId = '<?php echo $_POST['cteId']; ?>';
						var rj = jsonF('admin/proyectos/json/json.php',{datos:e.dat,acc:3,opt:2});
						// console.log(rj);
						try{
							var r = $.parseJSON(rj);
							console.log(r);
						}catch(e){
							console.log('Error de parseo');
							console.log(rj);
							var r = {ok:0};
						}
						// console.log(r);
						if(r.ok == 1){
							$('#popUp').modal('toggle');
							// console.log(cteId);
							$('#tr_'+cteId).load(rz+'admin/proyectos/clienteFila.php',{cId: cteId},function(){});
						}

					});

				}
			}
			
		});
	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Cancelación de cliente</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	Esta acción deshabilitará la cuenta de este cliente y cualquier modificación sucesiva que pudiera realizarse.

	Esta acción no puede revertirse.
	<table class="table">
		<tr>
			<td> Cliente:</td>
			<td> <?php echo $cteNom; ?></td>
		</tr>
	</table>
	<div style="border-bottom: solid 1px;">Comentarios de cancelación:</div>
	<div id="comentarios" style="height: 200px;"></div>

</div>
<div class="modal-footer">
	<div style="text-align: left;width: 100%;">
		<span id="env" class="btn btn-sm btn-cancel">Cancelar cliente</span>
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Regresar sin cancelar</span>
	</div>
</div>
