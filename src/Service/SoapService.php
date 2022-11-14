<?php

namespace App\Service;

use App\Entity\Student;
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

    function student_change_password($params){
        $soapClient = new SoapClient("http://localhost/Administrador/Soap/Student/StudentChangePasswordSoap.php?wsdl");
        
        return $soapClient->studentChangePasswordSoapService($params);
    }
        
    function createParamsUserInsert($form, $userType){

        $params['dni_input'] = $form['dni']->getData();
        $params['email_input'] = $form['email']->getData();
        $params['lastname_input'] = $form['lastname']->getData();
        $params['name_input'] = $form['name']->getData();
        if($userType == Student::STR_USER_TYPE){
            $params['password_input'] = $form['dni']->getData();
        } else {
            $params['password_input'] = $form['password']->getData();
        }
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

    function createParamsChangePassword($form, $id_student){
        
        $params['id_user_input'] = $id_student;
        $params['old_password_input'] = $form['oldPassword']->getData(); 
        $params['new_password_input'] = $form['newPassword']->getData();
        $params['confirm_password_input'] = $form['confirmPassword']->getData();

        return $params;
    }
}