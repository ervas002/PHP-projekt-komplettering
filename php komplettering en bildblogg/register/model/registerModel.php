<?php

require_once 'registerDAL.php';

Class RegisterModel{

    private $errorArray = array();
    private $userNameError = "Det verkar som att du försökt något skumt med användarnamnet! Endast bokstäver och siffor pls.";
    private $userTaken = "Det här användarnamnet finns redan!";
    private $nonMatchPassError = "Lösenorden matcher inte!";
    private $minUserLength = 6;
    private $minPassLength = 8;

    //Kontrollerar så att all input håller sig till reglerna.
    public function inputIsValid($username, $password, $rePassword){
        //Kör alla validering här först så den inte avbryter mitt i if-satsen
        // och skippar att lagra felmeddelanden
        $validUsername = $this->validUsername($username);
        $validPassword = $this->validPassword($password);
        $samePasswords = $this->comparePass($password, $rePassword);
        if(!$validUsername || !$validPassword || !$samePasswords){
            return false;
        }
        return true;
    }

    private function validUsername($username){
        if(strlen($username) < $this->minUserLength){
            $this->errorArray[] = "Användarnamnet är för kort, minst " . $this->minUserLength . " tecken";
            return false;
        }
        $registerDAL = new RegisterDAL();
        if(!$registerDAL->isUserNameFree($username)){
            $this->errorArray[] = $this->userTaken;
            return false;
        }
        if (preg_match('/^[<a-zA-Z0-9]*$/', $username)) {
            return true;
        } else {
            $this->errorArray[] = $this->userNameError;
            return false;
        }
    }

    private function validPassword($password){
        if(strlen($password) < $this->minPassLength){
            $this->errorArray[] = "Lösenordet är för kort, minst " . $this->minPassLength . " tecken";
            return false;
        }
        return true;
    }

    //Jämnför så att de angivna lösenorden matchar.
    private function comparePass($password, $rePassword){
        if($password === $rePassword){
            return true;
        }
        $this->errorArray[] = $this->nonMatchPassError;
        return false;
    }

    //Returnerar en array med felmeddelanden.
    public function getErrors(){
        return $this->errorArray;
    }
}