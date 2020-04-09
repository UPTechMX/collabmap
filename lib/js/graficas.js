function pay(ele,s,nombre){
	// console.log(s);
	$(ele).highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie',
			backgroundColor:'rgba(255, 255, 255, 0.0)',
			// marginTop:'-100',
		},
		title: {
			text: ''
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
		exporting: { enabled: false },

		series: [{
			name: 'Porcentaje',
			colorByPoint: true,
			data: s
		}],
		legend:{
		},
		credits: {
			enabled: false
		},
	});
}

function grafVel(div,avance){

	var gaugeOptions = {

			chart: {
				type: 'solidgauge',
				alignTicks: false,
				plotBackgroundColor: null,
				plotBackgroundImage: null,
				plotBorderWidth: 0,
				plotShadow: false,
				spacingTop: 0,
				spacingLeft: 0,
				spacingRight: 0,
				spacingBottom: 0,
				backgroundColor:'rgba(255, 255, 255, 0)'
			},

			title: {
				style: {
					display: 'none'
				},

				text:''
			},


			pane: {
				center: ['50%', '50%'],
				size: '100%',
				startAngle: -90,
				endAngle: 90,
				background: {
					backgroundColor: 'rgba(0,0,0,0)',
					innerRadius: '60%',
					outerRadius: '100%',
					shape: 'arc'
				}
			},

			tooltip: {
				enabled: false
			},

			// the value axis
			yAxis: {
				stops: [

					[0.5, '#ff0000'], // red
					[0.55, '#ff0000'], // red
					[0.65, '#FFD700'], // green
					[0.80, '#FFD700'], // green
					[0.85, '#FFD700'], // green
					[0.899999, '#55BF3B'], // green
					[0.9, '#55BF3B'], // green
					[0.91, '#55BF3B'], // green
					[1, '#55BF3B'] // green
				],
				lineWidth: 0,
				minorTickInterval: null,
				tickPixelInterval: 400,
				tickWidth: 0,
				title: {
					y: -70
				},
				labels: {
					enabled:false,
					y: 16
				}
			},

			plotOptions: {
				solidgauge: {
					dataLabels: {
						enabled:true,
						y: 5,
						borderWidth: 0,
						useHTML: true,
						style:{
							fontSize:'2em'
						}
					}
				}
			}
		};

		// The speed gauge
		div.highcharts(Highcharts.merge(gaugeOptions, {
			yAxis: {
				min: 0,
				max: 100,
				title: {
					text: ''
				}
			},
			exporting: { enabled: false },

			credits: {
				enabled: false
			},

			series: [{
				name: 'Total',
				data: [avance],
				dataLabels: {
					format: '<div style="text-align:center;"><span style="font-size:1em;color:black">{y}%</span><br/></div>'
				},
				tooltip: {
					valueSuffix: 'Total'
				}
			}]

		}));


	var chart = div.highcharts(),
				point,
				newVal,
				inc;

			if (chart) {
				point = chart.series[0].points[0];
				newVal = avance;
			   
				point.update(newVal);
			}
}

function barras(ele,dat,prom){
	// console.log(dat);
	if(prom){		
		var sum = 0;
		for(var i in dat){
			sum += parseFloat(dat[i].y);
		}

		if(!isNaN(sum) && dat.length > 0){
			var prom = sum/dat.length;
		}else{
			var prom = 0;
		}

		var lProm = {
			color: 'black',
			value: prom,
			width: '1',
			zIndex: 4,
			label:{
				text:prom.toFixed(2)
			},
			min:0,
			max:100

		}
		// console.log(prom);
	}else{
		lProm = [];
	}

	var decimales = 0;
	for(var i in dat){
		if(!isInt(dat[i].y)){
			decimales = 2;
		}
	}



	$(ele).highcharts({
		chart: {
			type: 'column',

		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			type: 'category'
		},
		yAxis: {
			title: {
				text: ''
			},
			plotLines: [lProm],
			min:0,
			max:100

			
		},
		legend: {
			enabled: false
		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					format: '{point.y:.'+decimales+'f}'
				}
			},
		},

		tooltip: {
			headerFormat: '',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>:<br/> <b>{point.y:.'+decimales+'f}</b>  <br/>'
		},

		series: [{
			name: 'Totales',
			colorByPoint: true,
			data: dat
		}],
		credits: {
			enabled: false
		},
		exporting: { enabled: false },

	});
}

function barrasDD(ele,dat,drilldown){
	// console.log(dat,drilldown);
	$(ele).highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			type: 'category'
		},
		yAxis: {
			title: {
				text: 'Total '
			},
			min:0,
			max:100


		},
		legend: {
			enabled: false
		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					format: '{point.y:.2f}%'
				}
			}
		},

		tooltip: {
			// formatter: function () {
			// 	console.log(this);
			// },

			headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b>% <br/>'+
			'<b>Base: {point.cuenta}</b>'
		},

		series: [{
			name: 'Totales',
			colorByPoint: true,
			data: dat
		}],
		drilldown: {
			series: drilldown
		},
		credits: {
			enabled: false
		},
		exporting: { enabled: false },

	});
}

function apiladas(ele,dat,cats){
	$(ele).highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		xAxis: {
			categories: cats
		},
		yAxis: {
			min: 0,
			title: {
				text: ''
			}
		},
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
			shared: true
		},
		plotOptions: {
			column: {
				stacking: 'percent'
			}
		},

		series: dat,
		credits: {
			enabled: false
		},
		exporting: { enabled: false },


	});
}

function lineas(ele,dat,cats,prom,tipo,nom){
	// console.log(nom);
	if(prom == 1){
		var k = 0;
		var sum = 0;
		for(var i in dat){
			for(var j in dat[i].data){
				k++;
				sum += parseFloat(dat[i].data[j].y);
			}
		}
		// console.log(sum);

		if(!isNaN(sum) && k > 0){
			var prom = sum/k;
		}else{
			var prom = 0;
		}

		var lProm = {
			color: 'black',
			value: prom,
			width: '1',
			zIndex: 4
		}
		// console.log(prom);
	}else{
		lProm = [];
	}

	var decimales = 0;
	for(var i in dat){
		if(!isInt(dat[i].y)){
			decimales = 2;
		}
	}
	


	$(ele).highcharts({
		chart: {
			type: tipo,
			zoomType:'xy'

		},
		title: {
			useHTML:true,
			text: '<div style="text-align:justify;"><span class="nombre" style="font-weight: bold;">'+nom+'</span></div>'
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			// type: 'category',
			categories:cats
		},
		yAxis: {
			title: {
				text: ''
			},
			plotLines: [lProm],
			min:0,
			max:100
		},
		legend: {
			enabled: true,
			itemStyle: {
			    fontWeight: 'normal',
			    fontSize: '0.8em'
			}

		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					format: '{point.y:.'+decimales+'f}'
				}
			},

		},

		tooltip: {
			headerFormat: '',
			pointFormat: '<span style="color:{point.color}">'+
				'{series.name}</span>:<br/> <b>{point.y:.'+decimales+'f}</b>  <br/>'+
				'<b>Base: {point.cuenta}</b>'
		},

		series: dat,
		drilldown: {
			// series: drilldown
		},
		credits: {
			enabled: false
		},
		exporting: { enabled: true },

	});
}

function apiladasComp(ele,dat,cats,prom,tipo,nom){
	// console.log(cats);
	if(prom == 1){
		var k = 0;
		var sum = 0;
		for(var i in dat){
			for(var j in dat[i].data){
				k++;
				sum += parseFloat(dat[i].data[j].y);
			}
		}
		// console.log(sum);

		if(!isNaN(sum) && k > 0){
			var prom = sum/k;
		}else{
			var prom = 0;
		}

		var lProm = {
			color: 'black',
			value: prom,
			width: '1',
			zIndex: 4
		}
		// console.log(prom);
	}else{
		lProm = [];
	}

	var decimales = 0;
	for(var i in dat){
		if(!isInt(dat[i].y)){
			decimales = 2;
		}
	}
	


	$(ele).highcharts({
		chart: {
			type: tipo,
			zoomType:'xy'

		},
		title: {
			// useHTML:true,
			text: nom
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			// type: 'category',
			categories:cats
		},
		yAxis: {
			title: {
				text: ''
			},
			plotLines: [lProm],
			min:0,
			max:100
		},
		legend: {
			enabled: true,
			itemStyle: {
			    fontWeight: 'normal',
			    fontSize: '0.8em'
			}

		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: false,
					format: '{point.y:.'+decimales+'f}'
				}
			},
			column: {
			    stacking: 'normal',
			    stacking: 'percent'
			}


		},

		tooltip: {
			formatter: function () {
				// console.log(this);
			    return '<b>' + this.x + '</b><br/>' +
			        '<b>Respuesta '+this.series.options.respNom + ': ' + this.y + '</b><br/>' +
			        '<b>Base : ' + this.point.stackTotal+'</b><br/>'+
			        '<b>Porcentaje: '+this.percentage.toFixed(2)+'</b><br/>'+
			        '<b>Porcentaje acumulado: '+this.point.stackY.toFixed(2)+'</b>';
			}
		},

		series: dat,
		credits: {
			enabled: false
		},
		exporting: { enabled: true },

	});
}

function grafCalifFinal(div,calif){

	var gaugeOptions = {

	    chart: {
	        type: 'solidgauge',
	    },

	    title: null,

	    pane: {

	        size: '100%',
	        startAngle: -0,
	        endAngle: 360,
	        background: {
	            innerRadius: '60%',
	            outerRadius: '100%',
	            shape: 'arc',
	        }
	    },

	    tooltip: {
	        enabled: false
	    },

	    // the value axis
	    yAxis: {
	        stops: [
	            [0.5, '#ff0000'], // red
	            [0.55, '#ff0000'], // red
	            [0.65, '#FFD700'], // green
	            [0.80, '#FFD700'], // green
	            [0.85, '#FFD700'], // green
	            [0.899999, '#55BF3B'], // green
	            [0.9, '#55BF3B'], // green
	            [0.91, '#55BF3B'], // green
	            [1, '#55BF3B'] // green
	        ],
	        lineWidth: 0,
	        minorTickInterval: null,
	        tickAmount: 2,
	        title: {
	        		enabled:false,
	            y: 0
	        },
	        labels: {
	        		enabled:false,
	            y: 50
	        }
	    },

	    plotOptions: {
	        solidgauge: {
	            dataLabels: {
	                y: -20,
	                borderWidth: 0,
	                useHTML: true
	            }
	        }
	    }
	};

	// The speed gauge
	div.highcharts(Highcharts.merge(gaugeOptions, {
	    yAxis: {
	        min: 0,
	        max: 100,
	        title: {
	            text: ''
	        }
	    },

	    credits: {
	        enabled: false
	    },

	    series: [{
	        name: '',
	        data: [calif],
	        dataLabels: {
	            format: '<div style="text-align:center;"><span style="font-size:1.5em;color:black">{y}%</span><br/></div>'
	        },
	        tooltip: {
	            valueSuffix: ''
	        }
	    }],
		credits: {
			enabled: false
		},
		exporting: { enabled: false },

	}));
}






