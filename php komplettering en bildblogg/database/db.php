<?php

Class DB{

    private $con;
    public $error = "";

    public function __construct(){

        $this->con = mysqli_connect("localhost", "root", "", "bbusers");

        if (mysqli_connect_errno())
        {
            $this->error = "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    public function isSuccess(){
        if($this->error !== ""){
            return false;
        }
        return true;
    }

    public function getCon(){
        return $this->con;
    }

    public function getError(){
        return $this->error;
    }
}