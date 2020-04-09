<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Vehiculos WHERE id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		$multimedia = $db->query("SELECT * FROM VehiculosMult WHERE vehiculosId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);
		// print2($datC);
	}

	// print2($mediosRec);


?>

<?php

$nivel = $_SESSION['CM']['admin']['nivel'];
if($nivel<50){
	exit('No tienes acceso a esta área');
}



?>

<script type="text/javascript">
	$(document).ready(function() {

		var datC = <?php echo atj($datC); ?>;

		 $('.verArea').click(function(event) {
			$(this).siblings('.area').toggle();
			if($(this).siblings('.area').is(':visible')){
				$(this).find('i').removeClass('glyphicon-menu-right');
				$(this).find('i').addClass('glyphicon-menu-down');
			}else{
				$(this).find('i').removeClass('glyphicon-menu-down');
				$(this).find('i').addClass('glyphicon-menu-right');
			}
		});


		$('#envVeh').click(function(event) {
			var dat = $('#fVeh').serializeObject();
			dat.id = <?php echo $_POST['eleId']; ?>;
			var allOk = camposObligatorios('#fVeh');


			if(allOk){
				var rj = jsonF('admin/administracion/vehiculos/json/json.php',{datos:dat,acc:2,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				console.log(r);
			}

		});

		subArch($('#subeFotografias'),3,'fotografia_<?php echo $_POST['eleId']; ?>_','jpg,png,gif,jpeg',true,function(e){
			// console.log(e);
			var rj = jsonF('admin/administracion/vehiculos/json/json.php',{dat:e,acc:4,vId:<?php echo $_POST['eleId']; ?>})
			// console.log(rj);
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
						'<div class="col-10" id="imgNom_'+r.nId+'">'+
							'<img class="verImg manita" src="administracion/vehiculos/img/'+e.prefijo+e.nombreArchivo+'" height="100px"/>'+
						'</div>'+
						'<div class="col-2" style="text-align: right;">'+
							'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
								'id="imgDel_'+r.nId+'"></i>&nbsp;&nbsp;'+
						'</div>'+
					'</div>'
				)
			}

		});

		$('.verImg').click(function(event) {
			var src = $(this).attr('src');
			verImagen(src);
		});

		$('#multimedia').on('click', '.multDel', function(event) {
			// console.log('aa');
			event.preventDefault();
			var multId = this.id.split('_')[1]
			conf('¿Seguro que deseas eliminar la fotografía?',{multId:multId,ele:$(this)},function(e){

				var rj = jsonF('admin/administracion/vehiculos/json/json.php',{acc:5,mId:e.multId});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					e.ele.closest('li').remove();
				}
			})
		});

		$('#placa').blur(function(event) {
			var thenum = parseInt($(this).val().replace( /\D+/g, ''))%10; // replace all leading non-digits with nothing
			// console.log(thenum)

			switch(thenum){
				case 1:
				case 2:
					$('#engomado').css({backgroundColor:'#40b34f'});
					break;
				case 3:
				case 4:
					$('#engomado').css({backgroundColor:'#ea202d'});
					break;
				case 5:
				case 6:
					$('#engomado').css({backgroundColor:'#fdf035'});
					break;
				case 7:
				case 8:
					$('#engomado').css({backgroundColor:'#e65fb6'});
					break;
				case 9:
				case 0:
					$('#engomado').css({backgroundColor:'#31abe0'});
					break;
				default:
					$('#engomado').css({backgroundColor:'#FFFFFF'});
					break;
			}
		});
		$('#placa').trigger('blur');



	});
</script>

<div class="nuevo" style="text-align: center;">
	Datos del vehículo <strong><?php echo $datC['nombre']; ?></strong>
</div>

<br/>
<table class="table" style="font-family: Courier;">
	<tr>
		<td>Nombre</td>
		<td>
			<?php echo $datC['nombre']; ?>
		</td>
		<td></td>
	</tr>
</table>

<div>
	<div class="nuevo barra manita verArea">
		Información general <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display: none;" id="ubicaciones">
		<form id='fVeh'>		
			<table class="table">
				<tbody>
					<tr>
						<td>Placa</td>
						<td>
							<div class="row">
								<div class="col-8">
									<input type="text" name="placa" id="placa" value="<?php echo $datC['placa']; ?>" class="form-control" />
								</div>
								<div class="col-2">
									<div style="width: 70px;height: 35px; border-radius: 3px;" id="engomado"></div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>Marca</td>
						<td><input type="text" name="marca" id="marca" value="<?php echo $datC['marca']; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td>Modelo</td>
						<td><input type="text" name="modelo" id="modelo" value="<?php echo $datC['modelo']; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td>Año</td>
						<td><input maxlength=4 type="text" name="year" id="year" value="<?php echo $datC['year']; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td>Color</td>
						<td><input type="text" name="color" id="color" value="<?php echo $datC['color']; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td>N<sup>o</sup> de puertas</td>
						<td><input type="text" name="puertas" id="puertas" value="<?php echo $datC['puertas']; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td>Dimensiones</td>
						<td><input type="text" name="dimensiones" id="dimensiones" value="<?php echo $datC['dimensiones']; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td>Tipo de vehículo</td>
						<td>
							<select name="capacidadCarga" id="capacidadCarga" class="form-control">
								<option value="Grande" <?php echo $datC['capacidadCarga'] == 'Grande'?'selected':''; ?>>Grande</option>
								<option value="Mediano" <?php echo $datC['capacidadCarga'] == 'Mediano'?'selected':''; ?>>Mediano</option>
								<option value="Chicho" <?php echo $datC['capacidadCarga'] == 'Chicho'?'selected':''; ?>>Chicho</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Capacidad de personal</td>
						<td>
							<input type="text" name="capacidadPersonal" id="capacidadPersonal" 
							value="<?php echo $datC['capacidadPersonal']; ?>" class="form-control" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<div style="text-align: right; margin-bottom: 10px;">
			<span id="envVeh" class="btn btn-sm btn-shop">Guardar</span>
		</div>
	</div>
</div>


<div>
	<div class="nuevo barra manita verArea">
		Fotografías <span style="float:right;"><i class="glyphicon glyphicon-menu-right manita"></i></span>
	</div>
	<div class="area" style="display: none;" id="multimedia">
		<div class="row" style="margin-top: 5px;border:none;">
			<div class="col-6" style="border:none 1px blue;">
				<span id="subeFotografias"></span>
			</div>
			<div class="col-12" style="border: none 1px;">
				<ul id="imgList" class="list-group">
					<?php 
						$multimedia = isset($multimedia)?$multimedia:array();

						foreach ($multimedia as $i){
					?>
						<li class="list-group-item imgEle" id="imgEle_<?php echo $i['id'];?>">
							<div class="row">
								<div class="col-10" id="imgNom_<?php echo $i['id'];?>">
									<img class="verImg manita" src="administracion/vehiculos/img/<?php echo $i['archivo'];?>" height="100px"/>
								</div>
								<div class="col-2" style="text-align: right;">
									<i class="glyphicon glyphicon-trash manita multDel rojo" 
										id="imgDel_<?php echo $i['id'];?>"></i>&nbsp;&nbsp;
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>

	</div>
</div>

