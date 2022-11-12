<?php

//ruta de la clase nusoap.php
require_once "../vendor/econea/nusoap/src/nusoap.php";
//Nombre del servicio
$namespace = "Soap/UserInsertSOAP";
$server = new soap_server();
$server->configureWSDL("userInsertSoap",$namespace); 
$server->wsdl->schemaTargeNamespace = $namespace;

//Estructura del servicio 
$server->wsdl->addComplexType(
    'userInsertSoap',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'dni_input' => array('name' => 'dni_input', 'type' => 'xsd:string'),
        'email_input' => array('name' => 'email_input', 'type' => 'xsd:string'),
        'lastname_input' => array('name' => 'lastname_input', 'type' => 'xsd:string'),
        'name_input' => array('name' => 'name_input', 'type' => 'xsd:string'),
        'password_input' => array('name' => 'password_input', 'type' => 'xsd:string'),
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
    "userInsertSoapService",
    array("userInsertSoap" => "tns:userInsertSoap"),
    array("userInsertSoap" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "Inserta los datos de un usuario"
);

function userInsertSoapService($request){

        $userType = $request['userType_input'];
        $name = $request['name_input'];
        $mensaje = "";
        $resultado = true;
    if (in_array($userType, array("teacher", "admin", "student"))){
        $dni = $request['dni_input'];
        $email = $request['email_input'];
        $lastname = $request['lastname_input'];
        $passwordHashed = password_hash($request['password_input'], PASSWORD_DEFAULT);
        $phone = $request['phone_input'];

        if($userType == "admin"){
            $roles = '[\"ROLE_ADMIN\"]';
        } elseif ($userType == "teacher") {
            $roles = '[\"ROLE_TEACHER\"]';
        } else {
            $roles = '[\"ROLE_STUDENT\"]';
        }
        
        $user_query = "INSERT INTO user (dni, email, lastname, name, password, phone, roles, discr) VALUES
        ('$dni', '$email', '$lastname', '$name', '$passwordHashed', '$phone', '$roles', '$userType')"; //Para insertar el padre
        
        $connection = new mysqli("localhost", "root", "", "db_api_rest_soap"); //el segundo es el username, el tercero la password, el cuarto database y el quinto (opcional) el puerto
        
        if (mysqli_query($connection, $user_query)) { //si se inserta el padre
            $id_ultimo_user = "SELECT MAX(id) as id FROM user";
            $son_query = "INSERT INTO $userType(id) VALUES (($id_ultimo_user))"; //Para inserta el hijo
            if(mysqli_query($connection, $son_query)){ //Si se inserta el hijo
                $mensaje = "$userType $name agregado correctamente";
            } else { //Si no se inserta el hijo
                $resultado = false;
                $mensaje = "Error hijo: ".mysqli_error($connection); //informo el error
                $user_query = "DELETE FROM user WHERE id = '$id_ultimo_user'"; //Para borrar el padre
                mysqli_query($connection, $user_query); //borro el padre ya que no se agregó el hijo
            }
        } else { //si no se inserta el padre
            $resultado = false;
            $mensaje = "Error padre: ".mysqli_error($connection); //informo el error
        }
    } else { //Si se envia tipo de usuario invalido (válidos son: admin, student y teacher)
        $mensaje = "Error: El usuario $name no se agrega, ya que el tipo $userType no es correcto"; 
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