<?php 
    require "../classes/Constants.php";
    require '../config/config.php';
    require '../helpers/function.php';
    require '../classes/Account.php';

    if(!empty($_POST['oldPassword']) && 
        !empty($_POST['newPassword']) &&
        !empty($_POST['confirmPassword']) 
    ){
        extract($_POST);  

        // do the checking before updating ...
        $account = new Account($db);
        $success = $account->validateProfilePassword($oldPassword,$newPassword,$confirmPassword);

        if($success){
            $q = $db->prepare("UPDATE users SET password = :pwd WHERE username = :un LIMIT 1");

            $pwd = password_hash($newPassword,PASSWORD_BCRYPT);
            $q->bindParam("un", $_SESSION['username']);
            $q->bindParam("pwd", $pwd);
            
            $q->execute();
    
            $code = 0;
            $msg = "Password updaated successfully";

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