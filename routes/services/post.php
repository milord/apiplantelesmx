<?php

require_once "models/connection.php";
require_once "controllers/post.controller.php";

if(isset($_POST)) {

    $columns = array();

    foreach (array_keys($_POST) as $key => $value) {

        array_push($columns, $value);
        
    }

    /*===============================
    Validar la tabla y las  columnas
    =================================*/

    if(empty(Connection::getColumnsData($table, $columns))){

        $json = array(

            'status' => 400,
            'resultado' => 'Error: Fields in the form do not match the database'
        
        );
        
        echo json_encode($json, http_response_code($json["status"]));

        return;

    }

    /*=======================================================================
    Solicitamos respuesta del controlador para crear datos en cualquier tabla
    =========================================================================*/

    $response = new PostController();
    $response -> postData($table, $_POST);

}


