<?php

class Database
{
	private $connection; //conexión a BD

	/**
	 * Método constructor: crea la conexión con BD
	 */
	public function __construct()
	{
		//Recoge los valores del .env
		$this->connection = new mysqli($_ENV["DB_IP"], $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"], $_ENV["DB_DATABASE"], $_ENV["DB_PORT"]);

		if ($this->connection->connect_errno) {
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
	public function getDB(string $table, ?array $extra = null): array
	{
		$query = "SELECT * FROM $table"; //consulta por defecto
		//si recibimos parámetros en la petición get...
		if ($extra != null) {
			//concatenamos las condiciones a la consulta por defecto
			$query .= ' WHERE';

			foreach ($extra as $key => $condition) {
				$query .= ' ' . $key . ' = "' . $condition . '"';
				if ($extra[$key] != end($extra)) {
					$query .= " AND ";
				}
			}
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
	public function insertDB(int $table, array $data): int
	{
		//se convierten los elementos del array $data en dos cadenas
		$fields = implode(',', array_keys($data)); //campos
		$values = '"';
		$values .= implode('","', array_values($data)); //valores
		$values .= '"';
		//que se van a usar en la inserción a BD
		$query = "INSERT INTO $table (" . $fields . ') VALUES (' . $values . ')';
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
	public function updateDB(string $table, int $id, array $data): int
	{
		//generamos la sentencia sql
		$query = "UPDATE $table SET ";
		//con los parámetros recibidos por parámetro
		foreach ($data as $key => $value) {
			$query .= "$key = '$value'";
			//si son más de un parámetro, concatenamos con la separación ,
			if (count($data) > 1 && $key != array_key_last($data)) {
				$query .= " , ";
			}
		}
		//la tupla a actualizar
		$query .= ' WHERE id = ' . $id;

		$this->connection->query($query);

		//si no modificamos ninguna tupla	
		if (!$this->connection->affected_rows) {
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
	public function deleteDB(string $table, int $id): int
	{
		//Eliminamos la tupla con el id indicado
		$query = "DELETE FROM $table WHERE id = $id";
		$this->connection->query($query);
		//Si no se elimina, devolvemos 0
		if (!$this->connection->affected_rows) {
			return 0;
		}
		//Devolvemos el nº de tuplas eliminadas
		return $this->connection->affected_rows;
	}
}


