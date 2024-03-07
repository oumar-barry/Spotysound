<?php 
    require '../config/config.php';
    if(isset($_POST['playlist']) && isset($_POST['data'])){
        $data = json_decode($_POST['data']);
        $indexes = $data->indexes;
        $songIds = $data->songIds;
        $playlist = $_POST['playlist'];

        for($i = 0; $i < sizeof($indexes); $i++){
            $q = $db->prepare("UPDATE playlistsongs SET playlistOrder = :index WHERE songId = :songId  AND playlistId = :playlistId LIMIT 1 ");
            $q->bindParam("index",$indexes[$i]);
            $q->bindParam("songId",$songIds[$i]);
            $q->bindParam("playlistId",$playlist);
            $q->execute();

        }

        $q->closeCursor();

    } else {
        echo "Something went wrong while reordering ";
    }

    



?>