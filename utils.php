<?php
/**
 * 
 * tengo que hacer una consulta a la base de datos para buscar el array de los campos y asi hacerlo full dinamico
 * 
 * 
 */
//Meter todo aqui sobre validaciones de los modelos
require_once ("classes/Database.inc.php");


function validations(string $model)
{
    $request = new Database();
    $data = $request->getDB($model);

    echoBonito($data);
    //tendria que ver que me devuelve $datos y ahora si devuelve algo , meter el los array asociativos , todos los campos y en "otro" guardar todo menos el ID
    
    if (!empty($data)) {
        $allowed = array(
            "solicitud" => array(
                "get" => $data[0],
                "otro" => $data[0],
            )
        );
        //Quitamos id ya que para un insert/update no lo necesitamos
        //unset($datos['id']);
        unset($allowed['solicitud']['otro']['id']);

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