<?php

class Account{

    private $con;
    private $errorArray = array();

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function register($userName,$email,$password,$password2){
        $this->validateUserName($userName);
        $this->validateEmail($email);
        $this->validatePassword($password,$password2);

        if(empty($this->errorArray)){
            return $this->insertUserDetails($userName,$email,$password);
        }

        return false;
    }

    public function login($userName,$password){
        $password = hash("sha512",$password);

        $query = $this->con->prepare("SELECT * FROM USER_MASTER WHERE UM_USERNAME=:username AND UM_PASSWORD=:pass");

        $query->bindValue(":username",$userName);
        $query->bindValue(":pass",$password);

        $query->execute();

        if($query->rowCount() == 1){
            return true;
        }

        array_push($this->errorArray,Constants::$loginFailed);
        return false;
    }

    private function insertUserDetails($userName,$email,$password){
        $password = hash("sha512",$password);

        $query = $this->con->prepare("INSERT INTO USER_MASTER (UM_USERNAME,UM_EMAIL,UM_PASSWORD)
                                        VALUES (:username,:email,:pass)");

        $query->bindValue(":username",$userName);
        $query->bindValue(":email",$email);
        $query->bindValue(":pass",$password);

        return $query->execute();
    }

    private function validateUserName($userName){
        if(strlen($userName) < 5 || strlen($userName) > 30){
            array_push($this->errorArray,Constants::$userNameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM USER_MASTER WHERE UM_USERNAME=:userName");
        $query->bindValue(":userName",$userName);
        $query->execute();

        if($query->rowCount()!= 0){
            array_push($this->errorArray,Constants::$userNameExixts);
        }
    }

    private function validateEmail($email){
        if(strlen($email) > 50){
            array_push($this->errorArray,Constants::$emailCharacters);
        }

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$invalidEmail);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM USER_MASTER WHERE UM_EMAIL=:email");
        $query->bindValue(":email",$email);
        $query->execute();

        if($query->rowCount()!= 0){
            array_push($this->errorArray,Constants::$emailExixts);
        }
    }

    private function validatePassword($password,$password2){
        if($password != $password2){
            array_push($this->errorArray,Constants::$passwordUnmatch);
            return;
        }
        $uppercase = preg_match('@[A-Z]@',$password);
        $lowercase = preg_match('@[a-z]@',$password);
        $digit = preg_match('@[0-9]@',$password);
        $special = preg_match('@[^\w]@',$password);
        if(!$uppercase || !$lowercase || !$digit || !$special ||strlen($password) < 8){
            array_push($this->errorArray,Constants::$passwordCharacters);
        }
    }

    public function getError($error){
        if(in_array($error,$this->errorArray)){
            return "<span class='errorMessage'>$error</span>";
        }
    }

}

?>