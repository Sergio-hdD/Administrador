<?php

namespace App\Service;

use SoapClient;

class SoapService {

    public function __construct(){

    }
    
    function userInsert_soap($form, $userType){
        $soapClient = new SoapClient("http://localhost/Administrador/Soap/UserInsertSoap.php?wsdl");

        $params['dni_input'] = $form['dni']->getData();
        $params['email_input'] = $form['email']->getData();
        $params['lastname_input'] = $form['lastname']->getData();
        $params['name_input'] = $form['name']->getData();
        $params['password_input'] = $form['password']->getData();
        $params['phone_input'] = $form['phone']->getData();
        $params['userType_input'] = $userType;
        
        return $soapClient->userInsertSoapService($params);
    }

    
    function userUpdate_soap($id, $form, $userType){
        $soapClient = new SoapClient("http://localhost/Administrador/Soap/UserUpdateSoap.php?wsdl");
        
        $params['id_user_input'] = $id;
        $params['dni_input'] = $form['dni']->getData();
        $params['email_input'] = $form['email']->getData();
        $params['lastname_input'] = $form['lastname']->getData();
        $params['name_input'] = $form['name']->getData();
        $params['phone_input'] = $form['phone']->getData();
        $params['userType_input'] = $userType;
        
        return $soapClient->userUpdateSoapService($params);
    }

}