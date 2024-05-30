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

    // Método para encriptar la contraseña
    private function encriptarContrasenia($params)
    {
        if (isset($params['pass'])) {
            $params['pass'] = password_hash($params['pass'], PASSWORD_BCRYPT);
        }
        return $params;
    }

    public function autenticar($table, $username, $password)
    {
        // Buscar el usuario por su nombre de usuario
        $query = "SELECT * FROM $table WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['pass'])) {
            // La contraseña es correcta
            return $user;
        } else {
            // La autenticación falló
            return null;
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

        // Encriptar la contraseña
        $params = $this->encriptarContraseña($params);

        // Todos los parámetros son válidos, proceder con la inserción
        return parent::insertDB($table, $params);
    }

    public function update($table, $id, $id_key, $params) {
        // Verificar los parámetros
        $this->verificarParametros($table, $params);

        // Encriptar la contraseña si está presente
        $params = $this->encriptarContraseña($params);

        // Todos los parámetros son válidos, proceder con la actualización
        return parent::updateDB($table, $id, $id_key, $params);
    }

    public function delete($table, $id, $id_key) {
        // Para delete no se necesitan parámetros
        return parent::deleteDB($table, $id, $id_key);
    }
}
