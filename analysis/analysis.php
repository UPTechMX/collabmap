<?php 
	include_once '../lib/j/j.func.php';
	checaAcceso(5); // checaAcceso analysis;
?>
<script type="text/javascript">
	
	function pieChart(elem, data, name){
		elem.highcharts({

			chart: {
			    plotBackgroundColor: null,
			    plotBorderWidth: null,
			    plotShadow: false,
			    type: 'pie'
			},
			title: {
			    text: name
			},
			tooltip: {
			    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			accessibility: {
			    point: {
			        valueSuffix: '%'
			    }
			},
			plotOptions: {
			    pie: {
			        allowPointSelect: true,
			        cursor: 'pointer',
			        dataLabels: {
			            enabled: false
			        },
			        showInLegend: true
			    }
			},
			series: [{
			    name: 'Answers',
			    colorByPoint: true,
			    data: data
			}]
				
		});

	}

	function numChart(elem, datGr, name){
		elem.highcharts({

				chart: {
					type: 'boxplot',
					renderTo: 'container',
					inverted:true,
					zoomType: 'y',
				},

				title: {
					text: name,
				},
				subtitle: {
					// text: 'Source: Heinz  2003'
				},
				xAxis: {
					title: {
						enabled: true,
						text: '<?php echo TR("frequency"); ?>',
					},
					reversed:false,
					startOnTick: true,
					endOnTick: true,
					showLastLabel: true,

				},
				yAxis: {
					title: {
						text: '<?php echo TR("value"); ?>',
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
					align: 'center',
					verticalAlign: 'bottom',
					x: 0,
					y: 0
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
							pointFormat: '<?php echo TR("value"); ?>:<strong>{point.y}</strong><br/><?php echo TR("frequency"); ?>: <strong>{point.x}</stong>'
						}
					}
				},
				series: [
					{
						type: 'scatter',
						name: '<?php echo TR("answers"); ?>',
						color: 'rgba(223, 83, 83, .5)',
						data: datGr['disp']

					},
					{
						type: 'boxplot',
						inverted: true,
			            name: '<?php echo TR("analysis"); ?>',
			            data: [
			                [datGr['xMin'], datGr['Q1'], datGr['Q2'], datGr['Q3'], datGr['xMax']]
			            ],
			            tooltip: {
			                headerFormat: ''
			            }
			        }
					
				]


		});

	}

	function barChart(elem, data, cats,name){
		elem.highcharts({

			chart: {
			    type: 'column'
			},
			title: {
			    text: name
			},
			subtitle: {
			    text: ''
			},
			xAxis: {
			    categories: cats,
			    title: {
			        text: null
			    }
			},
			yAxis: {
			    min: 0,
			    title: {
			        text: '<?php echo TR("frequency"); ?>',
			        align: 'high'
			    },
			    labels: {
			        overflow: 'justify'
			    }
			},
			tooltip: {
			    valueSuffix: ''
			},
			plotOptions: {
			    column: {
			        dataLabels: {
			            enabled: true
			        }
			    }
			},
			credits: {
			    enabled: false
			},
			series: [
				{
				    name: '<?php echo TR("answer"); ?>',
			    	data: data
				}
			]
				
		});

	}

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
					// Math.round(Math.pow(Math.random(), 2) * 100)
					Math.round(randn_bm()*100)
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
		let sum;
		let avg;
		if(datos.length == 0){
			sum = 0;
			avg = 0;
		}else{

			sum = datos.reduce((previous, current) => current += previous);
			avg = sum / datos.length;
		}

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


		// console.log('indexQ1',indexQ1,'Q1:',Q1);
		// console.log('indexQ2',indexQ2,'Q2:',Q2);
		// console.log('indexQ3',indexQ3,'Q3:',Q3);
		// console.log('xMin',xMin,'xMax:',xMax);
		
		
		return resp;
	}

	function barChartSMnum(elem, series, cats,name){
		elem.highcharts({

			chart: {
			    type: 'column'
			},
			title: {
			    text: name
			},
			subtitle: {
			    text: ''
			},
			xAxis: {
			    categories: cats,
			    crosshair: true
			},
			yAxis: {
			    min: 0,
			    title: {
			        text: '<?php echo TR("value"); ?>'
			    }
			},
			tooltip: {
			    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			    footerFormat: '</table>',
			    shared: true,
			    useHTML: true
			},
			plotOptions: {
			    column: {
			        pointPadding: 0.2,
			        borderWidth: 0
			    }
			},
			series: series
				
		});

	}

	function barChartSMmult(elem, series, cats, name, catsY){
		var xOffset = 28/3*(catsY.length+1);
		console.log('xOffset',xOffset, catsY.length);
		elem.highcharts({

			chart: {
			    type: 'column'
			},
			title: {
			    text: name
			},
			subtitle: {
			    text: ''
			},
			xAxis: {
			    categories: cats,
			    crosshair: true,
			    offset:-(30/3*catsY.length)
			},
			yAxis: {
			    // min: 0,
			    title: {
			        text: '<?php echo TR("answer"); ?>'
			    },
			    categories: catsY,
			    
			},
			tooltip: {
			    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			        '<td style="padding:0"><b>{point.name}</b></td></tr>',
			    footerFormat: '</table>',
			    shared: true,
			    useHTML: true
			},
			plotOptions: {
			    column: {
			        pointPadding: 0.2,
			        borderWidth: 0
			    }
			},
			series: series
				
		});

	}


		// var datos = genDatos();
		// // console.log(datos);
		// var datGr = arreglaDatos(datos);
</script>


