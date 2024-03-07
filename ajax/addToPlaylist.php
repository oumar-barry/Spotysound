<?php 
    require '../config/config.php';
    if(isset($_POST['playlistId']) && isset($_POST['songId']) ){
        
        $q = $db->prepare("SELECT playlistsongs.id as id  FROM playlistsongs,users,playlists WHERE users.username = playlists.owner AND playlists.id = playlistsongs.playlistId  AND playlistsongs.songId = :songId AND playlistsongs.playlistId = :playlistId ");
        $q->bindParam("songId", $_POST['songId']);
        $q->bindParam("playlistId", $_POST['playlistId']);
        $q->execute();
        if($q->rowCount() == 0){
            $q = $db->prepare("SELECT playlistOrder FROM playlistsongs WHERE playlistId = :playlistId  ORDER BY playlistOrder DESC LIMIT 1 ");
            $q->bindParam("playlistId", $_POST['playlistId']);
            $q->execute();
            $lastOrder = $q->fetchColumn();
            
            $q = $db->prepare("INSERT INTO playlistsongs VALUES (null,:songId,:playlistId,:order) ");
            $q->bindParam("songId", $_POST['songId']);
            $q->bindParam("playlistId", $_POST['playlistId']);
            $q->bindParam("order",$order);
            $order = $lastOrder + 1;
            $q->execute();
            $q->closeCursor();
           
        }


        
    } else {
        echo "The song id param was not passed in ";
    }

    



?>