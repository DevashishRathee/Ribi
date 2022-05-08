<?php

require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");


    $account = new Account($con);

    if(isset($_POST["submitButton"])){
        $userName = FormSanitizer::sanitizeFormUserName($_POST["userName"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

        $success = $account->login($userName,$password);

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
            <div class="loginContainer2">
                <div class="header-register">
                <div id="logo">    
                <img src="assets/images/ribi-logo.png"/>
                </div>
                <h3 class="medium-heading">Sign in to your account</h3>
                </div>
                <form method="POST">
                    <?php echo $account->getError(Constants::$loginFailed) ?> 
                    <input type="text" name="userName" placeholder="UserName" value="<?php getInputValue("userName");?>" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" name="submitButton" value="Continue">
                </form>
                <a href="register.php" class="signInMessage"> Need an account ? </a>
            </div>
        </div>
    </body>
</html>