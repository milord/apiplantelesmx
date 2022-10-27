<?php

require_once "models/delete.model.php";

class DeleteController {

    /*==========================================
    Petición Delete para eliminar datos
    ========================================== */

    static public function deleteData($table, $id, $nameId) {

        $response = DeleteModel::deleteData($table, $id, $nameId);

        $return = new DeleteController();
        $return -> fncResponse($response);

    }

     /*==========================================
    Petición Delete para eliminar datos
    ========================================== */

    public function fncResponse($response) {

        if(!empty($response)) {

            $json = array(

                'status' => 200,
                'results' => $response
            );
        }else{

            $json = array(

                'status' => 400,
                'results' => 'Not found',
                'method' => 'delete'
            );
        }

        echo json_encode($json, http_response_code($json["status"]));

    }
}