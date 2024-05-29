<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos GET, POST, PUT, DELETE y opciones preflights
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permitir ciertos encabezados en las solicitudes
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, api-key");

require_once 'classes/Response.inc.php';
require_once 'classes/PruebaJavi.inc.php';

//Creamos el objeto de la clase User para manejar el endpoint
$forumApp = new Prueba ();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$params = $_GET;
		$forums = $forumApp->get($params);
		$response = array(
			'result' => 'ok',
			'forumsApp' => $forums
		);
		Response::result(200, $response);
		break;
}