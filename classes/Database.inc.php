<?php

class Database
{
	private $connection; //conexión a BD
	private $results_page = 5; //nº de resultados por página

	// Método constructor: crea la conexión con BD ****************************************
	public function __construct()
	{
		$this->connection = new mysqli('127.0.0.1', 'root', 'Root1.', 'tfg');

		if ($this->connection->connect_errno) {
			echo 'Error de conexión a la base de datos';
			exit;
		}
		$this->connection->set_charset("utf8");
	}

	// Método getDB: select a la base de datos ********************************************
	public function getDB($table, $extra = null)
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

	// Método insertDB: inserta en la base de datos la tupla pasada por parámetro ************
	public function insertDB($table, $data)
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

	// Método updateDB: actualiza una tupla en BD *********************************************
	public function updateDB($table, $id, $id_clave, $data)
	{
		//generamos la sentencia sql
		$query = "UPDATE $table SET ";
		//con los parámetros recibidos por parámetro
		foreach ($data as $key => $value) {
			$query .= "$key = '$value'";
			//si son más de un parámetro, concatenamos con la separación ,
			if (sizeof($data) > 1 && $key != array_key_last($data)) {
				$query .= " , ";
			}
		}
		//la tupla a actualizar
		$query .= ' WHERE ' . $id_clave . ' = ' . $id;

		$this->connection->query($query);

		//si no modificamos ninguna tupla	
		if (!$this->connection->affected_rows) {
			return 0;
		}

		//devolvemos el nº de tuplas afectadas	
		return $this->connection->affected_rows;
	}

	// Método deleteDB: elimina la tupla indicada en el id ***************************************
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

	// Método obtenerColumnas: recupera las columnas de la tabla para verificar los parámetros
	public function obtenerColumnas($table)
	{
		// Obtenemos las columas de la tabla
		$query = "SHOW COLUMNS FROM $table;";

		$resultado = $this->connection->query($query);
		// Recorremos la consulta de las columnas devueltas
		if ($resultado) {
			$columnas = array();
			while ($fila = $resultado->fetch_assoc()) {
				$columnas[] = $fila['Field'];
			}
			// Devolvemos el conjunto de columas
			return $columnas;
		} else {
			// Devolver error
			echo "Error al obtener las columnas de la tabla";
			return null;
		}
	}
}


