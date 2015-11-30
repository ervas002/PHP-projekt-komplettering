<?php

session_start();

Class Session{

    private $logInSession = "logInSession";
    private $security = "security";
    private $usernameSession = "username";

    public function logOut(){
        if(isset($_SESSION[$this->logInSession])){
            unset($_SESSION[$this->logInSession]);
        }
    }

    public function isLoggedIn(){
        if(isset($_SESSION[$this->security])){
            if($_SESSION[$this->security] == $this->getUserAgent() && isset($_SESSION[$this->logInSession]) && $_SESSION[$this->logInSession] == true){
                return true;
            }else{
                unset($_SESSION[$this->logInSession]);
                session_destroy();
                return false;
            }
        }else{
            return false;
        }
    }

    public function createLogInSession($username){
        $_SESSION[$this->logInSession] = true;
        $_SESSION[$this->security] = $this->getUserAgent();
        $this->setUsername($username);
    }

    private function setUsername($username){
        $_SESSION[$this->usernameSession] = $username;
    }

    public function getUsername(){
        return $_SESSION[$this->usernameSession];
    }

    //http://stackoverflow.com/questions/9693574/user-agent-extract-os-and-browser-from-string-php
    private function getUserAgent(){
        $agent = null;

        if ( empty($agent) ) {
            $agent = $_SERVER['HTTP_USER_AGENT'];

            if ( stripos($agent, 'Firefox') !== false ) {
                $agent = 'firefox';
            } elseif ( stripos($agent, 'MSIE') !== false ) {
                $agent = 'ie';
            } elseif ( stripos($agent, 'iPad') !== false ) {
                $agent = 'ipad';
            } elseif ( stripos($agent, 'Android') !== false ) {
                $agent = 'android';
            } elseif ( stripos($agent, 'Chrome') !== false ) {
                $agent = 'chrome';
            } elseif ( stripos($agent, 'Safari') !== false ) {
                $agent = 'safari';
            } elseif ( stripos($agent, 'AIR') !== false ) {
                $agent = 'air';
            } elseif ( stripos($agent, 'Fluid') !== false ) {
                $agent = 'fluid';
            }

        }
        return $agent;
    }
}