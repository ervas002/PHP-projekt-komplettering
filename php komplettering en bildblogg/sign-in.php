<?php

require_once 'session/session.php';
require_once 'logIn/controller/logInController.php';

$session = new Session();
if($session->isLoggedIn()){
    $session->logOut();
}
$logInController = new LogInController();
echo $logInController->getHTML($session);