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

function jsonF(archivo,datos){
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
	})
	.fail(function() {
		console.log("error");
	});
	
	return r;
}

function camposObligatorios(forma){
	var todos = true;


	$.each( $(forma+' .oblig'), function(index, val) {
		if($(this).val() == '' && $(this).is(':visible')){
			$(this).css({backgroundColor:'rgba(255,0,0,.5)'});
			todos = false;
		}
	});

	return todos;
}

function alertar(html,fnc,params){
	var c = null;
	popUpAlerta('lib/j/php/alerta.php',{html:html},function(e){
		$('#alertas #envOkModal').click(function(event) {
			fnc(e);
			$('#alertas #cPop').trigger('click');
		});
	},params);
}

function popUpAlerta(ruta,params,fn,fnP){
	$('#alertasCont').load(rz+ruta,params, function(){
		if(fn){
			fn(fnP);
		}
	});
	$('#alertas').modal('show');
}

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

