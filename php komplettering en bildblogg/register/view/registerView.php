<?php

Class RegisterView{

    private $errorMessages = "";

    const USERID_FIELD = "usernameInput";
    const PASSWORD_FIELD = "passwordInput";
    const REPASSWORD_FIELD = "repeatPasswordInput";

    private $missingUsername = "Det saknas användarnamn!";
    private $missingPassword = "Det saknas lösenord!";
    private $missingRePassword = "Vänligen upprepa lösenord!";

    public function getHTML(){
        $form = $this->getForm();

        return "
            <!DOCTYPE html>
				<html lang='en'>
				  <head>
				    <meta charset='utf-8'>
				    <title>bloggen</title>
				    <link rel='stylesheet' href='css/bootstrap/css/bootstrap.css'>
				    <link rel='stylesheet' href='css/bloggen.css'>
				  </head>
				  <body>
				  <div class='pageDiv'>
	                $form
                    </div>
				  </body>
				</html>";
    }

    private function getForm(){
        return "
            <div id='registerFormDiv'>
				<h2>Registrering</h2>
				<div id='registerError'>
					<ul>" . $this -> errorMessages . "</ul>
				</div>
				<form id='registerForm' method='post' role='form'>
					<input type='text' name='" . self::USERID_FIELD . "' id='" . self::USERID_FIELD . "' placeholder='Användarnamn'>
					</br>
					<input type='password' name='" . self::PASSWORD_FIELD . "' id='" . self::PASSWORD_FIELD . "' placeholder='Lösenord'>
					</br>
					<input type='password' name='" . self::REPASSWORD_FIELD . "' id='" . self::REPASSWORD_FIELD . "' placeholder='Upprepa lösenord'>
					</br>
					<input type='submit' id='registerButton' name='registerButton' value='Registrera'/>
				</form>
				<a href='sign-in.php'>Tillbaka till inlogggning</a>
			</div>
        ";
    }

    public function isPostback(){
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
            //var_dump($this->getInputValue(self::REPASSWORD_FIELD));
            return true;
        } else {
            return false;
        }
    }

    public function validateFields(){
        $usernameSet = $this->validateField(self::USERID_FIELD);
        $passwordSet = $this->validateField(self::PASSWORD_FIELD);
        $rePasswordSet = $this->validateField(self::REPASSWORD_FIELD);
        if(!$usernameSet || !$passwordSet || !$rePasswordSet){
            return false;
        }
        return true;
    }

    //Kontrollerar att den angivna fältet inte är tomt.
    private function validateField($field){
        if(isset($_POST[$field])){
            if($_POST[$field] != ""){
                return true;
            }else{
                $this->setErrorMessage($field);
                return false;
            }
        } else {
            $this->setErrorMessage($field);
            return false;
        }
    }

    //Hämtar input från det angivna fältet
    public function getInputValue($field){
        return $_POST[$field];
    }

    private function setErrorMessage($field){
        switch($field){
            case self::USERID_FIELD:
                $this->errorMessages .= "<li>Skriv in användarnamn</li>";
                break;
            case self::PASSWORD_FIELD:
                $this->errorMessages .= "<li>Skriv in lösenord</li>";
                break;
            case self::REPASSWORD_FIELD:
                $this->errorMessages .= "<li>Skriv in lösenord igen</li>";
                break;
        }

    }

    //Den här är för att skicka in fel utifrån vyn
    public function addErrors($errors){
        foreach($errors as $error){
            $this->errorMessages .= "<li>" . $error. "</li>";
        }
    }
}