<?php 
    require '../config/config.php';
    if(isset($_POST['id'])){
        $q = $db->prepare("SELECT * FROM songs WHERE id = :id");
        $q->bindParam("id",$_POST['id']);
        $q->execute();
        $song = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();
        echo json_encode($song);
    } else {
        echo "The song id param was not passed in ";
    }

    



?>