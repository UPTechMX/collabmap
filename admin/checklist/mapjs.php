<script type="text/javascript">
	$(document).ready(function() {

		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	    var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
	    var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
	    var map_<?php echo $p['id'];?> = new L.Map('map_<?php echo $p['id'];?>', { center: new L.LatLng(0, 0), zoom: 2 });
	    var drawnItems = L.featureGroup().addTo(map_<?php echo $p['id'];?>);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        	maxZoom: 18,
        	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        		'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        		'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        	id: 'mapbox/streets-v11',
        	tileSize: 512,
        	zoomOffset: -1
        }).addTo(map_<?php echo $p['id'];?>);

		map_<?php echo $p['id'];?>.addControl(new L.Control.Draw({
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
    		    marker: false,
    		    polyline: false,

		        polygon: {
		            allowIntersection: false,
		            showArea: true
		        }
		    }
		}));

		map_<?php echo $p['id'];?>.on(L.Draw.Event.CREATED, function (event) {
		    var layer = event.layer;

		    var pId = <?php echo $p['id']; ?>;

		    var lns = layer._latlngs[0];
		    var latlngs = [];
		    for(var i = 0; i< lns.length; i++){
		    	latlngs.push({lat:lns[i]['lat'],lng:lns[i]['lng']});
		    }
		    var rj = jsonF('admin/checklist/json/json.php',{acc:8,latlngs:latlngs,n:1,pId:pId});
		    // console.log(rj);

		    var r = $.parseJSON(rj);
		    if(r.ok == 1){
		    	layer.dbId = r.saId;
		    	drawnItems.addLayer(layer);
		    }

		});

		map_<?php echo $p['id'];?>.on(L.Draw.Event.EDITED, function (event) {

			var layers = event.layers._layers;
			
			var lId = Object.keys(layers)[0]
			
			var layer = layers[lId];

			// console.log('layer: ',layer);
			if(typeof layer != 'undefined'){
				var saId = layer.dbId;

				var lns = layer._latlngs[0];
				var latlngs = [];			
				for(var i = 0; i< lns.length; i++){
					latlngs.push({lat:lns[i]['lat'],lng:lns[i]['lng']});
				}

				var rj = jsonF('admin/checklist/json/json.php',{acc:8,latlngs:latlngs,n:0,saId:saId});
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

		map_<?php echo $p['id'];?>.on(L.Draw.Event.DELETED, function (event) {

			var layers = event.layers._layers;

			var lIds = [];
			for(var i in layers){
				lIds.push(layers[i].dbId);
			}

			if(lIds.length != 0){
				var rj = jsonF('admin/checklist/json/json.php',{acc:10,lIds:lIds});
				// console.log(rj);
			}

			// console.log(lIds);

		    // console.log(event);
		});

		var getSA = jsonF('admin/checklist/json/json.php',{acc:9,pId:<?php echo $p['id']; ?>});
		// console.log(getSA);
		var SAs = $.parseJSON(getSA);

		// console.log(SA);

		for(var saId in SAs){
			var points = [];
			var sa = SAs[saId];
			// console.log(sa);
			for(var i = 0; i<sa.length; i++){
				// console.log(sa[i]);
				points.push( [ sa[i]['lat'],sa[i]['lng'] ] );
			// 	// points.push([sa[i]['lat'],sa[id]['lng']]);
			}

			var polygon = L.polygon(points);
			polygon.dbId = saId;

			drawnItems.addLayer(polygon);

		}



	});
</script>
