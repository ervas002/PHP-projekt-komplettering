<?php

require_once 'database/db.php';

Class RegisterDAL{

    public function addNewUser($username, $password, $salt){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $dbConnection->query("CALL addUser('$username', '$password', '$salt')");
            $dbConnection->close();
        }
    }

    public function isUserNameFree($username){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $rows = $dbConnection->query("CALL isUserFree('$username')");
            if(mysqli_num_rows($rows)>0){
                $dbConnection->close();
                return false;
            }
            $dbConnection->close();
            return true;
        }
        return false;
    }
}