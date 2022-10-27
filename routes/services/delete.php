<?php

require_once "models/connection.php";
require_once "controllers/delete.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])) {

    $columns = array($_GET["nameID"]);

    /*===========================================
    Validar la tabla y las columnas
    ===============================-============ */

    if(empty(Connection::getColumnsData($table, $columns))) {

        $json = array(
            'status' => 400,
            'results' => "Error: fields in the form do not match the database"
        );

        echo json_encode($json, http_response_code($json["status"]));

        return;
    }

     /*==========================================================================
    Solicitamos respuesta del controlador para eliminar datos en cualquier tabla
    ===============================-=========================================== */

    $response = new DeleteController();
    $response -> deleteData($table, $GET["id"], $_GET["nameId"]);
    
}