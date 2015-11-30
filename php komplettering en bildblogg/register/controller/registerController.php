<?php

require_once 'register/model/registerModel.php';
require_once 'register/model/registerDAL.php';
require_once 'encryption/encryption.php';
require_once 'register/view/registerView.php';

Class RegisterController{

    private $registerModel;
    private $registerView;
    private $registerDAL;
    private $encryption;

    public function __construct(){
        $this->registerModel = new RegisterModel();
        $this->registerView = new RegisterView();
        $this->registerDAL = new RegisterDAL();
        $this->encryption = new Encryption();
    }

    public function getHTML(){
        $this->tryRegister();
        return $this->registerView->getHTML();
    }

    //Försöker registrera användaren med de angivna uppgifterna.
    private function tryRegister(){
        if($this->registerView->isPostback()){
            if($this->registerView->validateFields()){
                $username = $this->registerView->getInputValue(registerView::USERID_FIELD);
                $password = $this->registerView->getInputValue(registerView::PASSWORD_FIELD);
                $rePassword = $this->registerView->getInputValue(registerView::REPASSWORD_FIELD);
                if($this->registerModel->inputIsValid($username, $password, $rePassword)){
                    $salt = $this->encryption->GetSalt();
                    $hashedPassword = $this->encryption->HashPassword($password, $salt);
                    $this->registerDAL->addNewUser($username, $hashedPassword, $salt);
                    header('Location: ./sign-in.php');
                }else{
                    $this->registerView->addErrors($this->registerModel->getErrors());
                }
            }
        }
    }
}