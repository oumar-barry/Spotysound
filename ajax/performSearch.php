<?php 
    require '../config/config.php';
    require '../classes/Album.php';
    require '../classes/Artist.php';
    require '../classes/Song.php';
    require '../helpers/function.php';
    if(isset($_POST['term']) and isset($_POST['type'])){
        $type = $_POST['type'];
        $term = $_POST['term'];
        $content = "";

        if($type == 'songs'){
            $songs = getSongsBySearchTerms($term);
            $songIds = [];
            
            if(sizeof($songs) != 0) {
                $content = "";
                foreach($songs as $song){
                    $songIds[] = $song->id;
                    $song = new Song($db,$song->id);
                    $artist = $song->Artist();
                    $content .= renderSongTemplate($song,$artist,true);
                }

                $data = [
                    "content" => $content,
                    "songIds" => $songIds
                ];
                
                echo json_encode($data);

            } else {
                $data = [
                    "content" => "<span class='no-result'>No results found </span>",
                    "songIds" => []
                ];
                echo json_encode($data);
            }
        } else if($type == 'albums'){
            
            $albums = getAlbumsBySearchTerm($term);
            if(sizeof($albums) != 0){
                foreach($albums as $album){
                    $content .= albumTemplate($album);
                }
            } else {
                $content = "<span class='no-result'>No results found </span>";
            }
            
            $data = ["content" => $content];
            echo json_encode($data);
        } else if($type == 'artists'){
            $artists = getArtistsBySearchTerm($term);
            
            if(sizeof($artists) != 0){
                foreach($artists as $artist){
                    $content .= renderArtistTemplate($artist);
                }
            } else {
                $content = "<span class='no-result'>No results found </span>";
            }

            $data = ["content" => $content];
            echo json_encode($data);
        }




    } else {
        echo "Search term or type was not passed in  ";
    }

    



?>