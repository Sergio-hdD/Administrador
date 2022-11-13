<?php

namespace App\Service;

use SoapClient;

class SoapService {

    public function __construct(){

    }
    
    function userInsert_soap($params){
        $soapClient = new SoapClient("http://localhost/Administrador/Soap/UserInsertSoap.php?wsdl");
        
        return $soapClient->userInsertSoapService($params);
    }

    
    function userUpdate_soap($params){
        $soapClient = new SoapClient("http://localhost/Administrador/Soap/UserUpdateSoap.php?wsdl");

        return $soapClient->userUpdateSoapService($params);
    }

    
    function userDelete_soap($id, $userType){
        $soapClient = new SoapClient("http://localhost/Administrador/Soap/UserDeleteSoap.php?wsdl");
        
        $params['id_user_input'] = $id;
        $params['userType_input'] = $userType;
        
        return $soapClient->userDeleteSoapService($params);
    }

        
    function createParamsUserInsert($form, $userType){

        $params['dni_input'] = $form['dni']->getData();
        $params['email_input'] = $form['email']->getData();
        $params['lastname_input'] = $form['lastname']->getData();
        $params['name_input'] = $form['name']->getData();
        $params['password_input'] = $form['password']->getData();
        $params['phone_input'] = $form['phone']->getData();
        $params['userType_input'] = $userType;
        
        return $params;
    }

    
    function createParamsUserUpdate($id, $form, $userType){
        
        $params['id_user_input'] = $id;
        $params['dni_input'] = $form['dni']->getData();
        $params['email_input'] = $form['email']->getData();
        $params['lastname_input'] = $form['lastname']->getData();
        $params['name_input'] = $form['name']->getData();
        $params['phone_input'] = $form['phone']->getData();
        $params['userType_input'] = $userType;
        
        return $params;
    }

}