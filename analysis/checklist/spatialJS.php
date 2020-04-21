
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


		var getSA = jsonF('analysis/checklist/json/json.php',{acc:9,pId:pregId});
		// console.log(getSA);
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
		// var getPRs = problems;
		var PRs = PRBs;
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
				problems.addLayer(prLyr);
				spatialYa = true;
			}

		}



			
	}
</script>
