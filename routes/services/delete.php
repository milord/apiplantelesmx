<?php

require_once "models/connection.php";
require_once "controllers/delete.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])) {

    $columns = array($_GET["nameID"]);

    /*===========================================
    Validar la tabla y las columnas
    ============================================ */

    if(empty(Connection::getColumnsData($table, $columns))) {

        $json = array(
            'status' => 400,
            'results' => "Error: fields in the form do not match the database"
        );

        echo json_encode($json, http_response_code($json["status"]));

        return;
    }

    /*===========================================
    Petición DELETE para usuarios autorizados
    ============================================*/

    if(isset($_GET["token"])) {

        $tableToken = $_GET["table"] ?? "users";
        $suffix = $_GET ["suffix"] ?? "user";

        $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);

        /*=======================================================================
        Solicitamos respuesta del controlador para EDITAR datos en cualquier tabla
        =========================================================================*/

        if($validate == "ok") {

            $response = new DeleteController();
            $response -> deleteData($table, $GET["id"], $_GET["nameId"]);

        }

        /*=========================================
        Error cuando el token ha expirado
        ==========================================*/

        if($validate == "expired") {

            $json = array(
                'status' => 303,
                'results' => "Error: The token has expired"
            );

            echo json_encode($json, http_response_code($json["status"]));

            return;

        }

        /*===========================================
        Error cuando el token no coincide en la BD
        ============================================*/

        if($validate == "no-auth") {

            $json = array(
                'status' => 400,
                'results' => "Error: The user is not authorized"
            );

            echo json_encode($json, http_response_code($json["status"]));

            return;
            
        }
    
    /*===============================================
    Error cuando no envía token
    ================================================*/    
    
    }else{

        $json = array(

            'status' => 400,
            'results' => "Error: Authorization required"
        );

        echo json_encode($json, http_response_code($json["status"]));

        return;

    }

}