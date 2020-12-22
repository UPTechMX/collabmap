<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Projects
	
	$KML = $db->query("SELECT * FROM KML WHERE id = $_POST[KMLId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($KML);
	$center = [ ($KML['north']+$KML['south'])/2, ($KML['east']+$KML['west'])/2 ];
	// print2($center);

?>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('map'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<div id="map" class="map" style="height: 400px"></div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
	</div>
</div>

<script>
	setTimeout(function(){

		var server = '<?php echo $_SERVER['HTTP_HOST']; ?>';

		var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
		var osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
		var map = new L.Map('map', { center: new L.LatLng(0, 0), zoom: 2 });
		var markers = L.featureGroup().addTo(map);
		var polygons = L.featureGroup().addTo(map);
		var municipios = L.featureGroup();


		L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 23
		}).addTo(map);

		var vectorTileOptions = {
			vectorTileLayerStyles: {
				'KMLGeometries': function(e) {
					// console.log(e);
					var fillColor = null;
					var fillOpacity = 0;
					var opacity = 0;
					var interactive = false;
					if(e.KMLId != <?php echo $_POST['KMLId']; ?>){
						fillColor = '#228B22';
						fillOpacity = 0;
						opacity = 0;
						interactive = false;
					}else{
						fillColor = 'blue';
						fillOpacity = .8;
						opacity = 1;
						interactive = true;
					}
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
		var campground_geoserverlayer = 'CMPy:KMLGeometries';
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

		// Creating the Leaflet vectorGrid object
		var kml_vectorgrid = L.vectorGrid.protobuf(tilesURL, vectorTileOptions)

		// Define the action taken once a polygon is clicked. In this case we will create a popup with the camping name
		kml_vectorgrid.on('click', function(e) {
			console.log(e.layer.properties);
		    // L.popup()
		    //   .setContent(
		    //   	'Estado: '+e.layer.properties.estado+'<br/>'+
		    //   	'Municipio: '+e.layer.properties.municip+'<br/>'+
		    //   	'Activos: '+e.layer.properties.activos+'<br/>'+
		    //   	'Acumulados: '+e.layer.properties.acumlds+'<br/>'+
		    //   	'Muertes: '+e.layer.properties.muertes+'<br/>'+
		    //   	'Recuperados: '+e.layer.properties.recprds+'<br/>'
		    //   )
		    //   .setLatLng(e.latlng)
		    //   .openOn(map);
		  })
		  .addTo(map);

		// Add the vectorGrid to the map
		kml_vectorgrid.addTo(map);

		// Set the map view. In this case we set it to the Netherlands
		map.setView([<?php echo $center[0]; ?>,<?php echo $center[1]; ?>], 17);

	},300)

</script>

