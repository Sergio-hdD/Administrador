<?php

//ruta de la clase nusoap.php
require_once "../../vendor/econea/nusoap/src/nusoap.php";
//Nombre del servicio
$namespace = "Soap/Student/StudentChangePasswordSOAP";
$server = new soap_server();
$server->configureWSDL("studentChangePasswordSoap",$namespace); 
$server->wsdl->schemaTargeNamespace = $namespace;

//Estructura del servicio 
$server->wsdl->addComplexType(
    'studentChangePasswordSoap',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id_user_input' => array('name' => 'id_user_input', 'type' => 'xsd:string'),
        'old_password_input' => array('name' => 'old_password_input', 'type' => 'xsd:string'),
        'new_password_input' => array('name' => 'new_password_input', 'type' => 'xsd:string'),
        'confirm_password_input' => array('name' => 'confirm_password_input', 'type' =>'xsd:string'),
    )      
);

//Estructura de la Respuesta del Servicio
$server->wsdl->addComplexType(
    'response',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'Mensaje' => array('name' => 'Mensaje', 'type' => 'xsd:string'),
        'Resultado' => array('name' => 'Resultado', 'type' => 'xsd:boolean'),
    )
);

$server->register(
    "studentChangePasswordSoapService",
    array("studentChangePasswordSoap" => "tns:studentChangePasswordSoap"),
    array("studentChangePasswordSoap" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "Cambia la password de un usuario"
);

function studentChangePasswordSoapService($request){
    $id = $request['id_user_input'];
    $old_password  = $request['old_password_input'];
    $new_password  = $request['new_password_input'];
    $confirm_password  = $request['confirm_password_input'];

    $mensaje =" ";
    $resultado = false;

    $user_query = "SELECT * FROM user WHERE id = $id";
    $connection = new mysqli("localhost", "root", "", "db_api_rest_soap"); //el segundo es el username, el tercero la password, el cuarto database y el quinto (opcional) el puerto
    
    $password_hashed_in_bd = "";
    $dni_in_bd = "";
    $resultado_query = mysqli_query($connection, $user_query);
//    $dni_in_bd = mysqli_fetch_array($resultado_query)['dni']; //Si necesito un solo campo lo puedo hacer así
    while($response_query_row = mysqli_fetch_array($resultado_query)){
        $password_hashed_in_bd = $response_query_row['password'];
        $dni_in_bd = $response_query_row['dni'];
    }

    if(password_verify($dni_in_bd, $password_hashed_in_bd)){ //Si no ha cambiado la contraseña temporal (que es el mismo dni)
        if(password_verify($old_password, $password_hashed_in_bd)){ //Verifico la contraseña ingresada
            if($new_password == $confirm_password){ //Verifico la confirmación 
                if($new_password == $dni_in_bd){ //Si intenta poner la misma password que tenía (que es el dni)
                    $mensaje = "Su password ya NO puede ser su DNI.";
                } else {
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $change_password_query = "UPDATE user SET password='$new_password_hashed' WHERE id=$id"; //Para actualizar
                    if(mysqli_query($connection, $change_password_query)){
                        $mensaje = "La password se ha modificado correctamente.";
                        $resultado = true;
                    } else {
                        $mensaje = "Error: ".mysqli_error($connection); //informo el error
                    }
                }
            } else {
                $mensaje = "La nueva password y la confimacion deben coincidir";
            }
        } else {
            $mensaje = "La password actual es incorrecta.";
        }
    } else {
        $mensaje = "Usted ya ha realizado el cambio de password temporal.";
    }

    return array(
        "Mensaje" => $mensaje,
        "Resultado" => $resultado
    );
}

$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();

?>