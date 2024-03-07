<?php 
    require '../config/config.php';
    if(isset($_POST['name'])){
        if(trim($_POST['name']) != ""){
            $q = $db->prepare("INSERT INTO playlists VALUES (null,:name,:owner,:date) ");
            $q->bindParam("name",$_POST['name']);
            $q->bindParam("owner",$_SESSION['username']);
            $q->bindParam("date",date("Y-m-d H:i:s"));
            $q->execute();
            $q->closeCursor();
        }

        
        
    } else {
        echo "The playlist name was not passed in  ";
    }

    



?>