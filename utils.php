<?php
/**
 * 
 * tengo que hacer una consulta a la base de datos para buscar el array de los campos y asi hacerlo full dinamico
 * 
 * 
 */
//Meter todo aqui sobre validaciones de los modelos
require_once ("classes/Database.inc.php");


function validaciones(string $model)
{
    $request = new Database();
    $datos = $request->getDB($model);

    echoBonito($datos);
    //tendria que ver que me devuelve $datos y ahora si devuelve algo , meter el los array asociativos , todos los campos y en "otro" guardar todo menos el ID
    //unset($datos['id']);

    $permitidos = array(
        "solicitud" => array(
            "get" => array("id", "nombre", "email", "edad"),
            "otro" => array("nombre", "email", "edad"),
        )
    );

    return $permitidos;
}

function echoBonito($var)
{
    echo "<pre>";
    echo var_dump($var);
    echo "</pre>";
}