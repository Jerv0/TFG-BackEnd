<?php
//importamos todos los complementos necesarios
require_once dirname(__DIR__, 1) . "/jwt/JWT.php";
require_once 'AuthModel.inc.php';
require_once 'Response.inc.php';
use Firebase\JWT\JWT;

/**
 * Authentication class: permite realizar la autenticación
 */
class Authentication extends AuthModel
{
	private $table = 'personas';//tabla personas
	private $key = 'clave_secreta';//clave secreta necesaria para jwt

	/**
	 * Método signIn: permite realizar el logueo del usuario
	 *
	 * @param array $user Array con los datos del usuario 
	 * @return string|void El objeto jwt que permite el acceso con tokenID
	 */
	public function signIn(array $user): string
	{
		//si no envia las variables username y password o están vacíos...
		if (!isset($user['username']) || !isset($user['password']) || empty($user['username']) || empty($user['password'])) {
			//genera un error y devuelve la respuesta al cliente
			$response = array(
				'result' => 'error',
				'details' => 'Los campos password y username son obligatorios'
			);

			Response::result(400, $response);
			exit;
		}
		//llamamos al método login con el usuario y la password hasheada
		$result = parent::login($user['username'], hash('sha256', $user['password']));
		//comprobamos si el array devuelto por el método login tiene datos,
		//esto es, el usuario y la contraseña está en BD
		//si no encontramos usuario y contraseña...
		if (sizeof($result) == 0) {
			//generamos una respuesta de error que devolvemos al cliente
			$response = array(
				'result' => 'error',
				'details' => 'El usuario y/o la contraseña son incorrectas'
			);

			Response::result(403, $response);
			exit;
		}
		//si el logueo fue bien, generamos un array en el que indicamos
		//- iat: el horario en el que se genera el token
		//- data: incluye otro array que contiene
		//-- el id del usuario
		//-- el nombre del usuario    
		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $result[0]['id'],
				'nombre' => $result[0]['nombre']
			)
		);
		//usamos la librería JWT y generamos el token con los campos:
		//- datatoken: generado anteriormente
		//- clave secreta
		//- algoritmo de hasheo utilizado
		$jwt = JWT::encode($dataToken, $this->key, 'HS256');
		//llamámos al método update pasando el id del usuario
		parent::update($result[0]['id'], $jwt);

		return $jwt;
	}

	/**
	 * Método verify: verifica el token enviado para usar la API
	 *
	 * @return object|void Los datos solicitados validando el tokenID
	 */
	public function verify()
	{
		//si no existe la cabecera de api-key...
		if (!isset($_SERVER['HTTP_API_KEY'])) {
			//genera error y devuelve al cliente
			$response = array(
				'result' => 'error',
				'details' => 'Usted no tiene los permisos para esta solicitud'
			);

			Response::result(403, $response);
			exit;
		}

		$jwt = $_SERVER['HTTP_API_KEY'];//obtiene la cabecera api-key

		try {
			//$data = JWT::decode($jwt, $this->key, array('HS256'));//decodifica el tokenID
			$data = JWT::decode($jwt, $this->key, array('HS256'));

			$user = parent::getById($data->data->id);//consulta el ID de la BD
			//si no coincide el tokenID de la cabecera con el almacenado en BD...
			if ($user[0]['token'] != $jwt) {
				throw new Exception();//lanza excepción
			}

			return $data;//devuelve los datos solicitados
		} catch (\Throwable $th) { //recogemos la excepción generada por no coincidir los tokens
			//generamos el error y devolvemos la respuesta al cliente
			$response = array(
				'result' => 'error',
				'details' => 'No tiene los permisos para esta solicitud'
			);

			Response::result(403, $response);
			exit;
		}
	}
}
