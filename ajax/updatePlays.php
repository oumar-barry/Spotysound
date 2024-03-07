<?php 
    require '../config/config.php';
    if(isset($_POST['id'])){
        $q = $db->prepare("UPDATE songs SET plays = plays + 1 WHERE id = :id");
        $q->bindParam("id",$_POST['id']);
        $q->execute();
        $song = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();
    } else {
        echo "The song id param was not passed in ";
    }

    



?>