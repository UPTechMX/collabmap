
<script type="text/javascript">
	$(document).ready(function() {});

	function initMap(pregId, PRBs){
		var drawHandler = null;
		///////// Funcionalidades generales ///////////

		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
		var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
		var map = new L.Map('map_'+pregId, { center: new L.LatLng(0, 0), zoom: 2 });
		var problems = L.featureGroup().addTo(map);
		var studyArea = L.featureGroup().addTo(map);

		// L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieHBla3RybyIsImEiOiJjazkzbDAzbWYwMTh1M2ZtbTBmOTlobDBpIn0.3lV0q43I-oC7mBSVzzBAXA', {
		// 	maxZoom: 18,
		// 	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
		// 		'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
		// 		'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		// 	id: 'mapbox/streets-v11',
		// 	tileSize: 512,
		// 	zoomOffset: -1
		// }).addTo(map);

		L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 30
		}).addTo(map);


		// Add Study Areas
		style = {
			fillColor: '#000000',
			fillOpacity: .2,
			weight: 1,
			color: 'grey'
		}

		var allPoints = addSA(studyArea,'analysis/checklist/json/json.php',9,pregId,0,style);
		var getSA = jsonF('analysis/checklist/json/json.php',{acc:9,pId:pregId});
		if(allPoints.length != 0){
			var group = new L.featureGroup(allPoints);
			map.fitBounds(group.getBounds());
		}

		//// Add answered geometries
		// var getPRs = problems;
		var PRs = PRBs;
		// console.log('PRs',PRs);
		var heatPoints = [];

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
					heatPoints.push( [ geometry.coordinates[1],geometry.coordinates[0] ] );
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
						heatPoints.push(  [ geometry.coordinates[i][1],geometry.coordinates[i][0] ]  )
					}
					prLyr = L.geoJSON(geometry);
					break;
				case 'polygon':
					for(var i in geometry.coordinates[0]){
						// points.push( [ geometry.coordinates[0][i][1],geometry.coordinates[0][i][0] ] )
						heatPoints.push(  [ geometry.coordinates[0][i][1],geometry.coordinates[0][i][0] ]  )

					}
					prLyr = L.geoJSON(geometry);
					break;
				default:
					continue;
					break;
			}

			

			
			prLyr.dbId = sa['id'];
			prLyr.type = type.toLowerCase();
			problems.addLayer(prLyr);

		}

		layerHeatSM = L.heatLayer(heatPoints, {radius: 25});

		function pintaPuntos(){
			map.removeLayer(layerHeatSM)
			map.addLayer(problems)
		}

		function pintaCalor(){
			map.removeLayer(problems)
			map.addLayer(layerHeatSM)
		}

		$('#heat_'+pregId).click(function(event) {
			pintaCalor();
		});

		$('#markers_'+pregId).click(function(event) {
			pintaPuntos();
		});

	}
</script>
