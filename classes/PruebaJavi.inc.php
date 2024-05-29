<?php
require_once 'Response.inc.php';
require_once 'DatabasePrueba.inc.php';

class Prueba extends Database
{
	private $table = 'usuario';

	private $allowedConditions_get = array(
		'id',
		'usertype',
		'username',
		'email'
	);

	/**
	 * @param array $params
	 * @return
	 */
	public function get($params){
		foreach ($params as $key => $param) {
			if(!in_array($key, $this->allowedConditions_get)){
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
				Response::result(400, $response);
				exit;
			}
		}
		//llamamos 
		$users = parent::getDB($this->table, $params);

		return $users;
	}

}