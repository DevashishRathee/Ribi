<?php

class PreviewProvider{

    private $con;
    private $username;

    public function __construct($con,$username)
    {
        $this->con = $con;
        $this->username = $username;
    }
}

?>