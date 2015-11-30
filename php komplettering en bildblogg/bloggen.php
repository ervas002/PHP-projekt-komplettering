<?php

require_once 'blog/controller/blogController.php';
require_once 'session/session.php';

$session = new Session();
// Man ska alltid kunna gå in på bloggen, dock så är det i ett annat läge om man inte är inlogggad
if($session->isLoggedIn()){
    //Skicka ingenstans, men ha istället argument i controllern som skriver ut en sida utan möjlighet till inlägg eller något

    $blogController = new BlogController(true, $session->getUsername());
    $blogController->checkPostback();
    echo $blogController->getHTML();
}else{
    $blogController = new BlogController(false, "");
    //Borde jag verkligen ha den här här?
    $blogController->checkPostback();
    echo $blogController->getHTML();
}
