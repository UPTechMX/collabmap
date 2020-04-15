
  // 0 para prod, 1 para pruebas
  var varDebug = 0;

  var marker_default  =   rz+'img/ico/iu-ico-blue.png';
  var marker_active  =   rz+'img/ico/iu-ico-green.png';

  var iconW = 40;
  var iconH = 60;


function getIconFromColor(color, activo){

  if( color!= null ){
    if(color == "gifGeo"){
      return rz+"img/ico/pin.png";
    }

    return rz+"img/ico/iu-ico-"+color+".png";
  }
  switch(activo){
    case 0:
    return rz+"img/ico/iu-ico-blue.png";
    case 1:
    return rz+"img/ico/iu-ico-green.png";

  }

}


function redibujaMarker(marker, active) {


 if(! marker instanceof Object)
  return;

 if(varDebug==1)
   console.log("redibujamarker", marker, active, typeof marker.icono);

  if(active){
    if(typeof marker.iconoActivo == "string")
      marker.setIcon(new H.map.Icon(marker.iconoActivo, {size: {w: iconW+25, h: iconH+25}}));
    else
      marker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW+25, h: iconH+25}}));
  }
  else{
    if(typeof marker.icono == "string")
      marker.setIcon(new H.map.Icon(marker.icono, {size: {w: iconW, h: iconH}}));
    else
      marker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
  }
}

function Mapa(dirPost, mapDiv, panelDiv, bubbles=true) {
  

  this.dirPost = dirPost;
  this.mapDiv = mapDiv;
  this.panelDiv =panelDiv;
  this.bubbles =bubbles;
  this.funcionClick = function(){};
  this.activeMarker = null;
  var bounds = null;

  var userMarker;

  var groups = {};

  var group = null;

  var dirPost = dirPost;
  
  var dirCalle;
  var direcciones;
  var coords;
  var position;
  var markerPos;
  var withPosition = false;
  var conPanel=true;
  var marker;
  var behavior;
  var ui;
  var map = null;
  var platform;
  var defaultLayers;
  var bubbleButton = false;
  var bubbleButtonText;
  var changeActiveMarker = false;
  var segundaVuelta=false;
  var prevActiveMarker = null;




  // $('#'+panel).empty();

  // map.setBaseLayer(defaultLayers.satellite.traffic);
  var panel = document.getElementById(panelDiv);

  if(varDebug==1)
    console.log("Setting up panel to>", panel);

  if(panel == null)
    conPanel = false;
  else
    $(panel).empty();

  var mapa = document.getElementById(mapDiv);

  // Hold a reference to any infobubble opened
  var bubble;

  // informativa
  this.getDiv = function(){
    return this.mapDiv;
  }

  this.getBounds = function() {
    return bounds;
  }

  this.vaciaDivs =function(){
    $('#'+this.mapDiv).empty();
    $('#'+this.panelDiv).empty();
  }

  this.returnMap = function(){return map;}

  this.creaMapa = function(){

    $('#'+this.mapDiv).empty();


  platform = new H.service.Platform({
    app_id: 'IqINkH9YGqaYrxvBtT7U',
    app_code: 'A4AoO6njodRaN5HlVASHgA',
    useCIT: true,
    useHTTPS: true
  });
  defaultLayers = platform.createDefaultLayers();

// normal.night.grey
  // if(!withPosition){
    map = new H.Map(document.getElementById(this.mapDiv),
      defaultLayers.normal.traffic,{
        center: {lat:19.376, lng:-99.1034},
        zoom: 10
      });
 //  }
 //  else{
 //   map = new H.Map(document.getElementById(this.mapDiv),
 //    defaultLayers.satellite.traffic,{
 //      center: {lat:.lat, lng:position.lng},
 //      zoom: 18
 //    }); 
 // }

 // $('#'+mapita).empty();


  // MapEvents enables the event system
  // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
  behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

  // Create the default UI components
  ui = H.ui.UI.createDefault(map, defaultLayers, 'es-ES');
  
}


  //   map.addEventListener('tap', function (evt) {
  //     console.log("sdf");
  //     var coords =  map.screenToGeo(evt.currentPointer.viewportX, evt.currentPointer.viewportY);
  //     markerMasCercano(coords);
  //   }, false);
  // }


  /**

  opts = { 
  activar: [true | false ] // activa el punto más cercano
  alerta:  [true | false ] // envía alerta
  alertaText :  string     // texto para la alerta
  grupo :  string          // identificador de un grupo de marcadores particular para buscar sobre ese
  
  */
  this.markerMasCercano = function(lat, lng, opts = {}) {

    coords = new H.geo.Point(lat, lng);

    var minDist = 999999999999,
    cercanoTxt = '*Ninguno*',
    cercano = null,
    markerDist,
    i;

    opts = opts;

    if(varDebug==1)
      console.log("OPTS:", opts);

    var objects;

    if(varDebug==1)
      console.log(groups)



    if(opts.grupo==null){
      objects = map.getObjects();
    }else{
      if(!groups[opts.grupo]){
        if(varDebug==1)
          console.log("BUSCAR:::: No existe el grupo deseado ["+opts.grupo+"]");
        return;
      }
      objects = groups[opts.grupo]['objeto'].getObjects();
    }

    var len = objects.length;

    // console.log("Cercanias", coords, objects)

    for (i = 0; i < len; i += 1) {
      if(!objects[i]['label']){
        continue;
      }
      markerDist = objects[i].getPosition().distance(coords);
      // console.log("qqqq", objects[i]['label'], objects[i].getPosition(), markerDist);

      if (markerDist < minDist) {
        minDist = markerDist;
        cercano = objects[i];
        cercanoTxt = objects[i]['label']+" ( "+objects[i].getPosition().lat+", "+objects[i].getPosition().lng+" )";
      }
    }

    if(opts.alerta){
      var text ="";
      if(opts.textoIn)
        text = textoIn;
      else {
        text = 'El punto más cercano a tu ubicación ';
        if(opts.grupo!= null)
          text+= ' para el grupo '+opts.grupo;
        text += ' es: '
      }

      alertar(text + cercanoTxt, function(){}, {});
    }

    if(opts.activar){
      this.activeMarker = cercano;
      cercano.dispatchEvent('tap');
      redibujaMarker(this.activeMarker, 1);
    }

    return cercano;
  }


this.addUserPosition = function(lat, lng){

  if(varDebug==1)
    console.log("[userposition]");
  
  if(userMarker)
    map.removeObject(userMarker);

  position = { lat: lat, lng: lng};
  withPosition =true;
          
  var domElement = $('<img>').attr('id', "domElement");
  domElement.attr('src', rz+"img/ico/gifGeo.gif");
  domElement.css({ width : '30px', height : '30px' });

  domElement.appendTo(document.body);
  // domElement.style.backgroundColor = 'blue';

  // icon = new H.map.DomIcon(domElement);
  icon = new H.map.DomIcon(
  '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" ' + 
  'y="0px" style="margin:-112px 0 0 -32px" width="136px"' + 
  'height="150px" viewBox="0 0 136 150"><ellipse fill="#000" ' +
  'cx="32" cy="128" rx="36" ry="4"><animate attributeName="cx" ' + 
  'from="32" to="32" begin="0s" dur="1.5s" values="96;32;96" ' + 
  'keySplines=".6 .1 .8 .1; .1 .8 .1 1" keyTimes="0;0.4;1"' + 
  'calcMode="spline" repeatCount="indefinite"/>' +  
  '<animate attributeName="rx" from="36" to="36" begin="0s"' +
  'dur="1.5s" values="36;10;36" keySplines=".6 .0 .8 .0; .0 .8 .0 1"' + 
  'keyTimes="0;0.4;1" calcMode="spline" repeatCount="indefinite"/>' +
  '<animate attributeName="opacity" from=".2" to=".2"  begin="0s" ' +
  ' dur="1.5s" values=".1;.7;.1" keySplines=" .6.0 .8 .0; .0 .8 .0 1" ' +
  'keyTimes=" 0;0.4;1" calcMode="spline" ' +
  'repeatCount="indefinite"/></ellipse><ellipse fill="#1b468d" ' +
  'cx="26" cy="20" rx="16" ry="12"><animate attributeName="cy" ' +
  'from="20" to="20" begin="0s" dur="1.5s" values="20;112;20" ' +
  'keySplines=".6 .1 .8 .1; .1 .8 .1 1" keyTimes=" 0;0.4;1" ' +
  'calcMode="spline" repeatCount="indefinite"/> ' +
  '<animate attributeName="ry" from="16" to="16" begin="0s" ' + 
  'dur="1.5s" values="16;12;16" keySplines=".6 .0 .8 .0; .0 .8 .0 1" ' +
  'keyTimes="0;0.4;1" calcMode="spline" ' +
  'repeatCount="indefinite"/></ellipse></svg>');
  marker = new H.map.DomMarker(position, {icon : icon});
            
  userMarker = marker;

  map.addObject(marker);

  // console.log("bounds", bounds, typeof bounds, groups);


  if(bounds !=null)
    bounds = bounds.mergeLatLng(lat,lng);
  else
    bounds = marker.getPosition().getBounds();

  if(varDebug==1)
    console.log("getBoundsUSER ", bounds,this);
  map.setViewBounds(bounds);
  map.setCenter(bounds.getCenter());

  ajustaZoom();

}


this.setBubbles = function(bool){
  this.bubbles = bool;
}

this.setMarkers = function(porDefault, active){
  marker_default = rz+'img/ico/iu-ico-'+porDefault+'.png'; 
  marker_active = rz+'img/ico/iu-ico-'+active+'.png'; 
}

this.setMarkerSize = function(width, height){
  iconW = width;
  iconH = height;
}

this.changeActiveMarker = function(bool){
  changeActiveMarker = bool;
}

this.bubbleButton = function(bool, text){
  bubbleButton = bool;
  bubbleButtonText = text;

  // console.log("rrrrrrrrrrrrrrrr", $('#mapaDiv #btnBubble'), this.funcionClick );

  if(bubbleButton){
    // funcClick = this.funcionClick;
    $('#'+this.mapDiv).on('click', '#btnBubble', function() {
      this.funcionClick();
    }.bind(this));
  }
}.bind(this)

this.setFuncionClick = function(func){
  // console.log(typeof func, func instanceof Function, func);
  this.funcionClick = func;
  // console.log("pppppppppppppp", this.funcionClick);
  // this.args = args;
}


this.setDireccionBusqueda = function(direccion){
  this.dirPost = direccion;
  this.dirCalle = direccion.calle;
}

this.activaMarker = function(id){
  
  if(!id){
    redibujaMarker(this.activeMarker, 0);
  }

  var objects = map.getObjects(),
  len = map.getObjects().length,
  i;

  if(varDebug==1)
    console.log("prev",prevActiveMarker);
  if(prevActiveMarker != null)
    console.log("previd> "+prevActiveMarker.valor, "this id> "+id);

  for (i = 0; i < len; i += 1) {
    // markerDist = objects[i].getPosition().distance(coords);
    if (typeof objects[i].valor != "undefined" && objects[i].valor == id) {

      prevActiveMarker = this.activeMarker;

      if(prevActiveMarker != null){
        // console.log("entre a cambiar icono antiguo por default...", prevActiveMarker, marker_default);
        redibujaMarker(prevActiveMarker, 0);
        // prevActiveMarker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
      }

      this.activeMarker = objects[i];        
      redibujaMarker(this.activeMarker, 1);
      // this.activeMarker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW, h: iconH}}));
    }
  }
}

this.addSingleCoord = function(lat, lng, val, label="label", colorDeflt=null, colorActivo=null, dibuja=false) {

  if(varDebug==1)
    console.log("RUNNING!");
  coords=[];
  coords.push({
    lat: lat,
    lng: lng,
    label: label,
    val: val
  });
  var i=1;
  var groupName="";

  do{
    groupName = "grupo_" + (i++);
    // console.log(typeof groups[groupName]);
  }
  while(typeof groups[groupName] != "undefined" && i<30)

  this.setManyCoordsAsGroup(groupName, coords, colorDeflt, colorActivo);

  withPosition = true;

  if(dibuja)
    this.dibujaGrupo(groupName);

  // console.log(groupName);

  return groupName;

}


// static function getIcon(color) {

// }

this.setManyCoordsAsGroup = function(groupId, inCoords, colorDefault=null, colorActivo=null){

  if(inCoords.length == 0)
    return;
  // coords = inCoords;
  // groups.push({'id':groupId, 'coords':inCoords});
  groups[groupId] = {'locations' : inCoords, color : colorDefault, id: groupId, icono: getIconFromColor(colorDefault, 0), iconoActivo: getIconFromColor(colorActivo, 1) };

  withPosition = true;

}

this.removeGroup = function(group) {
  if(typeof groups[grupo] !== "undefined"){
    map.removeObject(groups[grupo]['objeto']);
    delete groups[grupo];
    return 1;
  }
  else
    throw new ReferenceError('El grupo solicitado no existe...');  
}


function clickFunc(mapaIn){
 
  if(varDebug==1)
    console.log("clickFunc", changeActiveMarker, prevActiveMarker, bubbleButton);

  if(bubbleButton){ 
    $('#btnBubble').click(function(){
      if(changeActiveMarker){
        if(prevActiveMarker != null)
          redibujaMarker(prevActiveMarker, 0);
          // prevActiveMarker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
        // console.log("Cambio de icono activado por click en botoncito");
        redibujaMarker(mapaIn.activeMarker, 1);
        // if(typeof mapaIn.activeMarker.icono == "String")
        //   mapaIn.activeMarker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW, h: iconH}}));
        // else
        //   mapaIn.activeMarker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW, h: iconH}}));
      }
      bubble.close();
    });
  }
  else{
    if(changeActiveMarker){
      if(varDebug==1)
        console.log("Cambio de icono activado por click en icono");
      
      redibujaMarker(mapaIn.activeMarker, 1);
      // if(typeof mapaIn.activeMarker.iconoActivo == "String")
      //   mapaIn.activeMarker.setIcon(new H.map.Icon(mapaIn.activeMarker.iconoActivo, {size: {w: iconW, h: iconH}}));
      // else
      //   mapaIn.activeMarker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW, h: iconH}}));

    }
    mapaIn.funcionClick();
  }
}

this.removeAll = function() {
  
  // console.log(groups);
  for(var i in groups){
    // console.log(groups[i]);
    map.removeObject(groups[i]['objeto']);
  }
  groups = {};
}

this.getCoords = function(grupo){
  if(typeof groups[grupo] !== "undefined")
    return groups[grupo];
  else
    throw new ReferenceError('El grupo solicitado no existe...');
}

this.getGroups = function() {
  return groups;
}

  this.agregaListeners = function() {

    $('#opcionesDir').on('click', '.opcionesLista', function() {

      a = $(this);

      var id=$(this).attr('id').split('_')[1];

      if(varDebug==1)
        console.log(id, direcciones[id]);

      var dirTmp = [direcciones[id]];

      self.mapa.setManyCoordsAsGroup("geoCodeResults", dirTmp);
      convierteGeoLocations(dirTmp, "geoCodeResults");

      self.mapa.dibuja();

      // addLocationsToMap(dirTmp);

      addLocationsToPanel(dirTmp);

      $('#opcionesDir').empty().hide();

    });


    $('#btnSearchAgainDir').click( function() {
      if(varDebug==1)
        console.log("Grupo", group, this);
      this.removeAll();
      groups = {};
      withPosition = false;
      geocode(platform);
    }.bind(this));


    map.addEventListener('tap', function(ev) {

      var coord = map.screenToGeo(ev.currentPointer.viewportX, ev.currentPointer.viewportY);

      objects = map.getObjects();

      var len = objects.length;
      var num = 0;

      var mark = null;

      for (i = 0; i < len; i += 1) {
        if(!objects[i]['label']){
          continue;
        }
        num++;
        mark = objects[i];
      }

      if(num>1)
        return;

      if (mark instanceof mapsjs.map.Marker) {
        behavior.enable();

        $.confirm({
          title: '¡Confirmar el cambio!',
          content: 'Esta acción requiere confirmación.',
          theme: 'supervan' ,
          buttons: {
            confirm: {
              text: "Confirmar",
              btnClass: 'btn-shop',
              action: function () {
              // $.alert('El cambio de ubicación ha sido aplicado!');
              position = coord;
              $('#lat').val(position.lat);
              $('#lng').val(position.lng);
              mark.setPosition(position);

              markerPos = position;
              $('#spanPosicion').text(Math.abs(position.lat.toFixed(4)) + ((position.lat > 0) ? 'N' : 'S') +
                ' ' + Math.abs(position.lng.toFixed(4)) + ((position.lng > 0) ? 'E' : 'O') );     


            }
          },
          cancelar: {
            btnClass: 'btn-red',
            text: "Cancelar",
            action: function(){

            }
            // $.alert('Cancelado!');
          }
        }

      });
      }

    }, false);

    map.addEventListener('dragstart', function(ev) {
      var target = ev.target;
      if (target instanceof H.map.Marker) {
        behavior.disable();
      }
    }, false);


    map.addEventListener('dragend', function(ev) {

      var target = ev.target;
      if (target instanceof mapsjs.map.Marker) {
        behavior.enable();

        $.confirm({
          title: '¡Confirmar el cambio!',
          content: 'Esta acción requiere confirmación.',
          theme: 'supervan' ,
          buttons: {
            confirm: {
              text: "Confirmar",
              btnClass: 'btn-shop',
              action: function () {
              // $.alert('El cambio de ubicación ha sido aplicado!');
              position = marker.getPosition();
              $('#lat').val(position.lat);
              $('#lng').val(position.lng);

              markerPos = position;
              $('#spanPosicion').text(Math.abs(position.lat.toFixed(4)) + ((position.lat > 0) ? 'N' : 'S') +
                ' ' + Math.abs(position.lng.toFixed(4)) + ((position.lng > 0) ? 'E' : 'O') );     

              // $('#popUp').modal('toggle');

            }
          },
          cancelar: {
            btnClass: 'btn-red',
            text: "Cancelar",
            action: function(){

              // console.log(marker, markerPos);
              marker.setPosition(markerPos);
              $('#lat').val(markerPos.lat);
              $('#lng').val(markerPos.lng);

            }
            // $.alert('Cancelado!');
          }
        }

      });

      }

    }, false);


    map.addEventListener('drag', function(ev) {
      var target = ev.target,
      pointer = ev.currentPointer;
      if (target instanceof mapsjs.map.Marker) {
        target.setPosition(map.screenToGeo(pointer.viewportX, pointer.viewportY));
      }
    }, false);

  
  }


  function geocode(platform) {

    // console.trace();

    var geocoder = platform.getGeocodingService(),
    // geocodingParameters = {
    //   // searchText: 'Zacatecas 123, roma norte',
    //   searchText: direccion,
    //   jsonattributes : 1
    // };
  


    geocodingParameters = {
    	country: 'MEX',
    	jsonattributes : 1
    };

    if(dirPost != null){


      /** 
      por alguna extraña razón, HERE maneja las cosas como sigue... (ejemplo en CDMX)
      
      city -> alcaldías
      county -> Ciudad de México
      state ->  CDMX

      lo demás sí es normal...
      */

      if(dirPost.direccion.calle !== "")
        geocodingParameters.street = dirPost.direccion.calle;
      if(dirPost.direccion.colonia !== "")
        geocodingParameters.district = dirPost.direccion.colonia;
      if(dirPost.direccion.cp !== "")
        geocodingParameters.postalcode = dirPost.direccion.cp;
      if(dirPost.direccion.municipio !== "")
        geocodingParameters.city = dirPost.direccion.municipio;      
      if(dirPost.direccion.estado !== ""){
        geocodingParameters.county = dirPost.direccion.estado;
      }else{
        geocodingParameters.county = "Ciudad de México";
        geocodingParameters.state = "CDMX";
      }
    
      if(dirPost.direccion.numExt !== "")
        geocodingParameters.houseNumber = dirPost.direccion.numExt;
    }

    if(varDebug==1)
      console.log("GEOCODE PARAMS>>>", geocodingParameters);

  geocoder.geocode(
    geocodingParameters,
    onSuccess,
    onError
  );
}


/**
 * This function will be called once the Geocoder REST API provides a response
 * @param  {Object} result          A JSONP object representing the  location(s) found.
 *
 * see: http://developer.here.com/rest-apis/documentation/geocoder/topics/resource-type-response-geocode.html
 */
 
  function onSuccess(result) { 

    if(varDebug==1)
     console.log(result);

   if(result.response.view.length==0){

    if(segundaVuelta){
      segundaVuelta=false;
      return;
    }

    segundaVuelta=true;
    if(varDebug==1)
      console.log("sin resultados... a segunda vuelta", dirPost);
    var geocoder = platform.getGeocodingService(),
    geocodingParameters = {
      country: 'MEX',
      city : "Ciudad de México",


      jsonattributes : 1
    };


    geocoder.geocode(
      geocodingParameters,
      onSuccess,
      onError
      );
    return;
  }

   direcciones = result.response.view[0].result;
   
   withPosition = true;



   if(direcciones.length > 1){
		
		// console.log("multiples respuestas...");
		var ul = $('<ul>');

		for(direccion in direcciones){
			// console.log(direcciones[direccion]);

			ul.append($('<li>').append(
				$('<span>').attr('class', 'opcionesLista').css('cursor','pointer').attr('id', 'opcionesLista_'+direccion).append(direcciones[direccion].location.address.label)));
		}

		$('#opcionesDir').empty().show();
    $('#opcionesDir').append("<br> Se obtuvieron múltiples resultados para la búsqueda, favor de elegir entre las siguientes opciones: <br><br>");
		$('#opcionesDir').append(ul);

    $(panel).empty();
    $('#btnSearchAgainDir').hide();

		return;
	}

  if(varDebug==1)
    console.log("direcciones", direcciones);



  self.mapa.setManyCoordsAsGroup("geoCodeResults", direcciones);
  // addLocationsToMap("geoCodeResults", direcciones);
  
  convierteGeoLocations(direcciones, "geoCodeResults");
  addLocationsToPanel(direcciones);

  if(varDebug==1)
    console.log("y llamamos dibuja a continuacion");

  self.mapa.dibuja();
  // ... etc.
  }

/**
 * This function will be called if a communication error occurs during the JSON-P request
 * @param  {Object} error  The error message received.
 */
 function onError(error) { 
  alert('Ooops!');
}


/**
 * Opens/Closes a infobubble
 * @param  {H.geo.Point} position     The location on the map.
 * @param  {String} text              The contents of the infobubble.
 */
  function openBubble(position, text) {

    html = "<span style='font-size:0.6em'>"+text+"</span>";
    if(bubbleButton){
      html += "<br/><span id='btnBubble' class='btn btn-xs btn-shop'>"+bubbleButtonText+"</span>";
    }
    if(!bubble){
      bubble =  new H.ui.InfoBubble( position );
      bubble.setContent(html);
      ui.addBubble(bubble);
    } else {
      bubble.setPosition(position);
      // console.log("con boton", bubbleButton);
    }
    bubble.setContent(html);
    bubble.open();
  }


/**
 * Creates a series of list items for each location found, and adds it to the panel.
 * @param {Object[]} locations An array of locations as received from the
 *                             H.service.GeocodingService
 */
 function addLocationsToPanel(locations) { 

  if(!conPanel)
    return;

  if(varDebug==1)
    console.log("addingLoc-s", locations);

  var nodeOL = document.createElement('ul'),
    i;

  nodeOL.style.fontSize = 'small';
  nodeOL.style.marginLeft ='5%';
  nodeOL.style.marginRight ='5%';


   for (i = 0;  i < locations.length; i += 1) {
     var li = document.createElement('li'),
        divLabel = document.createElement('div'),
        address = locations[i].location.address,
        content =  '<strong style="font-size: large;">' + address.label  + '</strong></br>';
        position = {
          lat: locations[i].location.displayPosition.latitude,
          lng: locations[i].location.displayPosition.longitude
        };

        $('#lat').val(position.lat);
        $('#lng').val(position.lng);


      content += '<strong>Calle:</strong> '  + address.street + '<br/>';
      content += '<strong>Número:</strong> ' + address.houseNumber + '<br/>';
      content += '<strong>Colonia:</strong> '  + address.district + '<br/>';
      content += '<strong>Alcaldía:</strong> ' + address.city + '<br/>';
      content += '<strong>Código postal:</strong> ' + address.postalCode + '<br/>';
      content += '<strong>Ciudad:</strong> ' + address.county + '<br/>';
      content += '<strong>País:</strong> ' + address.country + '<br/>';

      content += '<br/><strong>Posición:</strong> <span id="spanPosicion">' +
        Math.abs(position.lat.toFixed(4)) + ((position.lat > 0) ? 'N' : 'S') +
        ' ' + Math.abs(position.lng.toFixed(4)) + ((position.lng > 0) ? 'E' : 'O') +
        '</span>';

      divLabel.innerHTML = content;
      li.appendChild(divLabel);

      nodeOL.appendChild(li);
      if(varDebug==1)
        console.log("added result # "+i);
      // debugger;
  }

  $(panel).empty();

  panel.appendChild(nodeOL);
}



  function addPostLocationToPanel() {
    
    if(!conPanel || dirPost == null)
      return;

  $(panel).empty();

  if(varDebug==1)
    console.log("adding POSTed Location", dirPost.direccion);

  var nodeOL = document.createElement('ul'),
  i;

  nodeOL.style.fontSize = 'small';
  nodeOL.style.marginLeft ='5%';
  nodeOL.style.marginRight ='5%';

  var li = document.createElement('li'),

  divLabel = document.createElement('div'),

  address =  dirPost.direccion;

  dirLabel = "";
  content = "";
  if(address.calle != "") { dirLabel += address.calle+" "; content +=  '<strong>Calle:</strong> '  + address.calle + '<br/>'; }
  if(address.numExt != "") { dirLabel += address.numExt+", "; content +=  '<strong>Número:</strong> '  + address.numExt + '<br/>'; }
  if(address.colonia != "") { dirLabel += address.colonia+", "; content +=  '<strong>Colonia:</strong> '  + address.colonia + '<br/>'; }
  if(address.cp != "") { dirLabel += address.cp+" "; content +=  '<strong>Código Postal:</strong> '  + address.cp + '<br/>'; }
  if(address.municipio != "") { dirLabel += address.municipio+", "; content +=  '<strong>Alcaldía:</strong> '  + address.municipio + '<br/>'; }
  if(address.estado != "") { dirLabel += address.estado+", México"; content +=  '<strong>Ciudad:</strong> '  + address.estado + '<br/>'; }

  content2 =  '<strong style="font-size: large;">' + dirLabel  + '</strong></br>'+content;

  content2 += '<br/><strong>Posición:</strong> <span id="spanPosicion">' +
  Math.abs( parseFloat($('#lat').val())) + (($('#lat').val() > 0) ? 'N' : 'S') +
  ' ,  ' + Math.abs( parseFloat($('#lng').val() )) + (($('#lng').val() > 0) ? 'E' : 'O') +
  '</span>';

  divLabel.innerHTML = content2;
  li.appendChild(divLabel);

  nodeOL.appendChild(li);

  panel.appendChild(nodeOL);
}


 function convierteGeoLocations(locations, groupId, labels=null){


  if(!locations || locations.length == 0)
    return;

  var i;

  for (i = 0;  i < locations.length; i += 1) {
    locations[i]['lat'] = locations[i].location.displayPosition.latitude;
    locations[i]['lng'] =  locations[i].location.displayPosition.longitude;
    if(labels)
      locations[i]['label'] = locations[i].labels;
    else
      locations[i]['label'] = "geoLoc";

    locations[i]['val'] =  "geoLoc";
  }

}

  this.dibuja = function(){
    this.dibujaGrupo(null);
  }


  this.dibujaGrupo = function(groupId) {

    if(varDebug==1){
      if(groupId == null)
        console.log("Entrando a dibujar TODO", groups);
      else
        console.log("Entrando a dibujar el grupo "+groupId);
    }

    // debugger;
    if(!withPosition){
      return;
      geocode(platform);
    }
    else
    {
      // position;
      if(varDebug==1)
        console.log("ya tenemos posicion, no hacemos geocoding...");

      var i;

      if(varDebug==1)
        console.log("groups", groups, groupId);

      jump = false;

      if(groupId != null && groups[groupId])
        jump = true;

      for (var i in groups) {        


        if(jump && i!=groupId)
          continue;

        group = new  H.map.Group();

        // console.log("agregando grupo: ", i);
        for(var j=0; j<groups[i]['locations'].length; j++){

          if(varDebug==1)
            console.log("agregando ubicacion: ", groups[i]['locations'][j].label);

          if(groups[i]['locations'][j].lat == null || groups[i]['locations'][j].lng == null)
            continue;

          position = { lat: groups[i]['locations'][j].lat, lng: groups[i]['locations'][j].lng};
          
          marker = new H.map.Marker(position);
          // if(typeof groups[i].icono == "String")
          //   marker = new H.map.Marker(position, {icon: new H.map.Icon(groups[i].icono, {size: {w: iconW, h: iconH}})});
          // else
          //   marker = new H.map.Marker(position, {icon: new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}})});
            
          marker.draggable = true;
          
          marker.label = groups[i]['locations'][j].label;
          marker.valor = groups[i]['locations'][j].valor;
          marker.visita = groups[i]['locations'][j].visitaId;
          marker.listo = groups[i]['locations'][j].avComp == 1 ? "1" : "0";
          marker.icono = groups[i]['icono'];
          marker.iconoActivo = groups[i]['iconoActivo'];
          redibujaMarker(marker, 0);

          group.addObject(marker);

        }

        if(group.getObjects().length == 0)
          continue;

        markerPos = position;

        if(this.bubbles){
          group.addEventListener('tap', function (evt) {
            // map.setCenter(evt.target.getPosition()); // para centrar en el click

            prevActiveMarker = this.activeMarker;

            if(changeActiveMarker && !bubbleButton && this.activeMarker != null){

              redibujaMarker(prevActiveMarker,0);
              // if(typeof prevActiveMarker.icono == "String")
              //   prevActiveMarker.setIcon(new H.map.Icon(prevActiveMarker.icono, {size: {w: iconW, h: iconH}}));
              // else
              //   prevActiveMarker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
              // this.activeMarker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
            }
            
            this.activeMarker = evt.target;

            // console.log("evt", evt, changeActiveMarker, bubbleButton)

            if(changeActiveMarker && !bubbleButton){
              redibujaMarker(this.activeMarker, 1);
              // if(typeof this.activeMarker.icono == "String")
              //   this.activeMarker.setIcon(new H.map.Icon(this.activeMarker.iconoActivo, {size: {w: iconW, h: iconH}}));
              // else
              //   this.activeMarker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW, h: iconH}}));
            }

            openBubble(evt.target.getPosition(), evt.target.label);
            clickFunc(this);
          }.bind(this), false);
        }
        else{
          // sin bubbles
          group.addEventListener('tap', function (evt) {
            // map.setCenter(evt.target.getPosition()); // para centrar en el click

            prevActiveMarker = this.activeMarker;

            if(changeActiveMarker && !bubbleButton && this.activeMarker != null){

              redibujaMarker(prevActiveMarker,0);
              // if(typeof prevActiveMarker.icono == "String")
              //   prevActiveMarker.setIcon(new H.map.Icon(prevActiveMarker.icono, {size: {w: iconW, h: iconH}}));
              // else
              //   prevActiveMarker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
              // this.activeMarker.setIcon(new H.map.Icon(marker_default, {size: {w: iconW, h: iconH}}));
            }
            
            this.activeMarker = evt.target;

            // console.log("evt", evt, changeActiveMarker, bubbleButton)

            if(changeActiveMarker && !bubbleButton){
              redibujaMarker(this.activeMarker, 1);
              // if(typeof this.activeMarker.icono == "String")
              //   this.activeMarker.setIcon(new H.map.Icon(this.activeMarker.iconoActivo, {size: {w: iconW, h: iconH}}));
              // else
              //   this.activeMarker.setIcon(new H.map.Icon(marker_active, {size: {w: iconW, h: iconH}}));
            }
            clickFunc(this);
          }.bind(this), false);
        }

        // si ya existía otro, se va
        if(groups[i]['objeto'])
          map.removeObject(groups[i]['objeto']);

        groups[i]['objeto'] = group;

        map.addObject(group);

        // console.log("bounds", bounds, typeof bounds, group.getBounds());


        ajustaBounds(group);
        ajustaZoom();

      
      }

      addPostLocationToPanel();


    }
  }



  function ajustaBounds(grupo){

    if(varDebug==1)
      console.log("Grupo ajustaBounds:", grupo);
    
    if(grupo != null){

      groupBounds = grupo.getBounds();

      if(varDebug==1)
        console.log(bounds, groupBounds, grupo);

      if(bounds !=null)
        bounds = bounds.mergeRect(grupo.getBounds());
      else
        bounds = grupo.getBounds();

      map.setViewBounds(bounds);
      map.setCenter(bounds.getCenter());
    }
    if(varDebug==1)
     console.log("saliendo de ajusta bounds", bounds);
  }



  function ajustaZoom(){
    setTimeout(function() {
     if(varDebug==1)
       console.log("ajustaZoom desde zoom : ",map.getZoom());
      if(map.getZoom() > 18)
        map.setZoom(18, true);
      if(map.getZoom() < 8)
        map.setZoom(8, true);      
    }, 300);
  }




this.geoCodifica =function(){
  if(varDebug==1)
    console.log("DIRPOST 1 >" , this.dirPost, dirPost);

  if(this.dirPost != null){
    if(this.dirPost.coord.lat != "" && this.dirPost.coord.lng != ""){
      position = {
        lat: this.dirPost.coord.lat,
        lng: this.dirPost.coord.lng
      };
      this.addSingleCoord(position.lat, position.lng, "posted");

      withPosition = true;

    }
    else {

      position = { lat: 0, lng:0  };

      geocode(platform);

      if(varDebug==1)
        console.log("posted empty position with address");
    }
  } else {
    position = { lat: 0, lng:0 };
    if(varDebug==1)
      console.log("posted empty post");
  }
  
  if(varDebug==1)
    console.log("dirpost 2:", this.dirPost, withPosition);
}

  // $('#opcionesDir').on('click', '.opcionesLista', function() {

  //   a = $(this);

  //   var id=$(this).attr('id').split('_')[1];

  //   console.log(id, direcciones[id]);

  //   var dirTmp = [direcciones[id]];

  //   addLocationsToMap(dirTmp);
  //   addLocationsToPanel(dirTmp);

  //   $('#opcionesDir').empty().hide();

  // });

  // $('#btnSearchAgainDir').click( function() {
  //   if(group)
  //     map.removeObject(group);

  //   geocode(platform);
  // });

 

  $('head').append('<link rel="stylesheet" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css" type="text/css" />');


}

