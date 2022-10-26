<?php

require_once "models/connection.php";
require_once "controllers/put.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])){

    /*===============================
    Capturamos datos del formulario
    ================================*/

    $data = array();
    parse_str(file_get_contents('php://input'), $data);

    /*===================================
    Separar propiedades en un arreglo
    ====================================*/

    $columns = array();

    foreach (array_keys($data) as $key => $value) {

        array_push($columns, $value);
        
    }

    array_push($columns, $_GET["nameId"]);

    $columns = array_unique($columns);
    
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
    Solicitamos respuesta del controlador para EDITAR datos en cualquier tabla
    =========================================================================*/

    $response = new PutController();
    $response -> putData($table, $data, $_GET["$id"], $_GET["$nameId"]);

}
