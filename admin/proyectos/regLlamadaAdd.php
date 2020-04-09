<script type="text/javascript">
	$(document).ready(function() {
		var comentarios = new Quill('#comentarios', {
		  theme: 'snow'
		});

		$('#env').click(function(event) {
			var dat = {};
			dat.clientesId = <?php echo $_POST['cteId']; ?>;
			dat.comentarios = comentarios.root.innerHTML.trim();

			// console.log(dat);
			var rj = jsonF('admin/proyectos/json/json.php',{datos:dat,opt:1,acc:1})

			try{
				var r = $.parseJSON(rj);
				// console.log(rj);
			}catch(e){
				console.log('error de parseo');
				console.log(rj);
				var r = {ok:0,err:'error de parseo'};
			}

			if(r.ok == 1){
				$('#popCont').load(rz+'admin/proyectos/regLlamada.php',{cteId:dat.clientesId});
				$('#cieraModal').trigger('click');
				// alertar('La información ha sido guardada correctamente',function(){},{});
			}
		});


	});
</script>
<?php  

	include_once '../../lib/j/j.func.php';
	session_start();
	checaAcceso(49);
	$usrId = $_SESSION['IU']['admin']['usrId'];
	// echo $usrId;
	$datCte = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";
	// print2($_POST);
	// print2($datCte);


?>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Nuevo registro de llamada</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="cieraModal">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>


<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>

	<table class="table">
		<tr>
			<td> Cliente:</td>
			<td> <?php echo $cteNom; ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="comentarios" style="height: 200px;"></div>
			</td>
		</tr>
	</table>
</div>


<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancelRegLlamada" data-dismiss="modal" class="btn btn-sm btn-cancel">Cerrar</span>
		<span id="env" class="btn btn-sm btn-shop">guardar</span>
	</div>
</div>

