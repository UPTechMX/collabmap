<?php

include_once '../lib/j/j.func.php';
include_once '../lib/php/api.php';
include_once '../lib/php/checklist.php';
include_once '../lib/php/calcCuest.php';
include_once '../lib/php/creaTokens.php';


// print2($_POST);
$cadena = "NL_$_POST[usrId]_$_POST[nivel]";
if(!password_verify($cadena,$_POST['hash'])){
	exit('[]');
}

$elemento = json_decode($_POST['elemento'],true);

switch ($_POST['tipo']) {
	case 'cliente':

		echo guardaCliente($elemento);
		break;
	case 'visita':
		// print2($elemento['historial']);
		echo guardaVisita($elemento,null);
		break;
	
	default:
		# code...
		break;
}



function guardaCliente($cliente){

	global $db;

	if($cliente['cliente']['creadoOffline'] == 1){

		$cte = $cliente['cliente'];
		// $_POST['paramsBusq'] = {};
		$_POST['paramsBusq']['nombre'] = '%'.$cte['nombre'].'%';
		$_POST['paramsBusq']['aPat'] = '%'.$cte['aPat'].'%';
		$_POST['paramsBusq']['aMat'] = '%'.$cte['aMat'].'%';

		// var $_POST['paramsBusqDir'] = {};
		$_POST['paramsBusqDir']['calle'] = '%'.$cte['calle'].'%';
		$_POST['paramsBusqDir']['numeroExt'] = $cte['numeroExt'];
		$_POST['paramsBusqDir']['estadosId'] = $cte['estadosId'];
		$_POST['paramsBusqDir']['municipiosId'] = $cte['municipiosId'];
		$_POST['paramsBusqDir']['colonia'] = '%'.$cte['colonia'].'%';

		// print2($cliente['cliente']);
		// print2($cte);
		// echo "BBBBB";
		ob_start();
		include raiz().'general/clientes/json/buscaCteRegis.php';
		$buscClientesJ = ob_get_clean();
		ob_end_clean();
		// echo "AAAAAA";

		$buscClientes = json_decode($buscClientesJ,true);
		if(count($buscClientes['clientes'])>0){
			$cte = $buscClientes['clientes'][0];
			$r['ok'] = 2;
			$r['nId'] = $cte['id'];
			$r['cte'] = $cte;
			// echo atj($r);
			// echo "ENTRA ACÁ!!";
			return atj($r);
		}else{
			// echo "ENTRA ACÁ 222!!";

			// print2($cliente['cliente']);
			unset($cliente['cliente']['creadoOffline']);
			unset($cliente['cliente']['offline']);
			unset($cliente['cliente']['id']);
			$cliente['cliente']['token'] = getTokenForTableField('Clientes', 'token', 6);

			$p = array();
			$p['tabla'] = 'Clientes';
			$p['datos'] = $cliente['cliente'];

			// print2($p);

			$rj = inserta($p);
			$r = json_decode($rj,true);
		}

		// echo "AASASASASASS";

		$cId = $r['nId'];

		// print2($r);


		if($r['ok'] == 1){
			// echo "AAA";
			// print2($cliente['visitas']);
			foreach ($cliente['visitas'] as $v) {
				// print2($v);
				if(empty($v['respuestas'])) {
					continue;
				}
				// print2($v);
				// echo "CID!!!";
				// print2($cId);

				$rvj = guardaVisita($v,$cId);
				$rv = json_decode($rvj,true);

				// echo 'AAAA';

				if($rv['ok'] != 1){
					return $rvj;
				}
			}
			return '{"ok":1}';
		}else{
			// return $rj;
			return '{"ok":"0","err":"Insertar cliente Err:3435","errServer":'.$rj.'}';
		}



	}else{
		unset($cliente['cliente']['creadoOffline']);
		unset($cliente['cliente']['offline']);
		$p['tabla'] = 'Clientes';
		$p['datos'] = $cliente['cliente'];

		$rj = upd($p);
		$r = json_decode($rj,true);
		$cId = null;
	}

	if($r['ok'] == 1){
		foreach ($cliente['visitas'] as $v) {
			$rvj = guardaVisita($v,$cId);
			$rv = json_decode($rvj,true);

			if($rv['ok'] != 1){
				return $rvj;
			}
		}
		return '{"ok":1}';
	}else{
		// return $rj;
		return '{"ok":"0","err":"Insertar cliente Err:3436"}';
	}

}

function guardaVisita($visita,$cId = null){

	$visita['visita']['clientesId'] = $cId == null ? $visita['visita']['clientesId'] : $cId;
	if($visita['visita']['creadoOffline'] == 1){
		unset($visita['visita']['creadoOffline']);
		unset($visita['visita']['offline']);
		unset($visita['visita']['id']);
		$p['tabla'] = 'Visitas';
		$p['datos'] = $visita['visita'];
		$rj = inserta($p);
		$r = json_decode($rj,true);
		$vId = $r['nId'];

	}else{
		unset($visita['visita']['creadoOffline']);
		unset($visita['visita']['offline']);
		$p['tabla'] = 'Visitas';
		$p['datos'] = $visita['visita'];
		$rj = upd($p);
		$r = json_decode($rj,true);
		$vId = null;
	}

	if($r['ok'] == 1){
		$visita['respuestas'] = is_array($visita['respuestas'])?$visita['respuestas']:array();
		foreach ($visita['respuestas'] as $resp) {
			$rrj = guardaResp($resp,$vId);
			$rr = json_decode($rrj,true);
			if($rr['ok'] != 1){
				return $rrj;
				// return '{"ok":"0","err":"Insertando respuesta"}';
			}
		}
		// print2($visita['historial']);
		$visita['historial'] = is_array($visita['historial'])?$visita['historial']:array();
		foreach ($visita['historial'] as $hist) {
			// print2($hist);
			$rhj = guardaHist($hist,$vId,$cId);
			$rh = json_decode($rhj,true);
			if($rh['ok'] != 1){
				return $rhj;
				// return '{"ok":"0","err":"Insertando historial"}';
			}
		}
		$visita['multimedia'] = is_array($visita['multimedia'])?$visita['multimedia']:array();
		foreach ($visita['multimedia'] as $mult) {
			$rmj = guardaImagen($mult,$vId);
			$rm = json_decode($rmj,true);
			if($rm['ok'] != 1){
				return '{"ok":"0","err":"Insertando multimedia"}';
			}
		}

		$vId = $vId == null ? $visita['visita']['id'] : $vId;
		// echo "VID $vId\n";
		$chk = new Checklist($vId);
		$rcvj = atj($chk -> insertaCacheVisita($vId));
		$rcv = json_decode($rcvj,true);
		if($rcv['ok'] == 1){
			return '{"ok":1}';	
		}else{
			return $rcvj;
		}
		
	}else{
		return '{"ok":"0","err":"Insertar visita"}';
	}
}

function guardaHist($hist,$vId = null, $cId = null){

	unset($hist['offline']);
	unset($hist['id']);
	$hist['visitasId'] = $vId != null ? $vId : $hist['visitasId'];
	$hist['clientesId'] = $cId != null ? $cId : $hist['clientesId'];

	$p['tabla'] = 'estatusHist';
	$p['datos'] = $hist;

	// print2($datos);

	return inserta($p);

}

function guardaResp($resp,$vId = null){

	unset($resp['offline']);
	unset($resp['id']);
	$resp['visitasId'] = $vId != null ? $vId : $resp['visitasId'];

	$p['tabla'] = 'RespuestasVisita';
	$p['datos'] = $resp;

	return replace($p);

}

function guardaImagen($mult,$vId = null){

	$name = raiz().'campo/archivosCuest/'.$mult['archivo'];
	$file = $mult['File'];
	$realImage = base64_decode($file);
  
  	file_put_contents($name,$realImage);
	$mult['visitasId'] = $vId != null ? $vId : $mult['visitasId'];

  	unset($mult['id']);
  	unset($mult['File']);

  	$p = array();
  	$p['tabla'] = 'Multimedia';
  	$p['datos'] = $mult;
  	// print2($p);

  	return inserta($p);

}


?>