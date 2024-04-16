<?php

class Database
{
	private $connection; //conexión a BD
	private $results_page = 5; //nº de resultados por página

	/**
	 * Método constructor: crea la conexión con BD
	 */
	public function __construct(){
		$this->connection = new mysqli('127.0.0.1', 'root', '', 'api', '3306');

		if($this->connection->connect_errno){
			echo 'Error de conexión a la base de datos';
			exit;
		}
	}

	/**
	 * Método getDB: realiza la consulta select a la base de datos
	 * como respuesta a la petición get al endpoint
	 *
	 * @param string $table La tabla de BD
	 * @param [string|null] $extra Los parámetros de la consulta select
	 * @return array
	 */
	public function getDB($table, $extra = null)
	{
		$page = 0; //página por defecto 0
		$query = "SELECT * FROM $table"; //consulta por defecto

		//si existe el parámetro page en la petición get
		if(isset($extra['page'])){
			//lo recogemos para hacer la consulta select
			$page = $extra['page'];
			unset($extra['page']);
		}

		//si recibimos parámetros en la petición get...
		if($extra != null){
			//concatenamos las condiciones a la consulta por defecto
			$query .= ' WHERE';

			foreach ($extra as $key => $condition) {
				$query .= ' '.$key.' = "'.$condition.'"';
				if($extra[$key] != end($extra)){
					$query .= " AND ";
				}
			}
		}

		//si la página es mayor que 0, esto es, la recibimos por parámetro...
		if($page > 0){
			//limitamos la consulta a la paginación
			$since = (($page-1) * $this->results_page);
			$query .= " LIMIT $since, $this->results_page";
		}
		//si no, devolvemos los primetos 5 resultados indicados en el atributo results_page
		else{
			$query .= " LIMIT 0, $this->results_page";
		}

		$results = $this->connection->query($query); //realizamos consulta
		$resultArray = array(); //creamos un array que recoja la consulta

		//rellenamos el array que devuelve la consulta...
		foreach ($results as $value) {
			$resultArray[] = $value;
		}

		return $resultArray; //devolvemos la consulta
	}

	/**
	 * Método insertDB: inserta en la base de datos la tupla pasada por parámetro
	 *
	 * @param string $table La tabla en la que insertar la tupla
	 * @param array $data Los parámetros de la tupla
	 * @return [int | void] El id insertado
	 */
	public function insertDB($table, $data)
	{
		//se convierten los elementos del array $data en dos cadenas
		$fields = implode(',', array_keys($data)); //campos
		$values = '"';
		$values .= implode('","', array_values($data)); //valores
		$values .= '"';
		//que se van a usar en la inserción a BD
		$query = "INSERT INTO $table (".$fields.') VALUES ('.$values.')';
		$this->connection->query($query);

		return $this->connection->insert_id;
	}

	/**
	 * Método updateDB: actualiza una tupla en BD
	 *
	 * @param string $table La tabla en la que actualizar la tupla
	 * @param int $id El id de la tupla
	 * @param array $data Los parámetros de la tupla
	 * @return int Las tuplas afectadas
	 */
	public function updateDB($table, $id, $data)
	{	
		//generamos la sentencia sql
		$query = "UPDATE $table SET ";
		//con los parámetros recibidos por parámetro
		foreach ($data as $key => $value) {
			$query .= "$key = '$value'";
			//si son más de un parámetro, concatenamos con la separación ,
			if(sizeof($data) > 1 && $key != array_key_last($data)){
				$query .= " , ";
			}
		}
		//la tupla a actualizar
		$query .= ' WHERE id = '.$id;

		$this->connection->query($query);

		//si no modificamos ninguna tupla	
		if(!$this->connection->affected_rows){
			return 0;
		}

		//devolvemos el nº de tuplas afectadas	
		return $this->connection->affected_rows;
	}

	/**
	 * Método deleteDB: elimina la tupla indicada en el id
	 *
	 * @param string $table La tabla de la tupla a eliminar
	 * @param int $id El id de la tupla a eliminar
	 * @return int El nº de tuplas afectadas
	 */
	public function deleteDB($table, $id)
	{
		//Eliminamos la tupla con el id indicado
		$query = "DELETE FROM $table WHERE id = $id";
		$this->connection->query($query);
		//Si no se elimina, devolvemos 0
		if(!$this->connection->affected_rows){
			return 0;
		}
		//Devolvemos el nº de tuplas eliminadas
		return $this->connection->affected_rows;
	}
}


?>