<?php

require_once 'classes/Response.inc.php';
require_once 'classes/Request.inc.php';
//require_once 'classes/Authentication.inc.php';
require_once 'utils.php';


//Creamos un objeto de la clase Authentication para saber si el usuario
//que usa el endpoint tiene los permisos para usar la API
//$auth = new Authentication();
//$auth->verify();//invocamos el método verify

//para recoger de la url el modelo
$model = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$fields = validations($model);

$request = new Request($model, $fields["solicitud"]["get"], $fields["solicitud"]["otro"]);
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
		$req = $request->get($params);

		//Creamos la respuesta en caso de realizar una petición correcta
		$response = array(
			'result' => 'ok',
			'array' => $req
		);

		Response::result(200, $response); //devolvemos la respuesta a la petición correcta

		break;
	//Método post
	/**
	 * Probar:
	 *  http://localhost/DWES/API/api/user
	 *  body -> raw (radio)
	 *  {
	 *     "nombre": "imagen subida",
	 *     "imagen": "<Base64 Image source from https://www.base64encoder.io/image-to-base64-converter/>"
	 *  }
	 */
	case 'POST':
		//Recogemos los  parámetros pasados por la petición post
		//decodificamos el json para convertirlo en array asociativo
		//php://input equivale a $_POST para leer datos raw del cuerpo de la petición
		$params = json_decode(file_get_contents('php://input'), true);

		//si no recibimos parámtros...
		if (!isset($params)) {
			//creamos el array de error y devolvemos la respuesta
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}

		//realizamos la inserción en BD de la petición
		$insert_id = $request->insert($params);

		//creamos el array de ok y devolvemos la respuesta junto al nuevo id
		$response = array(
			'result' => 'ok',
			'insert_id' => $insert_id
		);

		Response::result(201, $response);


		break;
	//Método put
	/**
	 * Probar:
	 *  http://localhost/DWES/API/api/user?id=1093
	 *  body -> raw (radio)
	 *  {
	 *     "nombre": "nueva prueba",
	 *     "disponible": "1"
	 *  }
	 */
	case 'PUT':
		//Recogemos los  parámetros pasados por la petición post
		//decodificamos el json para convertirlo en array asociativo
		//php://input equivale a $_POST para leer datos raw del cuerpo de la petición
		$params = json_decode(file_get_contents('php://input'), true);
		//Si no recibimos parámetros, no recibimos el parámetro id o id está vacío
		if (!isset($params) || !isset($_GET['id']) || empty($_GET['id'])) {
			//creamos el array de error 
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);
			//y lo devolvemos como respuesta
			Response::result(400, $response);
			exit;
		}
		//si recibimos el parámtro id y es correcto, realizamos la actualización
		$request->update($_GET['id'], $params);
		//creamos el array de respuesta correcta
		$response = array(
			'result' => 'ok'
		);
		//y devolvemos
		Response::result(200, $response);

		break;
	//Método delete
	/**
	 * Probar:
	 *  http://localhost/DWES/API/api/user?id=1092
	 */
	case 'DELETE':
		//Si no existe el parámetro id o está vacío
		if (!isset($_GET['id']) || empty($_GET['id'])) {
			//creamos el array de error y devolvemos la respuesta	
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}
		//eliminamos el id de la base de datos
		$request->delete($_GET['id']);
		//creamos el array de respuesta correcta
		$response = array(
			'result' => 'ok'
		);
		//devolvemos la respuesta
		Response::result(200, $response);
		break;
	//Si recibimos cualquier otro método diferente a get, post, put o delete...	
	default:
		//creamos el array de error
		$response = array(
			'result' => 'error'
		);
		//devolvemos la respuesta
		Response::result(404, $response);

		break;
}
