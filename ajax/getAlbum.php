<?php 
    require '../config/config.php';
    if(isset($_POST['id'])){
        $q = $db->prepare("SELECT * FROM albums WHERE id = :id");
        $q->bindParam("id",$_POST['id']);
        $q->execute();
        $album = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();
        echo json_encode($album);
    } else {
        echo "The album id param was not passed in ";
    }

    



?>