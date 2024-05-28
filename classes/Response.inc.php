<?php
/**
 * Clase Response: se encarga de crear la respuesta json a la petición del endpoint
 */
class Response{

	public static function result($code, $response){
		header('Content-type: application/json');
		http_response_code($code);
		echo json_encode($response);
	}
}

?>