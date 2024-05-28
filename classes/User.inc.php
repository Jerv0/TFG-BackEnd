<?php
// Importamos la clase Response y la clase Database
require_once 'Response.inc.php';
require_once 'Database.inc.php';

class User extends Database
{
    // Función privada para verificar parámetros
    private function verificarParametros($table, $params) {
        // Verificar si los parámetros están dentro de las columnas de la tabla
        $columnas = $this->obtenerColumnas($table);
        foreach ($params as $key => $param) {
            if (!in_array($key, $columnas)) {
                // Si un parámetro no está permitido, devolver un error
                $response = array(
                    'result' => 'error',
                    'details' => 'El parámetro ' . $key . ' no es válido para la tabla ' . $table
                );
                Response::result(400, $response);
                exit;
            }
        }
    }

    public function get($table, $params) {
        // Verificar los parámetros
        $this->verificarParametros($table, $params);

        // Todos los parámetros son válidos, proceder con la consulta
        $usuarios = parent::getDB($table, $params);
        return $usuarios;
    }

    public function insert($table, $params) {
        // Verificar los parámetros
        $this->verificarParametros($table, $params);

        // Todos los parámetros son válidos, proceder con la inserción
        return parent::insertDB($table, $params);
    }

    public function update($table, $id, $id_key, $params) {
        // Verificar los parámetros
        $this->verificarParametros($table, $params);

        // Todos los parámetros son válidos, proceder con la actualización
        return parent::updateDB($table, $id, $id_key, $params);
    }

    public function delete($table, $id, $id_key) {
        // Para delete no se necesitan parámetros
        return parent::deleteDB($table, $id, $id_key);
    }
}
?>
