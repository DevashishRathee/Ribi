<?php

require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");


    $account = new Account($con);

    if(isset($_POST["submitButton"])){

        $userName = FormSanitizer::sanitizeFormUserName($_POST["userName"]);
        $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

        $success = $account->register($userName,$email,$password,$password2);

        if($success){
            //store session - track whether user logged in or not
            $_SESSION["userLoggedIn"] = $userName;
            header("Location: index.php");
        }
    }

    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Ribi
        </title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        <div class="loginContainerPage">
            <div class="loginContainer">
                <div class="header-register">
                <div id="logo">    
                <img src="assets/images/ribi-logo.png"/>
                </div>
                <h3 class="medium-heading">Create an account</h3>
                </div>
                <form method="POST">
                    <?php echo $account->getError(Constants::$userNameCharacters) ?> 
                    <?php echo $account->getError(Constants::$userNameExixts) ?>  
                    <input type="text" name="userName" placeholder="UserName" value="<?php getInputValue("userName");?>" required>
                    <?php echo $account->getError(Constants::$emailCharacters) ?>
                    <?php echo $account->getError(Constants::$emailExixts) ?>
                    <?php echo $account->getError(Constants::$invalidEmail) ?>
                    <input type="email" name="email" placeholder="Email" value="<?php getInputValue("email");?>" required>
                    <?php echo $account->getError(Constants::$passwordCharacters) ?>
                    <?php echo $account->getError(Constants::$passwordUnmatch) ?>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password2" placeholder="Confirm Password" required>
                    <div class="subscribe-mail-parent">
                        <div class="subscribe-mail">
                            <input type="checkbox" name="sendmails">
                        </div>
                        <div class="subscribe-mail">(Optional) It’s okay to send me emails with Ribi updates, tips, and special offers. You can opt out at any time.</div>
                    </div>
                    <input type="submit" name="submitButton" value="Continue">
                </form>
                <a href="login.php" class="signInMessage"> Already have an account ? </a>
            </div>
        </div>
    </body>
</html>