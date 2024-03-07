<?php 
    require "../classes/Constants.php";
    require '../config/config.php';
    require '../helpers/function.php';
    require '../classes/Account.php';

    if(!empty($_POST['firstname']) && 
        !empty($_POST['lastname']) &&
        !empty($_POST['username']) &&
        !empty($_POST['email'])
    ){
        extract($_POST);  

        // do the checking before updating ...
        $account = new Account($db);
        $success = $account->validateProfileData($firstname,$lastname,$username,$email);

        if($success){
            $q = $db->prepare("UPDATE users SET firstname = :fn, lastname = :ln, username =:un, email = :em WHERE username = :current_username LIMIT 1");

            $q->bindParam("fn", $firstname);
            $q->bindParam("ln", $lastname);
            $q->bindParam("em", $email);
            $q->bindParam("un", $username);
            $q->bindParam("current_username", $_SESSION['username']);
            
            $q->execute();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            
            $code = 0;
            $msg = "Profile data updaated successfully";

        } else {
            $code = 1;
            $msg = $account->getFirstError();

        }

        
        
    } else {

        $msg = "Please fill out all fields ";
        $code = 1;
        
        
    }

    $data = ["msg" => $msg, "code" => $code]; 
    echo json_encode($data);

    



?>