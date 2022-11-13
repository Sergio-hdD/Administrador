<?php

//ruta de la clase nusoap.php
require_once "../vendor/econea/nusoap/src/nusoap.php";
//Nombre del servicio
$namespace = "Soap/UserUpdateSOAP";
$server = new soap_server();
$server->configureWSDL("userUpdateSoap",$namespace); 
$server->wsdl->schemaTargeNamespace = $namespace;

//Estructura del servicio 
$server->wsdl->addComplexType(
    'userUpdateSoap',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id_user_input' => array('name' => 'id_user_input', 'type' => 'xsd:string'),
        'dni_input' => array('name' => 'dni_input', 'type' => 'xsd:string'),
        'email_input' => array('name' => 'email_input', 'type' => 'xsd:string'),
        'lastname_input' => array('name' => 'lastname_input', 'type' => 'xsd:string'),
        'name_input' => array('name' => 'name_input', 'type' => 'xsd:string'),
        'phone_input' => array('name' => 'phone_input', 'type' => 'xsd:string'),
        'userType_input' => array('name' => 'userType_input', 'type' => 'xsd:string'),
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
    "userUpdateSoapService",
    array("userUpdateSoap" => "tns:userUpdateSoap"),
    array("userUpdateSoap" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "Actualiza los datos de un usuario"
);

function userUpdateSoapService($request){

        $userType = $request['userType_input'];
        $name = $request['name_input'];
        $mensaje = "";
        $resultado = true;

    if (in_array($userType, array("teacher", "admin", "student"))){
        $id = $request['id_user_input'];
        $dni = $request['dni_input'];
        $email = $request['email_input'];
        $lastname = $request['lastname_input'];
        $phone = $request['phone_input'];
        
        $user_query = "UPDATE user SET dni='$dni', email='$email', lastname='$lastname', name='$name', phone='$phone' WHERE id=$id"; //Para actualizar
        
        $connection = new mysqli("localhost", "root", "", "db_api_rest_soap"); //el segundo es el username, el tercero la password, el cuarto database y el quinto (opcional) el puerto
        
        if (mysqli_query($connection, $user_query)) { //si se actualiza
            $mensaje = "$userType $name actualizado correctamente";
        } else { //si no se actualiza
            $resultado = false;
            $mensaje = "Error: ".mysqli_error($connection); //informo el error
        }
    } else { //Si se envia tipo de usuario invalido (válidos son: admin, student y teacher)
        $mensaje = "Error: El usuario $name no se actualiza, ya que el tipo $userType no es correcto"; 
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