<?php  

// include_once '../../lib/j/j.func.php';
// print2($_POST);

// print2($ans);

?>

<script type="text/javascript">
	$(document).ready(function() {

		// grid = genGrid();

		wData = {};
		// wData[0] = {};
		// wData[0].tipo = 1;
		// wData[0].grafica = 'line';
		// wData[0].promedio = 0;
		// wData[0].analisis = 1;
		// wData[0].nombre = 'Total';


		// console.log(wData);
		setTimeout(function () {
			grid = genGrid();
			ajustaWidget(0);
			if(!calculados){
				calculados = true;
				$('#calcComp').trigger('click');
			}

		}, 10);


		var calculados = false;
		$('#comparativo-tab').click(function(event) {
			setTimeout(function () {
				grid = genGrid();
				ajustaWidget(0);
				if(!calculados){
					calculados = true;
					$('#calcComp').trigger('click');
				}

			}, 10);
		});


		var cuantos = $('#grid .grid-stack-item').length;
		$('#addGr').click(function(event) {
			// console.log('aaa');
			var el = $('<div>')
			.attr({class:"grid-stack-item",id:"wid_"+cuantos})
			.html('<div class="grid-stack-item-content wCont widget"></div>')
			$('.grid-stack').gridstack();

			grid.addWidget(el, 0, 0, 6, 4, true,2,12,6,10);
			$('#wid_'+cuantos).find('.wCont').load(rz+'reportes/comparativo/widgetCont.php',{nvo:1,wId:cuantos});
			wData[cuantos] = {};
			cuantos++;

			// console.log(cuantos);
		});

		$('#grid').on('click', '.widClose', function(event) {
			event.preventDefault();
			var el = $(this).closest('.grid-stack-item');
			var wId = $(this).closest('.grid-stack-item').attr('id').split('_')[1];

			grid.removeWidget(el,true)
			// gridster.remove_widget( li );
			delete wData[wId];
		});

		$('#grid').on('gsresizestop', function(event, elem) {
			// console.log(elem.id);
			var wId = elem.id.split('_')[1];
			ajustaWidget(wId)

		});

		$('#grid').on('click', '.bConf', function(event) {
			// console.log('aa');
			event.preventDefault();
			var wId = $(this).closest('.grid-stack-item').attr('id').split('_')[1];
			var proyectoId = <?php echo $_POST['proyectoId']; ?>;
			popUp('reportes/comparativo/config.php',{proyectoId:proyectoId,wId:wId},function(){},{});
		});

	});
</script>
<!-- 
<div style="text-align: center;">
	<span class="btn btn-sm btn-shop" id="addGr">Agregar widget</span>
</div>
 -->
<div style="margin-top:5px;">
	<div class="manita" style="height: 120px;margin-left: auto; margin-right:auto;
		background-color: whitesmoke;text-align: center;color:#AAA;border-radius: 5px;" id="addGr">
		<i class="glyphicon glyphicon-plus" style="font-size: 3.5em;margin-top: 20px;margin-bottom: 10px;"></i>
		<br/>
		Agregar gráfico
	</div>
</div>

<div class="grid-stack" id="grid" style="margin-top: 15px;">
<!-- 	<div class="grid-stack-item "
		data-gs-x="0" data-gs-y="0"
		data-gs-width="6" data-gs-height="4" data-gs-max-width="12" data-gs-max-height="10"  
		data-gs-min-width="2" data-gs-min-height="6" id="wid_0">
			<div class="grid-stack-item-content wCont widget"><?php include 'widgetCont.php'; ?></div>
	</div>
 -->

</div>







