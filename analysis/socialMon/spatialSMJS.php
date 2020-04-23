
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

		L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieHBla3RybyIsImEiOiJjazkzbDAzbWYwMTh1M2ZtbTBmOTlobDBpIn0.3lV0q43I-oC7mBSVzzBAXA', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
				'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			id: 'mapbox/streets-v11',
			tileSize: 512,
			zoomOffset: -1
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
			var pr = PRs[prId];
			// console.log(pr);
			for(var i = 0; i<pr.length; i++){
				if(pr[i]['lat'] == null || pr[i]['lng'] == null){
					continue;
				}
				points.push( [ pr[i]['lat'],pr[i]['lng'] ] );
				heatPoints.push( [ pr[i]['lat'],pr[i]['lng'] ] );
				allPoints.push( L.marker([ pr[i]['lat'],pr[i]['lng'] ]) );
				

			// 	// points.push([pr[i]['lat'],pr[id]['lng']]);
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
					prLyr = L.marker(points[0]).bindPopup("<strong>"+pr[0].deName+"</strong><br/>value: "+pr[0].respName);
					break;
				case 'polyline':
					prLyr = L.polyline(points).bindPopup("<strong>"+pr[0].deName+"</strong><br/>value: "+pr[0].respName);
					break;
				case 'polygon':
					prLyr = L.polygon(points).bindPopup("<strong>"+pr[0].deName+"</strong><br/>value: "+pr[0].respName);
					break;
				default:
					continue;
					break;

			}

			prLyr.dbId = prId;
			if(points.length > 0){
				layerPointsSM.addLayer(prLyr);
				

				spatialYa = true;
			}

		}
		if(allPoints.length != 0){
			var group = new L.featureGroup(allPoints);
			map.fitBounds(group.getBounds());
		}

		layerHeatSM = L.heatLayer(heatPoints, {radius: 25});

		return map;

			
	}

	function pintaPuntos(){
		mapSM.removeLayer(layerHeatSM)
		mapSM.addLayer(layerPointsSM)
	}

	function pintaCalor(){
		mapSM.removeLayer(layerPointsSM)
		mapSM.addLayer(layerHeatSM)

	}

</script>
