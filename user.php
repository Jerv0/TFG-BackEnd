<?php
header("Access-Control-Allow-Origin: *");
// Permitir métodos GET, POST, PUT, DELETE y opciones preflights
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permitir ciertos encabezados en las solicitudes
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, api-key");
//Importamos la clase Response y la clase User
require_once 'classes/Response.inc.php';
require_once 'classes/User.inc.php';
// Permitir solicitudes desde cualquier origen

//Creamos el objeto de la clase User para manejar el endpoint
$user = new User();

function getTable()
{
	// Si el parámetro 'table' está presente, lo asignamos a la variable $table
	if (isset($_GET['table'])) {
		$table = $_GET['table'];
		// Eliminamos el parámetro 'tab' de la lista de parámetros
		unset($_GET['table']);
		return $table;
	} else {
		return 'usuario';
	}
}

//Comprobamos de qué tipo es la petición al endpoint
switch ($_SERVER['REQUEST_METHOD']) {

	case 'GET':  // *********************************************************************************
		$table = getTable();
		$params = [];

		// Recorremos los parámetros GET y los añadimos a $params, excepto 'table'
		foreach ($_GET as $key => $value) {
			if ($key !== 'table') {
				$params[$key] = $value;
			}
		}

		// Llamamos al método get de la clase User, pasando los parámetros GET
		$getBD = $user->get($table, $params);

		// Petición correcta
		$response = array(
			'result' => 'ok',
			'usuarios' => $getBD
		);

		Response::result(200, $response);

		break;

	case 'POST': // *********************************************************************************

		$params = json_decode(file_get_contents('php://input'), true);
		$table = getTable();

		$insertBD = $user->insert($table, $params);

		// Petición correcta
		$response = array(
			'result' => 'ok',
			'insert_id' => $insertBD
		);

		Response::result(201, $response);


		break;

	case 'PUT': // *********************************************************************************

		$params = json_decode(file_get_contents('php://input'), true);
		$table = getTable();

		// Obtener el primer elemento del array
		$id_key = key($params);
		$id_value = reset($params);
		$id_value = "'" . $id_value . "'";  // POR QUE EL ID ES STRING

		// Borrar el primer elemento del array
		unset($params[$id_key]);

		$user->update($table, $id_value, $id_key, $params);

		// Petición correcta
		$response = array(
			'result' => 'ok'
		);
		Response::result(200, $response);

		break;


	case 'DELETE': // *********************************************************************************

		$table = getTable();

		$id_key = '';
		$id_value = '';

		// Buscar el parámetro que comience con 'id_'
		foreach ($_GET as $key => $value) {
			if (strpos($key, 'id_') === 0) {
				$id_key = $key;
				$id_value = "'" . $value . "'"; // POR QUE EL ID ES STRING
				break;
			}
		}

		if (empty($id_key)) {
			// Petición error
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud: falta el parámetro id'
			);

			Response::result(400, $response);
			exit;
		}

		$user->delete($table, $id_key);

		// Petición correcta
		$response = array(
			'result' => 'ok',
			'ID' => $id_value,
			'VALOR' => $id_key
		);

		Response::result(200, $response);
		break;
}
