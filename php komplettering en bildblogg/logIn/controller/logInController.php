<?php

require_once 'logIn/view/logInView.php';
require_once 'logIn/model/logInModel.php';

Class LogInController{

    private $logInModel;
    private $logInView;

    public function __construct(){
        $this->logInModel = new LogInModel();
        $this->logInView = new LogInView();
    }

    public function getHTML($session){
        $this->checkLogIn($session);
        return $this->logInView->getHTML();
    }

    //Loggar in användaren ifall den uppgett korrekta uppgifter, annars vidarebefodrar den fel till vyn.
    public function checkLogIn($session){
        if($this->logInView->isPostback()){
            if($this->logInView->checkLogIn()){
                $username = $this->logInView->getUsername();
                $password = $this->logInView->getPassword();
                if($this->logInModel->checkLogIn($username, $password)){
                    $session->createLogInSession($username);
                    header("Location:bloggen.php");
                }else{
                    $this->logInView->addErrors(logInView::WRONG_FIELD_ERROR);
                }
            }
        }
    }
}