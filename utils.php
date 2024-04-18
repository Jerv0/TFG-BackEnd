<?php

require_once ("classes/Database.inc.php");

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function validations(string $model)
{
    $request = new Database();
    //Consulta para saber los campos
    $data = $request->getDB($model);

    $campos = array();
    //Obtengo los campos de la base de datos 
    foreach ($data[0] as $clave => $valor) {
        array_push($campos, $clave);
    }

    //Si no esta vacio hace esto para tener todos los campos y en otro quito el campo ID que siempre deberia estar en la posicion 0 al tratarse de un ID
    if (!empty($campos)) {
        $allowed = array(
            "solicitud" => array(
                "get" => $campos,
                "otro" => $campos,
            )
        );
        unset($allowed['solicitud']['otro'][0]);  

        //retornamos el array bidimensional con lo que necesitamos
        return $allowed;
    } else
        return null;
}

function echoBonito($var)
{
    echo "<pre>";
    echo var_dump($var);
    echo "</pre>";
}