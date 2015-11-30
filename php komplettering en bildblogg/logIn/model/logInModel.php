<?php

require_once 'logInDAL.php';
require_once 'encryption/encryption.php';

Class LogInModel{

    private $logInDAL;
    private $encryption;

    public function __construct(){
        $this->logInDAL = new LogInDAL();
        $this->encryption = new Encryption();
    }

    //Jämnför inloggning.
    public function checkLogIn($username, $password){
        $salt = $this->logInDAL->getSaltOnUser($username);
        if($salt == ""){
            return false;
        }
        $hashedPassword = $this->encryption->HashPassword($password, $salt);
        if($this->logInDAL->checkLogIn($username, $hashedPassword)){
            return true;
        }
        return false;
    }
}