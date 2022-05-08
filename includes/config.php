<?php
ob_start(); // turns on output buffering
session_start();

date_default_timezone_set("Asia/Kolkata");

//trying to connect to db here

try{
    $con = new PDO("mysql:dbname=ribidb;host=localhost","root","admin@ribidb");
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
}
catch(PDOException $e){
    exit("Connection Failed : " . $e->getMessage());
}

?>