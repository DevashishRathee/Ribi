<?php
require_once("includes/header.php");

if(!isset($_SESSION["userLoggedIn"])){
    header("Location: register.php");
}

$preview = new PreviewProvider($con,$userLoggedIn);
echo $preview->createPreviewVideo(null);
?>
