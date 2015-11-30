<?php

Class LogInView {

    const USERID_FIELD = "usernameInput";
    const PASSWORD_FIELD = "passwordInput";

    const MISSING_FIELD_ERROR = "Vänligen fyll i användarnamn och lösenord.";
    const WRONG_FIELD_ERROR = "Det verkar som att användarnamnet eller lösenordet är fel.";

    private $errorMessages = "";

    //Returnerar HTML för hela sidan.
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
	                    <a href='bloggen.php'>Gå direkt till bloggen utan att logga in.</a>
                    </div>
				  </body>
				</html>";
    }

    //Returnerar inloggningsformuläret.
    public function getForm(){
        return "
           <div id='logInForm'>
           <h2>Logga in</h2>
            <div id='logInError'>
                <ul>" . $this -> errorMessages . "</ul>
            </div>
            <form id='logInForm' method='post' role='form'>
                <input type='text' name='" . self::USERID_FIELD . "' id='" . self::USERID_FIELD . "' placeholder='Användarnamn' value=''>
                </br>
                <input type='password' name='" . self::PASSWORD_FIELD . "' id='" . self::PASSWORD_FIELD . "' placeholder='Lösenord'>
                </br>
                <input type='submit' id='logInButton' name='logInButton' value='Logga in'/>
                </br>
                <p>Ingen inloggning?</p><a href='register.php'> Registrera dig här!</a>
            </form>
        </div>
        ";
    }

    //Kollar ifall det var en postback
    public function isPostback(){
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
            return true;
        } else {
            return false;
        }
    }

    //Kollar så att inloggningsfälten är ifyllda.
    public function checkLogIn(){
        if(isset($_POST[self::USERID_FIELD]) && isset($_POST[self::PASSWORD_FIELD])){
            if($_POST[self::USERID_FIELD] != "" && $_POST[self::PASSWORD_FIELD] != ""){
                return true;
            }
        }
        $this->addErrors(self::MISSING_FIELD_ERROR);
        return false;
    }

    public function getUsername(){
        return $_POST[self::USERID_FIELD];
    }

    public function getPassword(){
        return $_POST[self::PASSWORD_FIELD];
    }

    //Lägger till felmeddelanden för utskrift.
    public function addErrors($error){
        switch($error){
            case self::MISSING_FIELD_ERROR:
                $this->errorMessages .= "<li>" . self::MISSING_FIELD_ERROR . "</li>";
                break;
            case self::WRONG_FIELD_ERROR:
                $this->errorMessages .= "<li>" . self::WRONG_FIELD_ERROR . "</li>";
                break;
        }
    }
}