
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

		L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieHBla3RybyIsImEiOiJjazkzbDAzbWYwMTh1M2ZtbTBmOTlobDBpIn0.3lV0q43I-oC7mBSVzzBAXA', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
				'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			id: 'mapbox/streets-v11',
			tileSize: 512,
			zoomOffset: -1
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


		var getSA = jsonF('checklist/json/json.php',{acc:9,pId:pregId,vId:vId});
		var SAs = $.parseJSON(getSA);
		
		var allPoints = [];
		for(var saId in SAs){
			var points = [];
			var sa = SAs[saId];
			// console.log(sa);
			for(var i = 0; i<sa.length; i++){
				// console.log(sa[i]);
				allPoints.push(L.marker([ sa[i]['lat'],sa[i]['lng'] ]));
				points.push( [ sa[i]['lat'],sa[i]['lng'] ] );
			// 	// points.push([sa[i]['lat'],sa[id]['lng']]);
			}

			var polygon = L.polygon(points);
			polygon.setStyle({
				fillColor: '#000000',
				fillOpacity: .2,
				weight: 1,
				color: 'grey'
			});
			polygon.dbId = saId;

			studyArea.addLayer(polygon);
		}
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
			// console.log(sa);
			for(var i = 0; i<sa.length; i++){
				if(sa[i]['lat'] == null || sa[i]['lng'] == null){
					continue;
				}
				points.push( [ sa[i]['lat'],sa[i]['lng'] ] );
			// 	// points.push([sa[i]['lat'],sa[id]['lng']]);
			}
			var type = PRs[prId][0].type;
			// console.log('type: ',type);
			// console.log(points);
			var prLyr;
			// if(type == null){
			// 	continue;
			// }

			switch(type){
				case 'marker':
					prLyr = L.marker(points[0]);
					break;
				case 'polyline':
					prLyr = L.polyline(points);
					break;
				case 'polygon':
					prLyr = L.polygon(points);
					break;
				default:
					continue;
					break;

			}

			prLyr.dbId = prId;
			// console.log(prId);
			// console.log('points.lenght:',points.length);
			if(points.length > 0){
				drawnItems.addLayer(prLyr);
				spatialYa = true;
			}

		}




		//////// Funcionalidades a DB ////////


		
		map.on(L.Draw.Event.CREATED, function (event) {
			layer = event.layer;

			console.log('layerJS:',layer);

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
					case 'polyline':
						var lns = layer._latlngs;
						for(var i = 0; i< lns.length; i++){
							latlngs.push({lat:lns[i]['lat'],lng:lns[i]['lng']});
						}

						break;
					case 'polygon':
						var lns = layer._latlngs[0];
						for(var i = 0; i< lns.length; i++){
							latlngs.push({lat:lns[i]['lat'],lng:lns[i]['lng']});
						}
						break;
					case 'marker':
						var lns = layer._latlng;
						// console.log('Layer:',layer);
						latlngs.push({lat:lns['lat'],lng:lns['lng']})
						break;
				}

				// console.log(spatial)

				var datos = {};
				datos['visitasId'] = <?php echo $_POST['vId']; ?>;
				datos['preguntasId'] = <?php echo $p['id']; ?>;
				datos['identificador'] = '<?php echo $p['identificador']; ?>';
				datos['respuesta'] = 'spatial';
				datos['justificacion'] = '';

				var hash = '<?php echo $hash; ?>';
				var pIdAct = '<?php echo $pId; ?>';
				// console.log(datos)
				var problem = {};
				problem['type'] = type;

				popUpMapa('checklist/problemsAdd.php',{
					datos:datos,
					hash:hash,
					pIdAct:pIdAct,
					acc:8,
					vId:datos['visitasId'],
					problem:problem,
					latlngs: latlngs,
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

				var lns;
				if(typeof layer._latlngs != 'undefined'){
					lns = layer._latlngs[0];
				}else{
					lns = [layer._latlng];
				}
				var latlngs = [];			
				for(var i = 0; i< lns.length; i++){
					latlngs.push({lat:lns[i]['lat'],lng:lns[i]['lng']});
				}

				var rj = jsonF('checklist/json/json.php',{acc:11,latlngs:latlngs,prId:prId,vId:vId});

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



			
	}
</script>
