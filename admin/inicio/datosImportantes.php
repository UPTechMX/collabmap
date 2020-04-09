<?php  
	session_start();
	if(!function_exists('raiz')){
		include_once '../lib/j/j.func.php';
		include_once raiz().'lib/php/usrInt.php';
		$usrId = $_SESSION['IU']['admin']['usrId'];
		$usr = new Usuario($usrId);
	}
	checaAcceso(50);

	// print2($usr);
	$generales = $usr->visitasGral();

?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#visFalt").click(function(event) {
			popUp('admin/inicio/porRepeticion.php',{dato:'faltantes'},function(){},{})
		});
		$("#enviadasTot").click(function(event) {
			popUp('admin/inicio/porRepeticion.php',{dato:'enviadas'},function(){},{})
		});
		$("#enviadasAyer").click(function(event) {
			popUp('admin/inicio/porRepeticion.php',{dato:'enviadas ayer'},function(){},{})
		});
		$("#revTot").click(function(event) {
			popUp('admin/inicio/porRepeticion.php',{dato:'revisadas'},function(){},{})
		});
		$("#attTot").click(function(event) {
			popUp('admin/inicio/porRepeticion.php',{dato:'que requieren atención'},function(){},{})
		});
		$('#solsPend').click(function(event) {
			$('#aumCost').trigger('click');
		});
	});
</script>
<div class="nuevo">Datos generales del periodo <span id="fechaIniPer"></span> al <span id="fechaFinPer"></span></div>
<div class="row">
	<div class="col-md-4" style="">
		<div id="grTots"></div>
	</div>
	<div class="col-md-2">
		<div class="nuevo" style="">Total</div>
		<div class="numerote">
			<span class="totalSum"></span>
		</div>
	</div>
	<div class="col-md-2">
		<div class="nuevo" style="">Enviadas</div>
		<div class="numerote">
			<span class="enviadasSum"></span><br/>
			<span class="enviadasSumPerc" style="font-size: .5em;"></span>
		</div>
	</div>
	<div class="col-md-2">
		<div class="nuevo" style="">Faltantes</div>
		<div class="numerote">
			<span class="faltantesSum"></span><br/>
			<span class="faltantesSumPerc" style="font-size: .5em;"></span>
		</div>
	</div>
	<div class="col-md-2">
		<div class="nuevo" style="">Recibidas</div>
		<div class="numerote">
			<span class="recibidasSum"></span><br/>
			<span class="recibidasSumPerc" style="font-size: .5em;"></span>
		</div>
	</div>
	<div style="margin-top: 30px;" class="col-md-2 col-md-offset-1">
		<div class="nuevo" style="">Revisadas</div>
		<div class="numerote">
			<span class="revisadasSum"></span><br/>
			<span class="revisadasSumPerc" style="font-size: .5em;"></span>
		</div>
	</div>
	<div style="margin-top: 30px;" class="col-md-2">
		<div class="nuevo" style="">Publicadas</div>
		<div class="numerote">
			<span class="publicadasSum"></span><br/>
			<span class="publicadasSumPerc" style="font-size: .5em;"></span>
		</div>
	</div>
	<div style="margin-top: 30px;" class="col-md-2">
		<div class="nuevo" style="">Canceladas</div>
		<div class="numerote">
			<span class="canceladasSum"></span><br/>
			<span class="canceladasSumPerc" style="font-size: .5em;"></span>
		</div>
	</div>
</div>

<div style="margin-top: 30px;" class="nuevo">Indicadores de todos los periodos activos</div>
<div class="row">
	<div class="col-md-2 datImp">
		<div class="nuevo" style="height:60px;">Faltante general</div>
		<div class="numerote manita" id="visFalt">
			<?php echo $generales['faltantes']; ?>
		</div>
	</div>
	<div class="col-md-2 datImp">
		<div class="nuevo" style="height:60px;">Enviadas totales</div>
		<div class="numerote manita" id="enviadasTot">
			<?php echo $generales['visEnv']; ?>
		</div>
	</div>
	<div class="col-md-2 datImp">
		<div class="nuevo" style="height:60px;">Prod. día anterior</div>
		<div class="numerote manita" id="enviadasAyer">
			<?php echo $generales['visEnvAyer']; ?>
		</div>
	</div>
	<div class="col-md-2 datImp">
		<div class="nuevo" style="height:60px;">Revisadas totales</div>
		<div class="numerote manita" id="revTot">
			<?php echo $generales['visRev']; ?>
		</div>
	</div>
	<div class="col-md-2 datImp">
		<div class="nuevo" style="height:60px;">Requieren atención</div>
		<div class="numerote manita" id="attTot">
			<?php echo $generales['visAtt']; ?>
		</div>
	</div>
	<?php if ($nivel>= 30){ ?>	
		<div class="col-md-2 datImp">
			<div class="nuevo" style="height:60px;">Solicitudes pendientes</div>
			<div class="numerote manita" id="solsPend">
				<a id="aumCost" href="<?php echo $_SERVER['PHP_SELF'];?>?Act=solicitudes" style="text-decoration: none;" class="manita">
					<?php echo $generales['solicitudes']; ?>
				</a>
			</div>
		</div>
	<?php } ?>
</div>
