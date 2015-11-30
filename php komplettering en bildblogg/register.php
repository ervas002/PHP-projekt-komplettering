<?php

require_once 'session/session.php';
require_once 'register/controller/registerController.php';

$session = new Session();
if($session->isLoggedIn()){
    $session->logOut();
}
$registerController = new RegisterController();
echo $registerController->getHTML();