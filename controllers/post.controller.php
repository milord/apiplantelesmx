<?php

require_once "models/get.model.php";
require_once "models/post.model.php";
require_once "models/connection.php";

require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

require_once "models/put.model.php";

class PostController{

    /*===============================
    Pertición POST para crear datos
    =================================*/   

    static public function postData($table, $data){

        $response = PostModel::postData($table, $data);

        $return = new PostController();
        $return -> fncResponse($response, null);

    }

    /*====================================
    Pertición POST para registrar usuario
    ====================================*/   

    static public function postRegister($table, $data, $suffix){
        
        if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null) {

            $crypt = crypt($data["password_".$suffix], '$2a$07$azybxcags23425sdg23sdfhsd$');

            $data["password_".$suffix] = $crypt;

            $response = PostModel::postData($table, $data);

            $return = new PostController();
            $return -> fncResponse($response, null);

        }
        
    }

    /*====================================
    Pertición POST para login de usuario
    ====================================*/   

    static public function postLogin($table, $data, $suffix){
        
        /*=====================================
        Validar que el usuario exista en la BD
        =====================================*/
       
        $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix], null, null, null, null);

        if(!empty($response)) {

            /*====================================
            Encriptamos la contraseña
            ====================================*/

            $crypt = crypt($data["password_".$suffix], '$2a$07$azybxcags23425sdg23sdfhsd$');

            if($response[0]->{"password_".$suffix} == $crypt){

                $token = Connection::jwt($response[0]->{"id_".$suffix}, $response[0]->{"email_".$suffix});
                
                $jwt = JWT::encode($token, "dfhsdfg34dfchs4xgsrsdry46", "HS256");

                /*=============================================
                Actualizamos en la BD con el token del usuario
                ===============================================*/

                $data = array(

                    "token_".$suffix => $jwt,
                    "token_exp".$suffix => $token["exp"]

                );

                $update = PutModel::putData($table, $data, $response[0]->{"id_".$suffix}, "id_".$suffix);
                
                if($update["comment"] == "The process was successful") {



                }

            }else{

                $response = null;
                $return = new PostController();
                $return -> fncResponse($response, "Wrong password");
            }

        }else{

            $response = null;
            $return = new PostController();
            $return -> fncResponse($response, "Wrong email");

        }

    }

    /*=============================
    Las respuestas del controlador
    =============================*/

    public function fncResponse($response, $error){

        if(!empty($response)) {

            $json = array(

                'status' => 200,
                'results' => $response
            
            );

        }else{

            if($error != null) {

                $json = array(
                    'status' => 400,
                    "results" => $error
                );

            }else {

                $json = array(

                    'status' => 404,
                    'results' => 'Not found',
                    'method' => 'post'
    
                );

            }

        }

        echo json_encode($json, http_response_code($json["status"]));

    }

}