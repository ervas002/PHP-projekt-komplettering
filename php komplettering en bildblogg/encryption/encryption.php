<?php

Class Encryption{

    const SALT_LENGTH = 10;

    public function GetSalt(){
        $salt = mcrypt_create_iv(self::SALT_LENGTH, MCRYPT_DEV_RANDOM);
        return base64_encode($salt);
    }

    public function HashPassword($password, $salt){
        return crypt($password, $salt);
    }
}