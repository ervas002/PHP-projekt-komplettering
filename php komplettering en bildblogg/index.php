<?php

require_once 'session/session.php';

$session = new Session();
if($session->isLoggedIn()){
    header("Location: bloggen.php");
}else{
    header("Location: sign-in.php");
}
