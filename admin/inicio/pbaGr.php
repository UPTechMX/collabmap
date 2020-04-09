
<style type="text/css">
	.highcharts-figure, .highcharts-data-table table {
		min-width: 360px; 
		max-width: 800px;
		margin: 1em auto;
	}

	.highcharts-data-table table {
		font-family: Verdana, sans-serif;
		border-collapse: collapse;
		border: 1px solid #EBEBEB;
		margin: 10px auto;
		text-align: center;
		width: 100%;
		max-width: 500px;
	}
	.highcharts-data-table caption {
		padding: 1em 0;
		font-size: 1.2em;
		color: #555;
	}
	.highcharts-data-table th {
		font-weight: 600;
		padding: 0.5em;
	}
	.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
		padding: 0.5em;
	}
	.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
		background: #f8f8f8;
	}
	.highcharts-data-table tr:hover {
		background: #f1f7ff;
	}
</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="https://code.highcharts.com/highcharts-more.js"></script>


<figure class="highcharts-figure">
	<div id="container"></div>
</figure>


<script type="text/javascript">


	////////// ESTO ES PARA GENERAR DATOS RANDOM //////////
	function randn_bm() {
		let u = 0, v = 0;
		while(u === 0) u = Math.random(); //Converting [0,1) to (0,1)
		while(v === 0) v = Math.random();
		let num = Math.sqrt( -2.0 * Math.log( u ) ) * Math.cos( 2.0 * Math.PI * v );
		num = num / 10.0 + 0.5; // Translate to 0 -> 1
		if (num > 1 || num < 0) return randn_bm(); // resample between 0 and 1
		return num;
	}

	function genDatos(){
		// Prepare the data
		var data = [],
			n = 99999,
			i;
		for (i = 0; i < n; i += 1) {
			data.push(
				Math.round(Math.pow(Math.random(), 2) * 100)
				// Math.round(randn_bm()*100)
			);
		}

		return data;
	}

	////////// AQUI TERMINA GENERACION DE DATOS RANDOM //////////

	function arreglaDatos(datos){
		var mapDisp = {};
		for(i = 0; i<datos.length;i++){
			if(mapDisp[datos[i]] == undefined){
				mapDisp[datos[i]] = [];
				mapDisp[datos[i]][1] = datos[i];
				mapDisp[datos[i]][0] = 0;
			}
			mapDisp[datos[i]][0]++;
		}

		var resp = {};

		resp['disp'] = Object.values(mapDisp);

		let sum = datos.reduce((previous, current) => current += previous);
		let avg = sum / datos.length;

		resp['avg'] = avg;

		datos.sort((a, b) => a - b);
		let lowMiddle = Math.floor((datos.length - 1) / 2);
		let highMiddle = Math.ceil((datos.length - 1) / 2);
		let median = (datos[lowMiddle] + datos[highMiddle]) / 2;

		resp['median'] = median;

		// console.log('DATOS:',datos);

		var indexQ1 = Math.floor((datos.length - 1)/4);
		if((datos.length - 1)>1){
			var Q1 = (datos[indexQ1]+datos[indexQ1+1])/2;
		}else{
			var Q1 = datos[indexQ1];
		}

		var indexQ2 = Math.floor((datos.length - 1)/2);
		if((datos.length - 1)>1){
			var Q2 = (datos[indexQ2]+datos[indexQ2+1])/2;
		}else{
			var Q2 = datos[indexQ2];
		}
		// console.log(datos[indexQ2],datos[indexQ2]);


		var indexQ3 = Math.floor((datos.length - 1)*3/4);
		var indexQ3 = Math.floor((datos.length - 1)*3/4);
		if(datos.length>1){
			var Q3 = (datos[indexQ3]+datos[indexQ3+1])/2;
		}else{
			var Q3 = datos[indexQ3];
		}

		var RI = Q3-Q1;

		AL1 = Q1-1.5*RI;
		AL2 = Q3+1.5*RI;

		xMin = datos[0];
		for(var i = 0; i<datos.length; i++){
			if(datos[i]<AL1){
				xMin = datos[i];
			}else{
				break;
			}

		}

		xMax = datos[datos.length-1];
		for(var i = datos.length -1; i>0; i--){
			if(datos[i]>AL2){
				xMax = datos[i];
			}else{
				break;
			}
		}

		resp['xMin'] = xMin;
		resp['Q1'] = Q1;
		resp['Q2'] = Q2;
		resp['Q3'] = Q3;
		resp['xMax'] = xMax;		
		
		return resp;
	}

	var datos = genDatos();
	var datGr = arreglaDatos(datos);

	Highcharts.chart('container', {
	chart: {
		type: 'boxplot',
		renderTo: 'container',
		inverted:true,
	},

	title: {
		text: 'Respuestas'
	},
	subtitle: {
		// text: 'Source: Heinz  2003'
	},
	xAxis: {
		title: {
			enabled: true,
			text: 'Frecuencia'
		},
		reversed:false,
		startOnTick: true,
		endOnTick: true,
		showLastLabel: true,

	},
	yAxis: {
		title: {
			text: 'Valor'
		},
		plotLines: [{
			color: '#FF0000', // Red
			width: 2,
			value: datGr['avg'] // Position, you'll have to translate this to the values on your x axis
		},
		{
			color: '#FF0000', // Red
			width: 2,
			value: datGr['median'] // Position, you'll have to translate this to the values on your x axis
		}]

	},
	legend: {
		layout: 'vertical',
		align: 'left',
		verticalAlign: 'top',
		x: 100,
		y: 70,
		floating: true,
		backgroundColor: Highcharts.defaultOptions.chart.backgroundColor,
		borderWidth: 1
	},
	plotOptions: {
		scatter: {
			marker: {
				radius: 2,
				states: {
					hover: {
						enabled: true,
						lineColor: 'rgb(100,100,100)'
					}
				}
			},
			states: {
				hover: {
					marker: {
						enabled: false
					}
				}
			},
			tooltip: {
				headerFormat: '<b>{series.name}</b><br>',
				pointFormat: 'Valor:<strong>{point.y}</strong><br/>Frecuencia: <strong>{point.x}</stong>'
			}
		}
	},
	series: [
		{
			type: 'scatter',
			name: 'Respuestas',
			color: 'rgba(223, 83, 83, .5)',
			data: datGr['disp'],

		},
		{
			type: 'boxplot',
			inverted: true,
            name: 'Observations',
            data: [
                [datGr['xMin'], datGr['Q1'], datGr['Q2'], datGr['Q3'], datGr['xMax']]
            ],
            tooltip: {
                headerFormat: '<em>Genotype No. {point.key}</em><br/>'
            }
        }
		
	]
});

</script>