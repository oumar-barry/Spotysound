<?php 
    require '../config/config.php';
    if(isset($_POST['playlistId'])){
        
        $q = $db->prepare("DELETE FROM playlists WHERE id = :id ");
        $q->bindParam("id",$_POST['playlistId']);
        $q->execute();
        $q->closeCursor();
     
    } else {
        echo "The playlist name was not passed in  ";
    }

    



?>