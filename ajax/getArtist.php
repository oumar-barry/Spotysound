<?php 
    require '../config/config.php';
    if(isset($_POST['id'])){
        $q = $db->prepare("SELECT * FROM artists WHERE id = :id");
        $q->bindParam("id",$_POST['id']);
        $q->execute();
        $artist = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();
        echo json_encode($artist);
    } else {
        echo "The artist id param was not passed in ";
    }

    



?>