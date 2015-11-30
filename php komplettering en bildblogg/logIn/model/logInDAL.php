<?php

require_once 'database/db.php';

Class LogInDAL{

    public function checkLogIn($username, $password){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $rows = $dbConnection->query("CALL checkLogIn('$username', '$password')");
            while($row = $rows->fetch_object()){
                if(isset($row->Id)){
                    $dbConnection->close();
                    return true;
                }
            }
            $dbConnection->close();
            return false;
        }
    }

    public function getSaltOnUser($username){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $rows = $dbConnection->query("CALL getSaltOnUser('$username')");
            $salt = "";
            while($row = $rows->fetch_object()){
                $salt = $row->SALT;
            }
            $dbConnection->close();
            return $salt;
        }
    }


}