
<script type="text/javascript">
	var layer;
	var drawnItems;
	$(document).ready(function() {});

	function initMap(pregId,vId,identif){
		var drawHandler = null;
		var spatial = <?php echo atj($spatial); ?>;
		///////// Funcionalidades generales ///////////

		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
		var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
		var map = new L.Map('map_'+pregId, { center: new L.LatLng(0, 0), zoom: 2 });
		drawnItems = L.featureGroup().addTo(map);
		var studyArea = L.featureGroup().addTo(map);

		L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 23
		}).addTo(map);


		var drawControl = new L.Control.Draw({
			edit: {
				featureGroup: drawnItems,
				poly: {
					allowIntersection: false
				}
			},
			draw: {
				circle: false,
				circlemarker: false,
				rectangle: false,
				polyline: false,
				marker: false,

				polygon: {
					allowIntersection: false,
					showArea: true,
				},
				polygon: false,
			}
		});

		var MarkerControl =  L.Control.extend({        
		  options: {
			position: 'topleft'
		  },

		  onAdd: function (map) {
			var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

			container.style.backgroundColor = 'white';     
			container.style.backgroundImage = "url("+rz+"img/ico/marker.png)";
			container.style.backgroundSize = "25px 25px";
			container.style.backgroundRepeat = "no";
			container.style.width = '25px';
			container.style.height = '25px';

			container.onclick = function(){
				if(drawHandler != null){
					drawHandler.disable();
				}
				drawHandler = new L.Draw.Marker(map, drawControl.options.Marker);
				setTimeout(function(){
					drawHandler.enable();
				},100);

			}

			return container;
		  }
		});

		var polygonControl =  L.Control.extend({        
		  options: {
			position: 'topleft'
		  },

		  onAdd: function (map) {
			var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

			container.style.backgroundColor = 'white';     
			container.style.backgroundImage = "url("+rz+"img/ico/polygon.png)";
			container.style.backgroundSize = "25px 25px";
			container.style.backgroundRepeat = "no";
			container.style.width = '25px';
			container.style.height = '25px';

			container.onclick = function(){
				// console.log('buttonClicked');
				if(drawHandler != null){
					drawHandler.disable();
				}
				drawHandler = new L.Draw.Polygon(map, drawControl.options.Polygon);
				drawHandler.options.allowIntersection = false;
				drawHandler.enable();

			}

			return container;
		  }
		});



		var PolylineControl =  L.Control.extend({        
		  options: {
			position: 'topleft'
		  },

		  onAdd: function (map) {
			var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

			container.style.backgroundColor = 'white';     
			container.style.backgroundImage = "url("+rz+"img/ico/polyline.png)";
			container.style.backgroundSize = "25px 25px";
			container.style.backgroundRepeat = "no";
			container.style.width = '25px';
			container.style.height = '25px';

			container.onclick = function(){
				// console.log('buttonClicked');
				if(drawHandler != null){
					drawHandler.disable();
				}
				drawHandler = new L.Draw.Polyline(map, drawControl.options.Polyline);
				drawHandler.enable();

			}



			return container;
		  }
		});

		map.addControl(new polygonControl());
		map.addControl(new PolylineControl());

		
		map.addControl(new MarkerControl());
		map.addControl(drawControl);


		// check if point is in study area.
		map.on('draw:drawvertex', function (e) {

			// console.log('aaa');
			var layers = e.layers._layers;
			var lastLyrId = Object.keys(layers)[Object.keys(layers).length-1];
			// console.log(lastLyrId);
			lastLyr = e.layers._layers[lastLyrId];
			// console.log(lastLyr.getLatLng());
			var cont = false;
			for(var j in studyArea._layers){
				var pol = studyArea._layers[j];
				// console.log(j,pol);
				if(pol.contains(lastLyr.getLatLng())){
					cont = true;
					break;
				}

			}

			if(!cont){
				numPuntos = Object.keys(layers).length;
				if(numPuntos > 1){
					drawHandler.deleteLastVertex();
				}else{
					drawHandler.disable();
				}
				alertar('<?php echo TR('outside'); ?>')
				// console.log('dentro');
			}
		});


		// Add Study Areas

		style = {
			fillColor: '#000000',
			fillOpacity: .2,
			weight: 1,
			color: 'grey'
		};
		var allPoints = addSA(studyArea,'checklist/json/json.php',9,pregId,vId,style);
		if(allPoints.length != 0){
			var group = new L.featureGroup(allPoints);
			map.fitBounds(group.getBounds());
		}

		//// Add answered geometries
		var getPRs = jsonF('checklist/json/json.php',{acc:10,pId:pregId,vId:vId});
		var PRs = $.parseJSON(getPRs);
		// console.log('PRs',PRs);
		for(var prId in PRs){
			var points = [];
			var sa = PRs[prId];

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
					prLyr = L.marker( [ geometry.coordinates[1],geometry.coordinates[0] ] );
					break;
				case 'linestring':
				case 'polyline':
					type = 'polyline';
					for(var i in geometry.coordinates){
						points.push( [ geometry.coordinates[i][1],geometry.coordinates[i][0] ] )
					}
					prLyr = L.polyline(points);
					break;
				case 'polygon':
					for(var i in geometry.coordinates[0]){
						points.push( [ geometry.coordinates[0][i][1],geometry.coordinates[0][i][0] ] )
					}
					prLyr = L.polygon(points);
					break;
				default:
					continue;
					break;
			}

			prLyr.dbId = sa['id'];
			prLyr.type = type.toLowerCase();
			drawnItems.addLayer(prLyr);
			spatialYa = true;

		}




		//////// Funcionalidades a DB ////////


		
		map.on(L.Draw.Event.CREATED, function (event) {
			layer = event.layer;

			// console.log('layerJS:',layer);

			var cont = false;
			if(event.layerType == 'marker'){
				for(var j in studyArea._layers){
					var pol = studyArea._layers[j];
					// console.log(j,pol);
					if(pol.contains(event.layer.getLatLng())){
						cont = true;
						break;
					}
				}
				if(cont){
					cont = true;
				}
			}else{
				cont = true;
			}

			if(cont){
				var latlngs = [];
				var type = event.layerType;
				switch(type){
					/// This if removes polyline and polygon tools if the question is not spatial
					case 'polyline':
						var ll = layer._latlngs;
						break;
					case 'polygon':
						var ll = layer._latlngs;
						break;
					case 'marker':
						var ll = layer._latlng;
						break;
					default:
						break;
				}

				// console.log(spatial)

				var datos = {};
				datos['visitasId'] = <?php echo $_POST['vId']; ?>;
				datos['preguntasId'] = <?php echo $p['id']; ?>;
				datos['identificador'] = '<?php echo $p['identificador']; ?>';
				datos['respuesta'] = 'spatial';
				datos['justificacion'] = '';

				var lls = JSON.stringify(ll);
				// console.log('ll:',ll);
				// console.log('lls:',lls);
				var geo = {};
				geo['latlngs'] = lls;
				geo['type'] = type;

				var hash = '<?php echo $hash; ?>';
				var pIdAct = '<?php echo $pId; ?>';
				// console.log(datos)
				var problem = {};
				problem['type'] = type;

				// console.log('geo: ',geo);

				popUpMapa('checklist/problemsAdd.php',{
					datos:datos,
					hash:hash,
					pIdAct:pIdAct,
					acc:8,
					vId:datos['visitasId'],
					problem:problem,
					geo:geo
				});
			}

		});


		map.on(L.Draw.Event.EDITED, function (event) {

			var layers = event.layers._layers;
			
			var lId = Object.keys(layers)[0]
			
			var layer = layers[lId];

			// console.log('layer: ',layer);
			if(typeof layer != 'undefined'){

				var prId = layer.dbId;

				var type = layer.type;
				console.log('type',type);
				switch(type){
					case 'linestring':
					case 'polyline':
						type = 'polyline';
						var ll = layer._latlngs;
						break;
					case 'polygon':
						var ll = layer._latlngs;
						break;
					case 'marker':
						var ll = layer._latlng;
						break;
					default:
						break;
				}

				var lls = JSON.stringify(ll);
				var geo = {};
				geo['latlngs'] = JSON.stringify(ll);
				geo['type'] = type;
				// console.log(geo);

				var rj = jsonF('checklist/json/json.php',{acc:11,geo:geo,prId:prId,vId:vId});

				// console.log(rj);

				var r = $.parseJSON(rj);
				if(r.ok == 1){
					console.log("OK");
					// layer.dbId = r.saId;
					// drawnItems.addLayer(layer);
				}
			}

			// console.log(event);
		});

		map.on(L.Draw.Event.DELETED, function (event) {

			var layers = event.layers._layers;

			var lIds = [];
			for(var i in layers){
				lIds.push(layers[i].dbId);
			}

			var pId = <?php echo $p['id']; ?>;

			if(lIds.length != 0){
				var rj = jsonF('checklist/json/json.php',{acc:12,lIds:lIds,vId:vId,pId:pId});
					
				// console.log(rj);

				var r = $.parseJSON(rj);
				// console.log('R: ',r)
				if(r.count == 0){
					spatialYa = false;
				}
			}


		});

		navigator.geolocation.getCurrentPosition(function(location) {
		  // console.log(location.coords.latitude);
		  // console.log(location.coords.longitude);
		  // console.log(location.coords.accuracy);

		  map.setView([location.coords.latitude,location.coords.longitude], 16);
		});


			
	}
</script>
