<?php
//importamos las clases necesarias...
require_once 'classes/Request.inc.php';
require_once 'classes/Authentication.inc.php';
require_once 'classes/Response.inc.php';

$auth = new Authentication();

//Espera una petición POST que mande un username y un password
//que coincidirá en la BD y generará un token que se manda al usuario
//y este tiene que mandar al encabezado para poder hacer las peticiones API
switch ($_SERVER['REQUEST_METHOD']) {
	//Si recibe una petición POST...
	case 'POST':
		//Recupera los parámetros enviados por post y lo guarda en un array asociativo
		$user = json_decode(file_get_contents('php://input'), true);
		//guarda el resultado de llamar al método signIn pasando el array asociativo
		$token = $auth->signIn($user);

		$response = array(
			'result' => 'ok',
			'token' => $token
		);

		Response::result(201, $response);

		break;
}