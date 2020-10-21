<script type="text/javascript">
	
	function initMapHS(o = {
		kmlId:0,
		n:0,
		s:0,
		e:0,
		w:0,
		attrs:[],
		chkIdspatial:0,
		tcIdspatial:0,
		spatialQ:0,
		trgtId:0,
		padre:0,
		nivelMax:0,
		questionsChk:[]
	}){

		// console.log(o.questionsChk);

		var server = '<?php echo $_SERVER['HTTP_HOST']; ?>';

		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
		var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
		map = new L.Map('mapHS', { center: new L.LatLng(0, 0), zoom: 2 });

		var notFound = L.featureGroup().addTo(map);
		var notFoundAA = L.featureGroup().addTo(map);
		layerGeoms = L.featureGroup();

		// console.log(o.n,o.s,o.e,o.w,o.kmlId);
		cLat = (parseFloat(o.n)+parseFloat(o.s))/2;
		cLng = (parseFloat(o.e)+parseFloat(o.w))/2;

		var polygonsJ = jsonF('analysis/hotspots/json/getGeomsAttr.php',{attrs:o.attrs,kmlId:o.kmlId});
		try{
			var polygons = $.parseJSON(polygonsJ);
		}catch(e){
			console.log('error de parseo');
			console.log(polygonsJ)
			var polygons = [];
		}

		var pointsJ = jsonF('analysis/hotspots/json/getPoints.php',{
			kmlId:o.kmlId,
			spatialQ:o.spatialQ,
			chkIdspatial:o.chkIdspatial,
			trgtId:o.trgtId,
			tcIdspatial:o.tcIdspatial,
			padre:o.padre,
			nivelMax:o.nivelMax,
			questionsChk:o.questionsChk
		});
		// console.log(pointsJ);
		var analysisType = o.questionsChk[0].multType;
		switch(o.questionsChk[0]['qType']){
			case 'num':
				analysisType = o.questionsChk[0].numType;
				break;
			case 'mult':
				analysisType = o.questionsChk[0].multType;
				break;
		}
		
		// console.log(analysisType);


		try{
			var points = $.parseJSON(pointsJ);
			var pnf = points[""];

		}catch(e){
			console.log('error de parseo');
			console.log(pointsJ)
			var points = [];
			var pnf = [];

		}

		var chkAnsNumJ = jsonF('analysis/hotspots/json/getChkAnsNum.php',{
			kmlId:o.kmlId,
			spatialQ:o.spatialQ,
			chkIdspatial:o.chkIdspatial,
			trgtId:o.trgtId,
			tcIdspatial:o.tcIdspatial,
			padre:o.padre,
			nivelMax:o.nivelMax,
			questionsChk:o.questionsChk
		});
		// console.log(chkAnsNumJ);


		try{
			var chkAnsNum = $.parseJSON(chkAnsNumJ);
			var pnf = chkAnsNum[""];

		}catch(e){
			console.log('error de parseo');
			console.log(chkAnsNumJ)
			var chkAnsNum = [];
			var pnf = [];

		}
		// console.log('points',points);
		// console.log('chkAnsNum',chkAnsNum);

		var questMax = {};
		for(j = 0;j<o.questionsChk.length;j++){
			var questionChk = o.questionsChk[j];
			questMax['ans'+j] = 0;
		}
		questMax['countMeetsAll'] = 0;


		var numRespMax = 0;
		var numRespMin = 9999999999999;
		var numRespTot = 0;
		var numRespSel = 0;
		var numRespPol = 0;
		var valNumMax = -999999999999;
		var valNumMin = 999999999999;
		var acums = {};

		// console.log(points);
		// console.log(polygons);
		// console.log(o.kmlId);
		for(var i in points){

			if(typeof polygons[i] == 'undefined'){
				if(o.kmlId != -1){
					delete points[i];
				}
				continue;
			}

			var count = points[i].length;
			// console.log('count: ',count, points[i]);
			numRespMax = Math.max(numRespMax,count);
			numRespMin = Math.min(numRespMin,count);
			numRespTot += count;
			acums[i] ={};
			acums[i].numRespSel = count;
			acums[i].countMultAns = {};
			acums[i].countNumAns = {};
			acums[i].countMeetsAll = 0;
			// console.log(points[i]);
			var sums = {};
			for(var k= 0;k<points[i].length;k++){
				var meetsAll = true;
				for(j = 0;j<o.questionsChk.length;j++){
					var questionChk = o.questionsChk[j];
					if(acums[i].countMultAns['ans'+j] == undefined){
						acums[i].countMultAns['ans'+j] = {};
						acums[i].countMultAns['ans'+j]['value'] = 0;
					}
					if(questionChk['qType'] == 'mult'){
						if(points[i][k]['respV'+j] == questionChk['answer']){
							acums[i].countMultAns['ans'+j]['value']++;
						}else{
							// console.log('nocumple',i,j,points[i][k]['respV'+j],questionChk['answer'],questionChk);
							meetsAll = false;
						}
						questMax['ans'+j] = Math.max(questMax['ans'+j],acums[i].countMultAns['ans'+j]['value']);
					}

					if(questionChk['qType'] == 'num'){
						if(sums['ans'+j] == undefined){
							sums['ans'+j] = 0;
						}
						if(questionChk['questionId'] == 'ansNum'){
							////// AQUIES
							if(chkAnsNum[questionChk['chkId']][i] == undefined){
								// console.log('ESTA ENTRANDO AQUI' );
								valAnalysis = 0;
							}else{
								if(chkAnsNum[questionChk['chkId']][i][k] == undefined){
									// console.log('ESTA ENTRANDO ACA',questionChk['chkId'],points[i].length,i,k);
									valAnalysis = null;
								}else{
									valAnalysis = parseFloat(chkAnsNum[questionChk['chkId']][i][k].ansNum);
								}
							}

						}else{
							valAnalysis = isNaN(parseFloat(points[i][k]['respV'+j]))?0:parseFloat(points[i][k]['respV'+j]);
						}

						sums['ans'+j] += valAnalysis;
						// console.log(i,valAnalysis, questionChk['inequality'],parseFloat(questionChk['value']));
						// console.log(questionChk);
						switch(questionChk['inequality']){
							case '<':
								if(valAnalysis != null && valAnalysis < parseFloat(questionChk['value'])){
									acums[i].countMultAns['ans'+j]['value']++;		
								}else{
									meetsAll = false;
								}
								break;
							case '<=':
								if(valAnalysis != null && valAnalysis <= parseFloat(questionChk['value'])){
									acums[i].countMultAns['ans'+j]['value']++;		
								}else{
									meetsAll = false;
								}
								break;
							case '=':
								if(valAnalysis != null && valAnalysis == parseFloat(questionChk['value'])){
									acums[i].countMultAns['ans'+j]['value']++;		
								}else{
									meetsAll = false;
								}
								break;
							case '>=':
								if(valAnalysis != null && valAnalysis >= parseFloat(questionChk['value'])){
									acums[i].countMultAns['ans'+j]['value']++;		
								}else{
									meetsAll = false;
								}
								break;
							case '>':
								if(valAnalysis != null && valAnalysis > parseFloat(questionChk['value'])){
									acums[i].countMultAns['ans'+j]['value']++;		
								}else{
									meetsAll = false;
								}
								break;
							case 'range':
								valNumMax = Math.max(valNumMax,valAnalysis);
								valNumMin = Math.min(valNumMin,valAnalysis);
								break;
							default:
								break;
						}
						questMax['ans'+j] = Math.max(questMax['ans'+j],acums[i].countMultAns['ans'+j]['value']);
						// console.log(i,questionChk['inequality']);
						acums[i].range = questionChk['inequality'] == 'range';
					}

				}

				acums[i].sums = sums;
				acums[i].avgs = {};
				acums[i].avgValid = true;
				acums[i].sumValid = {};
				
				// console.log(o.questionsChk,sums);
				for(var h in sums){
					
					var avg =count != 0 ? sums[h]/count:0;
					// console.log(avg,sums[h],count);
					acums[i].avgs[h] = avg;
					for(j = 0;j<o.questionsChk.length;j++){

						if(acums[i].sumValid['ans'+j] == undefined){
							acums[i].sumValid['ans'+j] = true;
						}
						// console.log(parseFloat(avg) ,questionChk['inequality'], parseFloat(questionChk['value']))

						var questionChk = o.questionsChk[j];
						if(questionChk['qType'] == 'num'){
							switch(questionChk['inequality']){
								case '<':
									if(parseFloat(avg) >= parseFloat(questionChk['value'])){
										acums[i].avgValid = false;
									}
									if(parseFloat(sums['ans'+j]) >= parseFloat(questionChk['value'])){
										acums[i].sumValid['ans'+j] = false;
									}
									break;
								case '<=':
									if(parseFloat(avg) > parseFloat(questionChk['value'])){
										acums[i].avgValid = false;
									}
									if(parseFloat(sums['ans'+j]) > parseFloat(questionChk['value'])){
										acums[i].sumValid['ans'+j] = false;
									}
									break;
								case '=':
									if(parseFloat(avg) != parseFloat(questionChk['value'])){
										acums[i].avgValid = false;
									}
									if(parseFloat(sums['ans'+j]) != parseFloat(questionChk['value'])){
										acums[i].sumValid['ans'+j] = false;
									}
									break;
								case '>=':
									if(parseFloat(avg) < parseFloat(questionChk['value'])){
										acums[i].avgValid = false;
									}
									if(parseFloat(sums['ans'+j]) < parseFloat(questionChk['value'])){
										acums[i].sumValid['ans'+j] = false;
									}
									break;
								case '>':
									

									if(parseFloat(avg) <= parseFloat(questionChk['value'])){
										acums[i].avgValid = false;
									}else{
										// console.log(parseFloat(avg) ,questionChk['inequality'], parseFloat(questionChk['value']))	
									}
									if(parseFloat(sums['ans'+j]) <= parseFloat(questionChk['value'])){
										acums[i].sumValid['ans'+j] = false;
									}
									break;
								default:
									break;
							}
						}
					}

				}

				if(meetsAll && i != ''){
					acums[i].countMeetsAll++;
					// console.log('mettsAll:',i,acums[i].countMeetsAll);
				}

			}
			questMax['countMeetsAll'] = Math.max(questMax['countMeetsAll'],acums[i].countMeetsAll);
		}
		// console.log('numRespMax',numRespMax);
		// console.log('numRespMin',numRespMin);
		// console.log('numRespTot',numRespTot);
		// console.log('acums',acums);
		// console.log('valNumMax',valNumMax);
		// console.log('valNumMin',valNumMin);
		// console.log('questMax',questMax);

		var icon = L.icon({
			iconUrl: rz+'/lib/js/leaflet/images/marker-icon.png',
			iconSize:[25,41],
			iconAnchor: [25/2, 41],
		});
		
		// console.log(o.questionsChk);
		var heatPoints = [];
		var numPoints = 0;
		pointsCont = {};

		// console.log('POINTS:',points);
		// console.log('QUESTIONS:',o.questionsChk);
		for(var j in points){

			var pig = points[j];
			// console.log(pig);
			for(var i=0;i<pig.length;i++){

				var feature = pig[i]['geometry'];
				// console.log(feature);
				var geometry = $.parseJSON(feature);
				// prLyr = L.geoJSON(geometry);
				// layerGeoms.addLayer(prLyr);

				if(typeof pointsCont[pig[i]['vId0']] == 'undefined'){
					// console.log(pig[i]);
					pointsCont[pig[i]['vId0']] = 1;
				}else{
					continue;
				}
				// console.log(geometry);
				var meets = true;
				for(h = 0;h<o.questionsChk.length;h++){
					var questionChk = o.questionsChk[h];


					switch(questionChk['qType']){
						case 'mult':
							if(pig[i]['respV'+h] != questionChk['answer']){
								meets = false;
							}
							break;
						case 'num':
							if(questionChk['questionId'] == 'ansNum'){

								if(chkAnsNum[questionChk['chkId']][j] == undefined){
									valAnalysis = 0;	
								}else{
									if(chkAnsNum[questionChk['chkId']][j][i] == undefined){
										valAnalysis = 0;
									}else{
										valAnalysis = parseFloat(chkAnsNum[questionChk['chkId']][j][i].ansNum);
									}
									// console.log(valAnalysis);
								}
							}else{
								valAnalysis = parseFloat(pig[i]['respV'+h])
								// console.log('pig',pig[i]);
							}
							// console.log('valAnalysis',valAnalysis);
							switch(questionChk['inequality']){
								case '<':
									if(valAnalysis < parseFloat(questionChk['value'])){
									}else{
										meets = false;
									}
									break;
								case '<=':
									if(valAnalysis <= parseFloat(questionChk['value'])){
									}else{
										meets = false;
									}
									break;
								case '=':
									if(valAnalysis == parseFloat(questionChk['value'])){
									}else{
										meets = false;
									}
									break;
								case '>=':
									if(valAnalysis >= parseFloat(questionChk['value'])){
									}else{
										meets = false;
									}
									break;
								case '>':
									if(valAnalysis > parseFloat(questionChk['value'])){
									}else{
										meets = false;
									}
									break;
								case 'range':
									meets = false;
									break;
								default:
									break;
							}

							break;
					}

				}
				// console.log(pig[i]);

				if(meets && (j != '' || o.kmlId == -1)){
					// if(typeof pointsCont[pig[i]['vId0']] == 'undefined'){
						// console.log(pig[i]);
					// 	pointsCont[pig[i]['vId0']] = 1;
						prLyr = L.geoJSON(geometry);
						layerGeoms.addLayer(prLyr);
						numPoints++;
						if(o.spatialQType == 'op'){
							// console.log('aaa');
							heatPoints.push({
								lat:geometry.coordinates[1],
								lng:geometry.coordinates[0],
								count:5,
							});
						}

					// }
				}

				// if(o.kmlId == -1){
				// 	cLat = (parseFloat(north)+parseFloat(south))/2;
				// 	cLng = (parseFloat(east)+parseFloat(west))/2;
				// }

				// prLyr.dbId = sa['id'];
				// prLyr.type = type.toLowerCase();
				// notFound.addLayer(prLyr);
			}
		}

		$('#numAns').text(numPoints);
		// console.log(numPoints);

		var cfg = {
		  // radius should be small ONLY if scaleRadius is true (or small radius is intended)
		  // if scaleRadius is false it will be the constant radius used in pixels
		  "radius": 30,
		  "maxOpacity": .8,
		  // scales the radius based on map zoom
		  "scaleRadius": false,
		  // if set to false the heatmap uses the global maximum for colorization
		  // if activated: uses the data maximum within the current map boundaries
		  //   (there will always be a red spot with useLocalExtremas true)
		  "useLocalExtrema": false,
		  // which field name in your data represents the latitude - default "lat"
		  latField: 'lat',
		  // which field name in your data represents the longitude - default "lng"
		  lngField: 'lng',
		  // which field name in your data represents the data value - default "value"
		  valueField: 'count',
		  // gradient: {
		  //   '.5': '#fdb06d',
		  //   '.8': '#ea5e0d',
		  //   '.95': '#be371c'
		  // }

		};
		layerHeatSM = new HeatmapOverlay(cfg);

		
		var heatDatos = {
			max:8,
			data:heatPoints,
		};

		layerHeatSM.setData(heatDatos);



		/////// PINTA UN PUNTO CON COORDENADAS
		// var geometry = $.parseJSON('{"type": "Point", "coordinates": [-57.625783598892, -25.281126368487]}');
		// prLyr = L.geoJSON(geometry,{
		// 	pointToLayer: function (feature, latlng) {
		// 	    return L.marker(latlng,{icon:icon});
		// 	}
		// });
		// notFound.addLayer(prLyr);

		/////// PINTA TODOS LOS PUNTOS
		// for(var j in points){
		// 	var pnf = points[j];
		// 	for(var i=0;i<pnf.length;i++){

		// 		// if(pnf[i].teId != '1133'){
		// 		// 	continue;
		// 		// }
		// 		var feature = pnf[i]['geometry'];
		// 		// console.log(feature);
		// 		var geometry = $.parseJSON(feature);
		// 		prLyr = L.geoJSON(geometry,{
		// 			pointToLayer: function (feature, latlng) {
		// 			    return L.marker(latlng,{icon:icon});
		// 			}
		// 		});
		// 		// prLyr.dbId = sa['id'];
		// 		// prLyr.type = type.toLowerCase();
		// 		notFoundAA.addLayer(prLyr);
		// 	}

		// }

		/////// PINTA PUNTOS NO ENCONTRADOS
		if(pnf != undefined){
			for(var i=0;i<pnf.length;i++){
				var feature = pnf[i]['geometry'];
				// console.log(feature);
				var geometry = $.parseJSON(feature);
				prLyr = L.geoJSON(geometry,{
					pointToLayer: function (feature, latlng) {
					    return L.marker(latlng,{icon:icon});
					}
				});
				// prLyr.dbId = sa['id'];
				// prLyr.type = type.toLowerCase();
				notFoundAA.addLayer(prLyr);
			}
			// console.log(polygons);
		}




		L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 30
		}).addTo(map);

		var hide = {
		  color: 'grey',
		  weight: 1,
		  opacity: 0,
		  fillColor: '#228B22',
		  fill: true,
		  fillOpacity: 0,
		  interactive: false,
		}



		var vectorTileOptions = {
			vectorTileLayerStyles: {
				'KMLGeometries': function(e) {
					// console.log(e);
					var fillColor = null;
					var fillOpacity = 0;
					var opacity = 0;
					var interactive = false;

					if(e.KMLId != o.kmlId){
						return hide;
					}else{
						// console.log(e);
						// console.log(polygons[e.id]);
						if(polygons[e.id] == undefined){
							return hide;
						}
						// console.log(polygons[e.id]);
						fillColor = 'grey';
						fillOpacity = .4;
						opacity = 1;
						interactive = false;
					}

					if(points[e.id] != undefined){

						var acum = acums[e.id];
						if(acum.range){
							var interval = parseFloat(valNumMax)-parseFloat(valNumMin);
							// console.log(interval,step,parseFloat(acum.avgs['ans0']))
							if(interval == 0){
								fillOpacity = 1;
							}else{
								fillOpacity = (parseFloat(acum.avgs['ans0'])-valNumMin)/interval;
							}
						}else{
							// console.log('analysisType',analysisType);
							
							switch(parseInt(analysisType)){
								case 1:
									if(acum.numRespSel == 0){
										fillOpacity = 0;				
									}else{
										// fillOpacity = acum.countMultAns['ans'+j]['value']/acum.numRespSel;
										// console.log(acum);
										fillOpacity = acum.countMeetsAll/acum.numRespSel;
									}
									acum.text = '</strong><?php echo TR("ansNumber"); ?>:</strong>'+acum.numRespSel+'<br/>';
									acum.text += '</strong><?php echo TR("matchingAnswers"); ?>:</strong>'+acum.countMeetsAll+'<br/>';
									acum.text += '</strong><?php echo TR("percent"); ?>:</strong>'+(fillOpacity*100).toFixed(2)+' %<br/>';
									break;
								case 2:
									if(questMax['countMeetsAll'] == 0){
										fillOpacity = 1;
									}else{
										// fillOpacity = acum.countMultAns['ans'+j]['value']/questMax['ans'+j];
										fillOpacity = acum.countMeetsAll/questMax['countMeetsAll'];
									}
									if(acum.numRespSel == 0){
										percent = 0;				
									}else{
										percent = acum.countMeetsAll/acum.numRespSel;
									}

									acum.text = '</strong><?php echo TR("ansNumber"); ?>:</strong>'+acum.numRespSel+'<br/>';
									acum.text += '</strong><?php echo TR("matchingAnswers"); ?>:</strong>'+acum.countMeetsAll+'<br/>';
									acum.text += '</strong><?php echo TR("percent"); ?>:</strong>'+(percent*100).toFixed(2)+' %<br/>';
									break;
								case 3:
									if(acum.avgValid){
										fillOpacity = .8;
									}else{
										fillOpacity = 0;
									}
									acum.text = acum.countMultAns['ans0']['value'];
									break;

								case 4:
									if(acum.sumValid['ans0']){
										fillOpacity = .8;
									}else{
										fillOpacity = 0;
									}
									
									acum.text = acum.sums['ans0'];

									// console.log(acum);
									break;
							}

						}

						if(o.questionsChk[0].questionId == ''){
							fillOpacity = .2;
						}


						fillColor = 'green';
						
						opacity = 1;
						interactive = true;	
					}
					// console.log(e.id,fillOpacity);
					return {
					  color: 'grey',
					  weight: 1,
					  opacity: opacity,
					  fillColor: fillColor,
					  fill: true,
					  fillOpacity:fillOpacity,
					  interactive:interactive,
					}
				},
			},
			interactive: true,	// Make sure that this VectorGrid fires mouse/pointer events
			rendererFactory: L.canvas.tile,

		}

		// Set the coordinate system
		var projection_epsg_no = '900913';
		// Set the variable for storing the workspace:layername
		var campground_geoserverlayer = '<?php echo $geoserverWorkSpaceName; ?>:KMLGeometries';
		// Creating the full vectorTile url
		var tilesURL = 'http://'+server+':8080/geoserver/gwc/service/wmts?REQUEST=GetTile'+
			'&SERVICE=WMTS&VERSION=1.0.0'+
			'&LAYER='+campground_geoserverlayer+
			'&STYLE=' +
			'&TILEMATRIX=EPSG:'+projection_epsg_no +':{z}'+
			'&TILEMATRIXSET=EPSG:' + projection_epsg_no +
			'&FORMAT=application/vnd.mapbox-vector-tile' +
			'&TILECOL={x}' +
			'&TILEROW={y}';

		// console.log(tilesURL);
		// Creating the Leaflet vectorGrid object
		kml_vectorgrid = L.vectorGrid.protobuf(tilesURL, vectorTileOptions);
		// console.log(kml_vectorgrid);

		// Define the action taken once a polygon is clicked. In this case we will create a popup with the camping name
		kml_vectorgrid.on('click', function(e) {
			// console.log(e.layer.properties);
			var identifier = e.layer.properties.identifier == -1?'<i>'+e.layer.properties.id+'</i>':e.layer.properties.identifier;
			var elem = polygons[e.layer.properties.id][0];
			// console.log('elem',polygons[e.layer.properties.id]);
			var text = '<strong>Id : '+identifier+'</strong><br/>';
			// console.log(elem);
			// var i = 0;
			var attrs = {};
			if(Object.keys(elem).length > 2){
				for(var i = 0;i<(Object.keys(elem).length-2)/2;i++){
					if(attrs[elem['aName'+i]] == undefined){
						text += '<strong>'+elem['aName'+i]+" :</strong> "+elem['aValue'+i]+'<br/>';
						attrs[elem['aName'+i]] = 1;
					}
				}
			}

			// for(j = 0;j<o.questionsChk.length;j++){
			// 	var questionChk = o.questionsChk[j];
			// 	if(questionChk['qType'] == 'mult'){
			// 		text += 'Value: '+ acums[elem.id].countMultAns['ans'+j]['text']+'<br/>';
			// 	}
			// }
			// console.log(elem,acums[elem.id]);
			if(o.questionsChk[0].questionId != ''){
				if(acums[elem.id].range){
					text += '<?php echo TR("average"); ?>: '+ (acums[elem.id].avgs.ans0).toFixed(2)+'<br/>';
				}else{
					text += acums[elem.id].text;
				}
			}


			// console.log(acums[elem.id]);
			
		    L.popup()
		      .setContent(text)
		      .setLatLng(e.latlng)
		      .openOn(map);
		  })
		  .addTo(map);

		// Add the vectorGrid to the map
		kml_vectorgrid.addTo(map);

		if(numPoints >= 3){
			map.fitBounds(layerGeoms.getBounds());
		}else{
			if(o.kmlId > 0){
				map.setView([cLat,cLng], 16);
			}
		}

	}

	function drawPolygons(){
		cleanMap();
		map.addLayer(kml_vectorgrid)
	}

	function drawHeatmap(){
		cleanMap();
		map.addLayer(layerHeatSM)
	}

	function drawPoints(){
		cleanMap();
		map.addLayer(layerGeoms)
	}

	function cleanMap(){
		map.removeLayer(kml_vectorgrid);
		map.removeLayer(layerHeatSM);
		map.removeLayer(layerGeoms);
	}


</script>