<?php
//Importamos la clase Response y la clase User
require_once 'classes/Response.inc.php';
require_once 'classes/PruebaJavi.inc.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos GET, POST, PUT, DELETE y opciones preflights
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permitir ciertos encabezados en las solicitudes
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Cache-Control ,api-key");

//Creamos el objeto de la clase User para manejar el endpoint
$user = new Prueba();

//Comprobamos de qué tipo es la petición al endpoint
switch ($_SERVER['REQUEST_METHOD']) {
	//Método get
	/**
	 * Probar:
	 *  http://localhost/DWES/API/api/user
	 *  http://localhost/DWES/API/api/user?id=1
	 *  http://localhost/DWES/API/api/user?nombre=Nacho
	 *  http://localhost/DWES/API/api/user?disponible=1
	 *  http://localhost/DWES/API/api/user?page=3
	 *  Cualquier combinación válida de los anteriores. 
	 *  En caso de recibir un parámetro distinto dará error. 
	 */
	case 'GET':
		//Recogemos los parámetros de la petición get
		$params = $_GET;

		//Llamamos al método get de la clase User, le pasamos los 
		//parámetros get y comprobamos:
		//1º) si recibimos parámetros
		//2º) si los parámetros están permitidos
		$usuarios = $user->get($params);

		//Creamos la respuesta en caso de realizar una petición correcta
		$response = array(
			'result' => 'ok',
			'usuarios' => $usuarios
		);

		Response::result(200, $response); //devolvemos la respuesta a la petición correcta

		break;
}