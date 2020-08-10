
<script type="text/javascript">

	function initMapMS(pregId, PRBs){
		var drawHandler = null;
		///////// Funcionalidades generales ///////////

		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
		var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
		var map = new L.Map('mapSM', { center: new L.LatLng(0, 0), zoom: 2 });
		layerPointsSM = L.featureGroup().addTo(map);
		var studyArea = L.featureGroup().addTo(map);

		L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 23
		}).addTo(map);


		// Add Study Areas
		style = {
			fillColor: '#000000',
			fillOpacity: .2,
			weight: 1,
			color: 'grey'
		}
		addSA(studyArea,'analysis/checklist/json/json.php',9,pregId,0,style);
		// if(allPoints.length != 0){
		// 	var group = new L.featureGroup(allPoints);
		// 	map.fitBounds(group.getBounds());
		// }

		//// Add answered geometries
		// var getPRs = layerPointsSM;
		var PRs = PRBs;
		// console.log('PRs',PRs);

		var heatPoints = [];
		var allPoints = [];

		for(var prId in PRs){
			var points = [];
			var sa = PRs[prId];
			// console.log('PRs[prId]: ',PRs[prId]);
			if(sa.geometry == null){
				continue;
			}
			var prLyr;
			var geometry = $.parseJSON(sa.geometry);
			var type = geometry.type.toLowerCase();
			// console.log('type',type);
			switch(type){
				case 'point':
				case 'marker':
					type = 'marker';
					// console.log([ geometry.coordinates[1],geometry.coordinates[0] ]);
					// prLyr = L.marker( [ geometry.coordinates[1],geometry.coordinates[0] ] );
									// allPoints.push( L.marker([ pr[i]['lat'],pr[i]['lng'] ]) );

					heatPoints.push({
						lat:geometry.coordinates[1],
						lng:geometry.coordinates[0],
						count:3,
					});
					allPoints.push( L.marker([ geometry.coordinates[1],geometry.coordinates[0] ]) );
					var icon = L.icon({
						iconUrl: rz+'/lib/js/leaflet/images/marker-icon.png',
						iconSize:[25,41],
					})
					prLyr = L.geoJSON(geometry,{
						pointToLayer: function (feature, latlng) {
						    return L.marker(latlng,{icon:icon});
						}
					});

					break;
				case 'linestring':
				case 'polyline':
					type = 'polyline';
					for(var i in geometry.coordinates){
						// points.push( [ geometry.coordinates[i][1],geometry.coordinates[i][0] ] )
						heatPoints.push({
							lat:geometry.coordinates[i][1],
							lng:geometry.coordinates[i][0],
							count:3,
						});
						allPoints.push(  L.marker([ geometry.coordinates[i][1],geometry.coordinates[i][0] ])  );
					}
					prLyr = L.geoJSON(geometry)
					break;
				case 'polygon':
					for(var i in geometry.coordinates[0]){
						// points.push( [ geometry.coordinates[0][i][1],geometry.coordinates[0][i][0] ] )
						heatPoints.push({
							lat:geometry.coordinates[0][i][1],
							lng:geometry.coordinates[0][i][0],
							count:3,
						});
						allPoints.push(  L.marker([ geometry.coordinates[0][i][1],geometry.coordinates[0][i][0] ])  );

					}
					prLyr = L.geoJSON(geometry);
					break;
				default:
					continue;
					break;
			}

			prLyr.bindPopup("<strong>"+sa.deName+"</strong><br/>value: "+sa.respName);;
			prLyr.dbId = sa['id'];
			prLyr.type = type.toLowerCase();
			layerPointsSM.addLayer(prLyr);

		}

		// console.log('allPoints',allPoints);
		if(allPoints.length != 0){
			var group = new L.featureGroup(allPoints);
			map.fitBounds(group.getBounds());
		}
		
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

		////

		// console.log(map);
		return map;

			
	}

	function pintaPuntos(){
		// console.log(mapSM);
		mapSM.removeLayer(layerHeatSM)
		mapSM.addLayer(layerPointsSM)
	}

	function pintaCalor(){
		mapSM.removeLayer(layerPointsSM)
		mapSM.addLayer(layerHeatSM)

	}

</script>
