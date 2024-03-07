<?php
    require "classes/Constants.php";
    require './config/config.php';
    require 'helpers/function.php';
    require 'classes/Account.php';

    $account = new Account($db);
    $showLogin = false;
    $showRegister = true;

    if(isset($_POST["register"])){
        extract($_POST);
        $fn = echap($firstname);
        $ln = echap($lastname);
        $un = echap($username);
        $em = echap($email);
        $emc = echap($email_conf);
        $pw = $password;
        $pwc = $password_conf;
        
        $success = $account->register($fn,$ln,$un,$em,$emc,$pw,$pwc);
        

        if($success){
            
            createUserSession();
            
            redirect("index");
        }

    }

    if(isset($_POST['login'])){
        $showRegister = false;
        $showLogin = true;

        extract($_POST);
        $credentials = echap($credentials);
        $password = echap($password);

        $user = $account->login($credentials,$password);
        if($user){
            $_SESSION['username'] = $user->username;
            redirect("index");
        }


    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/png" href="./favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;1,300&family=Roboto:wght@400;700&family=Source+Sans+Pro:wght@300;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <title>Spotysound</title>
</head>

<body>

    

    <div class="wrapper">
        <div class="overlay"></div>
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="register-section"  style="display: <?= $showRegister ? '': 'none' ?>;"  >
                        <h2>Create an account</h2>    
                            <form action="" method="POST">
                                <div class="fg">
                                    <?= $account->getError(Constants::$emptyErr); ?>
                                    <?= $account->getError(Constants::$fnErr); ?>
                                    <input type="text" name="firstname" value="<?= getValue('firstname') ?>" placeholder="Enter your firstname">
                                    
                                </div>

                                <div class="fg">
                                    <?= $account->getError(Constants::$emptyErr); ?>
                                    <?= $account->getError(Constants::$lnErr); ?>
                                    <input type="text" name="lastname" value="<?= getValue('lastname') ?>" placeholder="Enter your lastname">
                                    
                                </div>

                                <div class="fg">
                                    <?= $account->getError(Constants::$emptyErr); ?>
                                    <?= $account->getError(Constants::$unErr); ?>
                                    <input type="text" name="username" value="<?= getValue('username') ?>" placeholder="Enter your username">
                                    
                                </div>

                                <div class="fg">
                                    <?= $account->getError(Constants::$emptyErr); ?>
                                    <?= $account->getError(Constants::$invalidEm); ?>
                                    <?= $account->getError(Constants::$emailsDoNotMatch); ?>
                                    <?= $account->getError(Constants::$emExists); ?>
                                    <input type="text" name="email"  value="<?= getValue('email') ?>" placeholder="Enter your email">
                                    
                                </div>

                                <div class="fg">
                                    <?= $account->getError(Constants::$emailsDoNotMatch); ?>
                                    <input type="text" name="email_conf"  value="<?= getValue('email_conf') ?>" placeholder="Confirm your email ">
                                    
                                </div>

                                <div class="fg">
                                    <?= $account->getError(Constants::$emptyErr); ?>
                                    <?= $account->getError(Constants::$pwErr); ?>
                                    <input type="password" name="password"  placeholder="Enter your password">
                                    
                                </div>

                                <div class="fg">
                                    <?= $account->getError(Constants::$pwDontMatch); ?>
                                    <input type="password" name="password_conf"  placeholder="Confirm your password ">
                                    
                                </div>

                                <div class="fg">
                                    <input type="submit" name="register" value="Register">
                                </div>

                                <div class="fg">
                                    <span>Already have an account <a href="javascript:void(0)" id="login" >Sign In</a> </span>
                                </div>


                            </form>

                    </div>

                    <div class="login-section" style="display: <?= $showLogin ? '': 'none' ?>;"  >
                        <h2>Login </h2></h2>    
                            <form action="" method="POST" >
                                <div class="fg">
                                    <?= $account->getError(Constants::$emptyErr); ?>
                                    <?= $account->getError(Constants::$credentialSerr); ?>
                                    <input type="text" placeholder="Email or username" name="credentials" value="<?= getValue('credentials') ?>" >
                                    
                                </div>

                                <div class="fg">
                                    <input type="password" name="password" placeholder="Enter your password">
                                    
                                </div>

                                <div class="fg">
                                    <input type="submit" name="login" value="Login">
                                    
                                </div>

                                

                                <div class="fg">
                                <span>Dont' have an account <a id="register" href="javascipt:void(0)">Register now </a> </span>
                                </div>


                            </form>

                    </div>
                </div class="col-md-6">
                    

                <div >
                    
                   
                    
                </div>
            </div>
        </div>
        

        
    </div>


    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script src="assets/js/common.js"></script>



</body>

</html>