<?php

class AuthModel
{
	private $connection;//atributo que se guarda la conexión
	/**
	 * Constructor que se encarga de realizar la conexión
	 */
	public function __construct()
	{
		//CAMBIAR A LAS VARIABLES DEL .ENV
		$this->connection = new mysqli('127.0.0.1', 'root', '', 'api', '3306');

		if ($this->connection->connect_errno) {
			echo 'Error de conexión a la base de datos';
			exit;
		}
	}
	/**
	 * Método login: realiza la llamada a BD con el usuario y contraseña y comprueba si existe en BD
	 *
	 * @param string $username Usuario en BD
	 * @param string $password Contraseña en BD
	 * @return array Array asociativo con la información del usuario
	 */
	public function login(string $username, string $password): array
	{
		$query = "SELECT id, nombre, username FROM personas WHERE username = '$username' AND password = '$password'";

		$results = $this->connection->query($query);

		$resultArray = array();

		if ($results != false) {
			foreach ($results as $value) {
				$resultArray[] = $value;
			}
		}

		return $resultArray;
	}
	/**
	 * Método update: actualiza el token del usuario pasado por parámetro
	 *
	 * @param int $id id del usuario en BD
	 * @param string $token El token generado para el usuario
	 * @return int
	 */
	public function update(int $id, string $token): int|string
	{
		$query = "UPDATE personas SET token = '$token' WHERE id = $id";

		$this->connection->query($query);

		if (!$this->connection->affected_rows) {
			return 0;
		}

		return $this->connection->affected_rows;
	}

	public function getById(int $id): array
	{
		$query = "SELECT token FROM personas WHERE id = $id";

		$results = $this->connection->query($query);

		$resultArray = array();

		if ($results != false) {
			foreach ($results as $value) {
				$resultArray[] = $value;
			}
		}

		return $resultArray;
	}
}
