<?php
//Importamos la clase Response y la clase Database
require_once 'Response.inc.php';
require_once 'Database.inc.php';

class User extends Database
{
	private $table = 'personas'; //asignamos como atributo el nombre de la tabla

	//indicamos los parámetros válidos para las peticiones get mediante un array
	private $allowedConditions_get = array(
		'id',
		'nombre',
		'disponible',
		'page'
	);
	
	//indicamos los parámetros válidos para las peticiones post y put mediante un array
	private $allowedConditions_insert_update = array(
		'nombre',
		'disponible',
		'imagen'
	);

	/**
	 * Método validate: valida los parámetros recibidos que se usarán en BD
	 *
	 * @param [type] $data Los parámetros
	 * @return [void | boolean] Si son válidos
	 */
	private function validate($data){
		//si no existe el parámetro nombre...
		if(!isset($data['nombre']) || empty($data['nombre'])){
			//... genera la respuesta de error
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		//si existe el parámetro disponible y es diferente a 1 o 0....
		if(isset($data['disponible']) && !($data['disponible'] == "1" || $data['disponible'] == "0")){
			//... genera la respuesta de error
			$response = array(
				'result' => 'error',
				'details' => 'El campo disponible debe ser del tipo boolean'
			);

			Response::result(400, $response);
			exit;
		}
		//si existe el parámetro imagen y no está vacío
		if(isset($data['imagen']) && !empty($data['imagen'])){
			//separamos los metadatos de la propia codificación codificación
			$img_array = explode(';base64,',$data['imagen']);
			//obtenemos la extensión del archivo que nos mandan
			$extension = strtoupper(explode('/',$img_array[0])[1]);
			//comprobamos si la extensión es válida...
			if($extension != 'PNG' && $extension != 'JPEG' && $extension != 'JPG'){
				//genera la respuesta de error
				$response = array(
					'result' => 'error',
					'details' => 'La extensión del archivo debe ser PNG/JPEG/JPG'
				);
	
				Response::result(400, $response);
				exit;

			}
	
		}
		return true;
	}

	/**
	 * Método get: recibe los parámetros de la petición get,
	 * los recorre para comprobar si son válidos,
	 * si no lo son los elimina y devuelve una respuesta json de error,
	 * si lo son realiza la consulta a DB y devuelve un json con la respuesta correcta
	 *
	 * @param array $params Los parámetros get usados en BD
	 * @return [array | void] Los usuarios de la BD
	 */
	public function get($params){
		//Recorremos los parámetros get
		foreach ($params as $key => $param) {
			//si los parámetros no están permitidos...
			if(!in_array($key, $this->allowedConditions_get)){
				//eliminamos los parámetros
				unset($params[$key]);
				//creamos el array de error
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
				//devolvemos la petición de error convertida a json
				Response::result(400, $response);
				exit;
			}
		}
		//llamamos 
		$usuarios = parent::getDB($this->table, $params);

		return $usuarios;
	}

	/**
	 * Método insert: recibe los parámetros de la petición post,
	 * los recorre para comprobar si son válidos,
	 * si no lo son los elimina y devuelve una respuesta json de error,
	 * si lo son realiza la inserción en DB y devuelve el id de la tupla insertada
	 *
	 * @param array $params Los parámetros get usados en BD
	 * @return [int | void] Los usuarios de la BD
	 */
	public function insert($params)
	{
		//recorremos los parámetros
		foreach ($params as $key => $param) {
			//si no están permitidos
			if(!in_array($key, $this->allowedConditions_insert_update)){
				//eliminamos los parámetros
				unset($params[$key]);
				//generamos la respuesta de error
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
	
				Response::result(400, $response);
				exit;
			}
		}
		//si son parámtros válidos
		if($this->validate($params)){
			//si existe el parámetro imagen...
			if(isset($params['imagen'])){
				//separamos los metadatos de la propia codificación codificación
				$img_array = explode(';base64,',$params['imagen']);
				//recuperamos el archivo enviado en base64
				$img_file = $img_array[1];
				//obtenemos la extensión del archivo que nos mandan
				$extension = strtolower(explode('/',$img_array[0])[1]);
				//generamos un id único
				$name = uniqid();
				//creamos la nueva ruta donde alojar la imagen
				$path = dirname(__DIR__,1)."\public\img\\".$name.".".$extension;
				//ubicamos la imagen recibida en la ruta creada anteriormente
				file_put_contents($path,base64_decode($img_file));
				//actualizamos el nombre y la extensión de la imagen para guardar en BD
				$params['imagen'] = $name.".".$extension;
			
			}
			//insertamos en BD y obtenemos el id de la tupla insertada
			return parent::insertDB($this->table, $params);
		}
	}

	/**
	 * Método update: recibe los parámetros de la petición put,
	 * los recorre para comprobar si son válidos,
	 * si no lo son los elimina y devuelve una respuesta json de error,
	 * si lo son realiza la inserción en DB y devuelve el id de la tupla insertada
	 *
	 * @param int $id El id de la tupla a actualizar
	 * @param array $params Los parámetros get usados en BD
	 * @return void Los usuarios de la BD
	 */
	public function update($id, $params)
	{
		//recorremos los parámetros
		foreach ($params as $key => $parm) {
			//si no son válidos
			if(!in_array($key, $this->allowedConditions_insert_update)){
				//eliminamos
				unset($params[$key]);
				//generamos la respuesta de error y devolvemos
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
	
				Response::result(400, $response);
				exit;
			}
		}
		//si son parámetros válidos
		if($this->validate($params)){
			//realizamos la actualización en BD pasando los parámetros y el id de la tupla
			$affected_rows = parent::updateDB($this->table, $id, $params);
			//si no se actualizó, generamos la respuesta
			if($affected_rows==0){
				$response = array(
					'result' => 'error',
					'details' => 'No hubo cambios'
				);

				Response::result(200, $response);
				exit;
			}
		}
	}

	/**
	 * Método delete: recibe el id de la petición delete
	 *
	 * @param int $id El id de la tupla a eliminar
	 */
	public function delete($id)	
	{
		//elimina la tupla y recibe el número de tuplas afectadas
		$affected_rows = parent::deleteDB($this->table, $id);
		//si es 0 significa que no eliminó ninguna tupla
		if($affected_rows==0){
			//genera la respuesta de error y la devuelve	
			$response = array(
				'result' => 'error',
				'details' => 'No hubo cambios'
			);

			Response::result(200, $response);
			exit;
		}
	}
}

?>