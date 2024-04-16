<?php

//Meter todo aqui sobre validaciones de los modelos
function validaciones(string $model)
{
    switch ($model) {
        case "usuarios":
            $permitidos = array(
                "solicitud" => array(
                    "get" => array("id", "nombre", "email", "edad"),
                    "otro" => array("nombre", "email", "edad"),
                )
            );
            break;
    }
    return $permitidos;
}