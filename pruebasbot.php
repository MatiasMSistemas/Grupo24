<?php
//	require_once directorio . "controller/turnos.php";
	define("directorio", __dir__ . "/"); 
	require_once directorio . 'api/src/rutas/turnos.php';
	require_once directorio . 'vendor/rmccue/requests/library/Requests.php'; 
//	require_once directorio . "controller/Telegram.php";



	$returnArray = true;
	$rawData = file_get_contents('php://input');
	$response = json_decode($rawData, $returnArray);
	$id_del_chat = intval($response['message']['chat']['id']);


	// Obtener comando (y sus posibles parametros)
	$regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';

	$app->run();

	$tmp = preg_match($regExp, $response['message']['text'], $aResults);

	if (isset($aResults[1])) {
		$cmd = trim($aResults[1]);
		$cmd_params = trim($aResults[2]);
	} else {
		$cmd = trim($response['message']['text']);
		$cmd_params = '';
	}
	
	$msg = array();
	$msg['chat_id'] = $id_del_chat;
	$msg['text'] = null;
	$msg['disable_web_page_preview'] = true;
	$msg['reply_to_message_id'] = $response['message']['message_id'];
	$msg['reply_markup'] = null;
	Requests::register_autoloader();

	switch ($cmd) {
	case '/start':
	    $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] . PHP_EOL;
	    $msg['text'] .= '¿Como puedo ayudarte? /help';
	    $msg['reply_to_message_id'] = null;
	    break;

	case '/help':
	    $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
	    $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;
	    $msg['text'] .= '/turnos aaaa-mm-dd Muestra los turnos disponibles del día' . PHP_EOL;
	    $msg['text'] .= '/reservar DNI aaaa-mm-dd hh:mm Realiza la reserva del turno' . PHP_EOL;
	    $msg['text'] .= '/help Muestra esta ayuda flaca';
	    $msg['reply_to_message_id'] = null;
	    break;

	case '/reservar':
		if (isset($aResults[2]) and isset($aResults[3]) and isset($aResults[4])){
			$headers = array('Accept' => 'application/json');
    		$options = array();
    		$collection_respose = Requests::get('https://grupo24.proyecto2017.linti.unlp.edu.ar/api/public/index.php/api/turnos/'. $aResults[2] . '/fecha/' . $aResults[3] . '/hora/' . $aResults[4], $headers, $options);
    		$json_collection = $collection_respose->body;
    		$verificacion = json_decode($json_collection, true);
//      		$verificacion = file_get_contents('https://grupo24.proyecto2017.linti.unlp.edu.ar/api/public/index.php/api/turnos/'. $aResults[2] . '/fecha/' . $aResults[3] . '/hora/' . $aResults[4]);
		}
		if ($verificacion) {
		    $msg['text']  = 'Te confirmamos el turno para el dia ' . $aResults[3] . ' a las ' . $aResults[4] . PHP_EOL;
		    $msg['text']  .=   PHP_EOL;
		}
	    $msg['reply_to_message_id'] = null;
	    break;

	case '/turnos':
		if (isset($aResults[2])){
      		$headers = array('Accept' => 'application/json');
    		$options = array();
    		$aResults[2] = str_replace(' ', '', $aResults[2]);
    		$collection_respose = Requests::get('https://grupo24.proyecto2017.linti.unlp.edu.ar/api/public/index.php/api/turnos/'. $aResults[2], $headers, $options);
    		//$turnos = intval($collection_respose['headers']['data:protected']['date']);
    		//$json_collection = $collection_respose->headers;
    		$turnos = json_decode($collection_respose, true);
    		error_log(print_r("estoy entrando al /turnos y volviendo del request",TRUE));
    		error_log(print_r($collection_respose,TRUE));

    		error_log(print_r("ya imprimi el collection_respose",TRUE));
//      		$turnos = file_get_contents('https://grupo24.proyecto2017.linti.unlp.edu.ar/api/public/index.php/api/turnos/'. $aResults[2]);
		}
	    $msg['text']  = 'Los turnos disponibles son:' . $turnos['date'] . PHP_EOL;
	    break;
	default:
			$msg['text']  = 'Lo siento, no es un comando válido.' . PHP_EOL;
			$msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
			break;
	}

	$url = 'https://api.telegram.org/bot485653504:AAFsndiixhRVgJy0jTE70RR-xVmrk5UPh2M/sendMessage';
//	$url = 'https://api.telegram.org/bot487248804:AAElgfl6r5JlBrTMh0IHultwPR_IVpFJWQw/sendmessage?chat_id=' . $msg['chat_id'] . '&text=' . $msg['text'] ;

	$context = stream_context_create([
	'http' => [
		'method'  => 'POST',
	    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	    'content' => http_build_query($msg)
	    ]
	]);

	

	

//	$telegram = new TelegramController;

//	$result = $telegram->post($url,$msg);

//	error_log(print_r($result,TRUE));


//	error_log(print_r($options,TRUE));

//	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
//	$result = file_get_contents($result);
	unset($response, $cmd, $aResults);
//	$app->halt(500);
//	error_log(print_r("llego al stop"));
//	$app->stop();
//	error_log(print_r("llego al stop"));
//	$app->finalize();
//	error_log(print_r("llego al finalice"));
	exit(0);





//	$context  = stream_context_create($options);
//	
	




?>