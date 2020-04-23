<script type="text/javascript">

	function initMap(pregId){
		// console.log('aaa');
		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	    var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
	    var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
	    var map = new L.Map('map_'+pregId, { center: new L.LatLng(0, 0), zoom: 2 });
	    var drawnItems = L.featureGroup().addTo(map);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieHBla3RybyIsImEiOiJjazkzbDAzbWYwMTh1M2ZtbTBmOTlobDBpIn0.3lV0q43I-oC7mBSVzzBAXA', {
        	maxZoom: 18,
        	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        		'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        		'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        	id: 'mapbox/streets-v11',
        	tileSize: 512,
        	zoomOffset: -1
        }).addTo(map);

		map.addControl(new L.Control.Draw({
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

		map.on(L.Draw.Event.CREATED, function (event) {
		    var layer = event.layer;

		    var pId = pregId;

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

		map.on(L.Draw.Event.EDITED, function (event) {

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

		map.on(L.Draw.Event.DELETED, function (event) {

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

		var allPoints = addSA(drawnItems,'admin/checklist/json/json.php',9,pregId);
		// var allPoints = drawSA(drawnItems,pregId);

		if(allPoints.length != 0){
			var group = new L.featureGroup(allPoints);
			map.fitBounds(group.getBounds());
		}

		subArch($('#uplStudyArea_'+pregId),3,'studyArea_'+pregId+'_','kml',false,function(a){
			// console.log('vvv');
			file = a.prefijo+a.nombreArchivo;
			// console.log(file,drawnItems);
			// $('#nomArch').text(a.nombreArchivo);
			var rj = jsonF('admin/checklist/json/importKML.php',{file:file,pregId:pregId});
			// console.log(rj);
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				drawnItems.clearLayers();
				var allPoints = drawSA(drawnItems,pregId);

				if(allPoints.length != 0){
					var group = new L.featureGroup(allPoints);
					map.fitBounds(group.getBounds());
				}
			}

		},false,"<?php echo TR('select'); ?>","<?php echo TR('extErrorStr'); ?>")
			
	}

</script>
