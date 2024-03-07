<?php 
    require '../config/config.php';
    if(isset($_POST['id'])){
        $q = $db->prepare("SELECT id FROM recentlyplayed WHERE username = :username AND songid = :id ");
        $q->bindParam("id",$_POST['id']);
        $q->bindParam("username",$_SESSION['username']);
        $q->execute();
        
        if($q->rowCount() > 0 ){
            $q = $db->prepare("UPDATE recentlyplayed SET date = :date WHERE songid = :id AND username = :username");
            $q->bindParam("id",$_POST['id']);
            $q->bindParam("username",$_SESSION['username']);
            $q->bindParam("date",date("Y-m-d H:i:s"));
            $q->execute();
            $q->closeCursor();

        } else {
            $q = $db->prepare("INSERT INTO recentlyplayed (songid,username,date) VALUES (:songid,:username,:date) ");
            $q->bindParam("songid",$_POST['id']);
            $q->bindParam("username",$_SESSION['username']);
            $q->bindParam("date",date("Y-m-d H:i:s"));
            $q->execute();
            $q->closeCursor();
        
        }

        
    } else {
        echo "The song id param was not passed in ";
    }

    



?>