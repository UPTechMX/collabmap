var rz = aRaiz();

$.fn.serializeObject = function(){
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
	if (o[this.name] !== undefined) {
		if (!o[this.name].push) {
		o[this.name] = [o[this.name]];
		}
		o[this.name].push(this.value || '');
	} else {
		o[this.name] = this.value || '';
	}
	});
	return o;
};


/**
 * Provides more features for the widget module...
 *
 * @module Generales
 * @main Generales
 */

/**
* Funciones generales que serán utilizadas en los módulos específicos
*
* @class Funciones generales
*/

/**
* Este método arroja busca el archivo raiz ubicado en el directorio raiz del proyecto y arroja la
* ruta de acceso al directorio raiz
*
* @method aRaiz
* @return {String} Devuelve la ruta del directorio Raiz a partir del directorio de llamada.
*/
function aRaiz(){

	var path = document.location.pathname;
	var dirs = path.split('/');
	var dRaiz = 'NO';

	$.ajax({
		url:'./raiz',
		async:false,
		success: function(){
			dRaiz = './';
		}
	});
	busq:{
		for(var i = 0;i<dirs.length;i++){
			if(dRaiz != 'NO'){
				break busq;
			}
			var dir = '';
			for(var j = 0; j<i;j++){
				dir += '../';
			}
			$.ajax({
				url:dir+'raiz',
				type:'HEAD',
				async:false,
				success: function(){
					dRaiz = dir;
				}
			});
		}

	}

	if(dRaiz == 'NO'){
		return 'Raiz no encontrado';
	}else{
		return dRaiz;       
	}
}

function popUp(ruta,params,fn = function(){},fnP={}){
	$('#popCont').load(rz+ruta,params, function(){
		if(fn){
			fn(fnP);
		}
	});
	
	$('#popUp').modal('show');
}

function popUpMapa(ruta,params,fn = function(){},fnP={}){
	$('#popContMapa').load(rz+ruta,params, function(){
		if(fn){
			fn(fnP);
		}
	});
	
	$('#popUpMapa').modal('show');
}


function popUpImg(ruta,params,fn = function(){},fnP={}){
	$('#popContImg').load(rz+ruta,params, function(){
		if(fn){
			fn(fnP);
		}
	});
	
	$('#popUpImg').modal('show');
}

function popUpCuest(ruta,params,fn = function(){},fnP={}){
	$('#popContCuest').load(rz+ruta,params, function(){
		if(fn){
			fn(fnP);
		}
	});
	
	$('#popUpCuest').modal('show');
}

function verImagen(src){
	popUpImg('lib/j/php/verImg.php',{src:src},function(){},{});
}

function popUpAlerta(ruta,params,fn = function(){},fnP={}){
	$('#alertasCont').load(rz+ruta,params, function(){
		if(fn){
			fn(fnP);
		}
	});
	$('#alertas').modal('show');
}

function convertUTCDateToLocalDate(date) {
    // var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);
    var newDate = new Date(date.getTime());

    var offset = date.getTimezoneOffset() / 60;
    var hours = date.getHours();

    newDate.setHours(hours - offset);
    // newDate.setHours(hours);
    // console.log(date.getTimezoneOffset())
    // console.log(newDate);
    return newDate;   
}

function ptsHoraLocal(pts){

	for(var i in pts){
		var ff = pts[i].fecha;
		var hh = pts[i].hora;
		var date = new Date(pts[i]['Time UTC']);
		// var date =  new Date(ff.split('-')[0], ff.split('-')[1], ff.split('-')[2], hh.split(':')[0], hh.split(':')[1], hh.split(':')[2], 00);
		var d = convertUTCDateToLocalDate(date);

		pts[i].fecha = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
		pts[i].hora = d.getHours()+':'+("0" + d.getMinutes()).slice(-2);
		

		// console.log( nDate.getFullYear() );

	}
}

function camposObligatorios(forma){
	var todos = true;

	$.each( $(forma+' .oblig'), function(index, val) {
		var tipo = $(this)[0].tagName;
		if(tipo == 'DIV'){			
			if($(this).text() == '' && $(this).is(':visible')){
				$(this).css({backgroundColor:'rgba(255,0,0,.5)'});
				todos = false;
			}
		}else{			
			// console.log($(this).val())
			if(($(this).val() == '' || $(this).val()== null ) && $(this).is(':visible')){
				$(this).css({backgroundColor:'rgba(255,0,0,.5)'});
				todos = false;
			}
		}
	});

	return todos;
}

function jsonF(archivo,datos){
	with ((console && console._commandLineAPI) || {}) {
	  r = '';
	}
	// console.log(rz+archivo);
	var r = '';
	$.ajax({
		url: rz+archivo,
		type: 'POST',
		data: datos,
		async:false
	})
	.done(function(data) {
		r = data
	})
	.fail(function() {
		console.log("error");
	});
	
	return r;
}

function jsonFA(archivo,datos,fnc){
	with ((console && console._commandLineAPI) || {}) {
	  r = '';
	}
	var r = '';
	$.ajax({
		url: rz+archivo,
		type: 'POST',
		data: datos,
		async:true
	})
	.done(function(data) {
		r = data;
		fnc(data,datos);
	})
	.fail(function() {
		console.log("error");
	});
	
	return r;
}

function jsonFF(archivo,datos,fnc,params){
	with ((console && console._commandLineAPI) || {}) {
	  r = '';
	}
	var r = '';
	$.ajax({
		url: rz+archivo,
		type: 'POST',
		data: datos,
		async:false
	})
	.done(function(data) {
		r = data
		fnc(params);
	})
	.fail(function() {
		console.log("error");
	});
	
	return r;
}

function optsSel(arr,elemSel,sinVacio,nomVacio,add){
	add = typeof add == 'undefined'?false:add;
	if(!add){
		elemSel.empty();
		if(!sinVacio){	
			if(nomVacio == "" || nomVacio == undefined){
				var o = new Option('- - - - - - - -','');
			}else{
				var o = new Option(nomVacio,'');
			}
			elemSel.append(o);
		}
	}
	
	for(var e in arr){
		if(arr[e].clase != undefined){
			var o = '<option value="'+arr[e].val+'" class="'+arr[e].clase+'">'+arr[e].nom+'</option>'
		}else{
			var o = new Option(arr[e].nom,arr[e].val);
		}
			elemSel.append(o);
	}
}

function alerta(tipo,texto){
	if(tipo == 'danger'){
		$('<div>')
		.attr({'class':'alert alert-'+tipo, role:'alert'})
		.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>'+
			'</button><strong>'+texto+'</strong>')
		.appendTo('#dAlerta')
		.fadeTo(0, 0)
		.fadeTo(500, 1);
	}else{

		$('<div>')
		.attr({'class':'alert alert-'+tipo, role:'alert'})
		.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>'+
			'</button><strong>'+texto+'</strong>')
		.appendTo('#dAlerta')
		.fadeTo(0, 0)
		.fadeTo(500, 1)
		.fadeTo(5000, 1,function(){$(this).remove()});
	}
}

function alertar(html,fnc=function(){},params={}){
	// console.log('vvv')
	var c = null;
	popUpAlerta('lib/j/php/alerta.php',{html:html},function(e){
		$('#alertas #envOkModal').click(function(event) {
			fnc(e);
			$('#alertas #cPop').trigger('click');
		});
	},params);
}

function conf(html,params,fnc){
	var c = null;
	popUpAlerta('lib/j/php/confirmacion.php',{html:html},function(e){
		$('#alertas #envOkModal').click(function(event) {
			fnc(params);
			$('#alertas #cPop').trigger('click');
		});
	},params) ;
}

/**
* Sube un archivo a un directorio específico y le cambia el nombre a prefijo_nombreDelArchivo.
*
* @method subArch
* @param {String} elementoId El id del botón de upload.
* @param {String} prefijo El prefijo que tendrá el archivo al guardarlo
* @param {String} dir La ruta del directorio donde se guardará el archivo a partir del directorio raiz
* @param {String} funcion El método que se ejecutará al terminar la carga del archivo.

* @return {Void}
*/
function subArch(
		elemento,
		rutaId,
		prefijo,
		extensiones,
		dragDrop,
		funcion,
		allowMultiple=true,
		uploadStr = 'Select',
		extErrorStr = "cannot be loaded. Only the following extensions are accepted "
	){
	// console.log("pppppp", evitarNombreOriginal);
	var r = '';
	elemento.uploadFile({
		url:rz+"lib/php/subeArchivos.php",
		fileName:"myfile",
		allowedTypes:extensiones,
		dragDrop:dragDrop,
		dataType:"json",
		multiple: allowMultiple,
		formData:{"prefijo":prefijo,rutaId:rutaId},
		showStatusAfterSuccess: false,
		uploadStr:uploadStr,
		extErrorStr:extErrorStr,
		onSuccess:
		function(files,data,data2){
			// console.log(data);
			var infoArchivo = $.parseJSON(data);
			// console.log(infoArchivo);
			if(infoArchivo.ok == 1){
				if(funcion){
					funcion(infoArchivo);
				}
			}else{
				console.log(data);
			}
			
		}
	});
}




/**
* Sólo permite escribir números en un campo de texto 
*
* @method soloNumeros
* @param {Object} idInputTexto El elemento en jQuery del objeto al que se aplicará el método.
* @return {Void}
*/
function soloNumeros(elem){
	// console.log(elem)
	elem.keydown(function(event){
		// console.log(event.keyCode)
		
		// Acepta: backspace, delete, tab, escape, and enter
		if(event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 190 ||
			// Acepta: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			// Acepta: Ctrl+R
			(event.keyCode == 82 && event.ctrlKey === true) || 
			// Acepta: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
				// no hacer nada
				// console.log(event.keyCode)
				return;
		}else{
			// Ensure that it is a number and stop the keypress
			if(event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				// console.log(event.keyCode)
				
				event.preventDefault();
			}
		}

	});

	elem.mousedown(function(e) {
		if( e.button == 2 ) { 
		  return false; 
		} 
		return true; 
	});

	elem.keyup(function(event) {
		var point = false;
		var str = '';
		for(var k = 0; k<elem.val().length; k++){
			if(!isNaN(elem.val()[k])){
				// console.log('entra:',elem.val()[k]);
				str += elem.val()[k];
			}
			if(elem.val()[k] == '.' && !point){
				str += elem.val()[k];	
				point = true;
			}
		}
		// console.log('str:',str);
		elem.val(str);
	});

	
}
/**
* Sólo permite escribir números en un campo de texto 
*
* @method soloNumeros
* @param {Object} idInputTexto El elemento en jQuery del objeto al que se aplicará el método.
* @return {Void}
*/
function desactivaEsc(elem){
	// console.log(elem)
	elem.keydown(function(event){
		// console.log(event.keyCode)
		
		// Acepta: backspace, delete, tab, escape, and enter
		if(event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			// console.log(event.keyCode)
			
			event.preventDefault();
		}

	});
	elem.mousedown(function(e) {
		if( e.button == 2 ) { 
		  return false; 
		} 
		return true; 
	});
}

function loading(){
	disableScroll();
	$('<div>')
	.attr({
		id: 'over'
	})
	.css({
		width: '100%',
		height:'100%',
		backgroundColor: 'rgba(0,0,0,.6)',
		position:'absolute',
		top:'0px',
		left:'0px',
		zIndex: 1000
	}).appendTo(document.body);

	$('<div>')
	.attr({
		id: 'loader',
		class: 'loader'
	}).appendTo('#over')
	.css({
		position: 'absolute',
		top: '45%',
		left:'45%'
	});
}

function removeLoading(){
	$('#over').remove();
	enableScroll();
}

function disableScroll() {
  if (window.addEventListener) // older FF
      window.addEventListener('DOMMouseScroll', preventDefault, false);
  window.onwheel = preventDefault; // modern standard
  window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
  window.ontouchmove  = preventDefault; // mobile
  document.onkeydown  = preventDefaultForScrollKeys;
}

function enableScroll() {
    if (window.removeEventListener)
        window.removeEventListener('DOMMouseScroll', preventDefault, false);
    window.onmousewheel = document.onmousewheel = null; 
    window.onwheel = null; 
    window.ontouchmove = null;  
    document.onkeydown = null;  
}

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
        preventDefault(e);
        return false;
    }
}

function parseaObjeto(obj){

	for(var i in obj){
		if( typeof(obj[i]) == 'object'){
			if(i != 'cats'){
				parseaObjeto(obj[i]);
			}
		}else{
			// console.log(obj[i]);
			// console.log(obj[i],parseFloat(obj[i]));

			if(isNaN(filterFloat(obj[i]))  ) {
				obj[i] = obj[i];
				// console.log("NaN",obj[i]);
				// console.log('cadena');
			}else{
				obj[i] = parseFloat(obj[i]);
				// console.log("no NaN",obj[i]);
				// console.log('numero');
			}
			// console.log(parseFloat(obj[i]));
			// obj[i] = isNaN(parseFloat(obj[i]))?obj[i]:parseFloat(obj[i]);
		}
	}
}

function genGrid(){
	var gridster = $('.grid-stack').gridstack({
		handle: '.widgetBar',
		// float: true
	}).data('gridstack');
	return gridster;
}

function isInt(x) {
    return x % 1 === 0;
}

function ajustaWidget(wId){
	// console.log(wId);
	var liH = $('#wid_'+wId).height();
	var bH = $('#wid_'+wId).find('.widgetBar').height();
	// console.log(liH,bH,liH-bH-10);
	$('#wid_'+wId).find('.grafica').height(liH-bH-10);
	// console.log($('#wid'+wId).find('.grafica').highcharts());
	if( typeof $('#wid_'+wId).find('.grafica').highcharts() != "undefined" ){
		$('#wid_'+wId).find('.grafica').highcharts().reflow();
	}
}

var filterFloat = function(value) {
    if (/^(\-|\+)?([0-9]+(\.[0-9]+)?|Infinity)$/
      .test(value))
      return Number(value);
  return NaN;
}

function strip(html){
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function muestraResultados(dat){
	if(dat.length == 0)
		dat.cuenta = "contar";
	switch( $('.'+dat.cuenta).length){
		case 0:
		$('#contador').html("No se encontraron resultados para la búsqueda.");		
		break;
		case 1:
		$('#contador').html("Se encontró <span class='cuenta'>1</span> resultado para la búsqueda.");		
		break;
		default:
		$('#'+dat.id).html("Se encontraron <span class='cuenta'>"+$('.'+dat.cuenta).length+"</span> resultados.");		
	}
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function addSA(layer,rutaGet,acc,pregId,vId = 0,style = {}){
	var getSA = jsonF(rutaGet,{acc:acc,pId:pregId,vId:vId});
	// console.log(getSA);
	var SAs = $.parseJSON(getSA);

	var allPoints = [];
	for(var saId in SAs){

		var points = [];
		var sa = SAs[saId];
		if(sa.geometry == null){
			continue;
		}
		var geometry = $.parseJSON(sa.geometry);
		var coordinates = geometry.coordinates;
		// console.log(typeof coordinates);
		for(var i in coordinates){
			var p = coordinates[i];
			for(var j in p){
				// console.log('j:',j,p[j],typeof p[j]);
				allPoints.push(L.marker( [ p[j][1], p[j][0] ] ));
				points.push( [ p[j][1], p[j][0] ] );
			}
			// console.log(sa[i]);
			// if(sa[i]['lat'] == null || sa[i]['lng'] == null){
			// 	continue;
			// }
		// 	// points.push([sa[i]['lat'],sa[id]['lng']]);
		}

		// console.log('aa',points.length, points);
		if(points.length > 0){		
			var polygon = L.polygon(points);
			polygon.setStyle(style);

			polygon.dbId = sa['id'];
			polygon.type = geometry['type'].toLowerCase();

			layer.addLayer(polygon);
		}
	}
	return allPoints;
}

