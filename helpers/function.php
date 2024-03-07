<?php

    function usernameAlreadyExist($un){
        global $db;
        $q = $db->prepare("SELECT id FROM users WHERE username = :un ");
        $q->bindParam("un",$un);
        $q->execute();
        return $q->rowCount() != 0;

    }

    function usernameExists($un){
        global $db;
        $q = $db->prepare("SELECT id FROM users WHERE username = :un AND email != :em ");
        $q->bindParam("un",$un);
        $q->bindParam("em",$_SESSION['email']);
        $q->execute();
        return $q->rowCount() != 0;
    }

    function emailAlreadyTaken($em){
        global $db;
        $q = $db->prepare("SELECT id FROM users WHERE email = :em ");
        $q->bindParam(":em",$em);
        $q->execute();
        return $q->rowCount() != 0;

    }

    function emailExists($em){
        global $db;
        $q = $db->prepare("SELECT id FROM users WHERE email = :em AND username != :un ");
        $q->bindParam(":em",$em);
        $q->bindParam(":un",$_SESSION['username']);
        $q->execute();
        return $q->rowCount() != 0;
    }

    function isCorrectPassword($oldPwd){
        global $db;
        $q = $db->prepare("SELECT * FROM users WHERE username = :un");
        $q->bindParam(":un",$_SESSION['username']);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_OBJ);
        return password_verify($oldPwd, $row->password);
    }

    function insertNewUser($fn,$ln,$un,$em,$pw){
        global $db;
        
        $q = $db->prepare("INSERT INTO users  VALUES (null,:un,:fn,:ln,:em,:pw,:sDate,:pic) ");
        $q->bindParam(":un",$un);
        $q->bindParam(":fn",$fn);
        $q->bindParam(":ln",$ln);
        $q->bindParam(":em",$em);
        $q->bindParam(":pw",$pw);
        $q->bindParam(":sDate",$signUpDate);
        $q->bindParam(":pic",$profilePic);

        $pw = password_hash($pw,PASSWORD_BCRYPT);
        $signUpDate = date("Y-m-d H:i:s");
        $profilePic = "assets/images/profilePic.png";
        
        return $q->execute();
        
    }

    function redirect($url){
        header("Location: " .$url.".php");
        exit();
    }

    function echap($string){
        $string = trim($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    function createUserSession(){
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['email'] = $_POST['email'];

    }

    function getValue($field){
        if(isset($_POST[$field])){
            return echap($_POST[$field]);
        }
    }

    function findUserBycredentails($credentials){
        global $db;
        
        $q = $db->prepare("SELECT * FROM users WHERE username = :credentials OR email = :credentials ");
        $q->bindParam(":credentials",$credentials);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();
        
        return $row;

    }

    function getRandomSongs(){
        global $db;
        $q = $db->prepare("SELECT id FROM songs ORDER BY RAND() LIMIT 10");
        $q->execute();
        $songs = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        $Ids = [];
        foreach($songs as $song){
            $Ids[] = $song->id;
        }

        return $Ids;

    }

    function getRandomAlbums(){
        global $db;
        $q = $db->prepare("SELECT * FROM albums ORDER BY RAND() LIMIT 15 ");
        $q->execute();
        $albums = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        
        $content = "";
        foreach($albums as $album){
            $content .= albumTemplate($album);
        }

        return $content;


    }

    function albumTemplate($album){
        return "<span class='album' onclick='openPage(\"album.php?id=".$album->id."\")'>
                    <div class='artwork'>
                        <img src='".$album->artworkPath."' alt='image of an album '>
                    </div>
                    <div class='details'>
                        <span class='name'>
                            ".$album->title."
                        </span>
                    </div>
                </span>";
    }

    function getUserData($username){
        global $db;
        $q = $db->prepare("SELECT * FROM users WHERE username = :username ");
        $q->bindParam(":username",$username);
        $q->execute();
        $user = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $user;
    }

    function renderSongTemplate($song,$artist,$showThumbnail = false, $position = ""){
        global $db;
        
        if(!is_object($song) && !is_array($song) ) $song = new Song($db,$song->id);

        

        if($showThumbnail){
            $album = $song->Album();
            $thumbnail =  "<img src='".$album->artworkPath()."'>";    
        } else {
            $thumbnail = "<img src='assets/images/icons/play.png'>";
        }

        if($position){
            $position = "data-position='$position'";
        } 
        
        return "
                <div class='song' id='".$song->Id()."' $position  onclick='playIt(".$song->Id().",tmpPlaylist,true)' >
                    <div class='thumbnail'>
                        ".$thumbnail."
                    </div>
                    <div class='details'>
                        <span class='title'>".$song->Title()."</span> 
                        <span class='artist'>".$artist->Name()."</span>
                        <span><img id='more' src='assets/images/icons/more.png' alt='more icon' /></span>
                        <input type='hidden' id='songId'>  
                    </div> 
                    <div class='duration'>
                        
                        ".$song->Duration()."
                    </div>
                    
                </div> ";


    }

    function getRecentPlayedSongs(){
        global $db;
        $q = $db->prepare("SELECT * FROM recentlyplayed WHERE username = :username ORDER BY date DESC ");
        $q->bindParam("username",$_SESSION['username']);
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        $songIds = [];
        $content = "<div class='swiper-container'>
                        <div class='swiper-wrapper'>";
        
        foreach($results as $result){
            $song = new Song($db,$result->songId);
            $songIds[] = $song->Id();
            $album = $song->Album();
            $artist = $song->Artist();
            $content .= "<span class='recent swiper-slide'  onclick='setTrack(".$song->Id().",tmpPlaylist,true)' >
                            
                            <img src='".$album->artworkPath()."' alt='image of the song '>
                          
                            <div class='details'>
                                <span class='title'>
                                    ".$song->Title()."
                                </span>
                                <span class='artist'>
                                    ".$artist->Name()."
                                </span>
                            </div>
                            <span class='play'><i class='fas fa-play'></i></span>
                        </span>";
        }
        $content .= "</div>
                        </div>";
        

        return ["content" => $content, "songIds" => $songIds];

    }

    
    function getSongsBySearchTerms($term){
        global $db;
        $q = $db->prepare("SELECT * FROM songs WHERE title LIKE :term ");
        $term = "%".$term."%";
        $q->bindParam(":term",$term);
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $results;

    }

    function getAlbumsBySearchTerm($term){
        global $db;
        $q = $db->prepare("SELECT * FROM albums WHERE title LIKE :term ");
        $term = "%".$term."%";
        $q->bindParam(":term",$term);
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $results;
    }

    function getArtistsBySearchTerm($term){
        global $db;
        $q = $db->prepare("SELECT * FROM artists WHERE name LIKE :term ");
        $term = "%".$term."%";
        $q->bindParam(":term",$term);
        $q->execute();
        $results = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $results;
    }

    function renderArtistTemplate($artist){
        global $db;
        $artist = new Artist($db,$artist->id);
        return "<div class='artist' onclick='openPage(\"artist.php?id=".$artist->Id()."\")'>
                        <img src='assets/images/profilePic.png' alt='artist image '>
                        <span > ".$artist->Name()." </span>    
                </div>";
    }

    function showOptionsMenu($operation = 'addTo'){
        global $db;
        if($operation == 'addTo'){
        
            $content = "<div class='options'>
                            <input type='hidden' id='songId' /> 
                            <select id='playlist'>
                            <option value=''>Add to playlist</option> ";

            $q = $db->prepare("SELECT * FROM playlists WHERE owner = :username ");
            $q->bindParam("username",$_SESSION['username']);
            $q->execute();
            $playlists = $q->fetchAll(PDO::FETCH_OBJ);
            $q->closeCursor();
            
            if($playlists){
                foreach($playlists as $playlist){
                    $content .= "<option value='".$playlist->id."'>".$playlist->name."</option>";
                }
            }

            $content .= "</select></div>";
        
        } else if ($operation == 'removeFrom'){
            $content = "<div class='options remove'>
                            <span class='RemoveFrom'>Remove</span>
                        </div>";
        }
        return $content;

    }

    function getPlaylists(){
        global $db;
        $q = $db->prepare("SELECT * FROM playlists WHERE owner = :username ");
        $q->bindParam("username",$_SESSION['username']);
        $q->execute();
        $playlists = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        $content = "";
        
        if($playlists){
            foreach($playlists as $playlist){
                $content .= renderPlaylistTemplate($playlist);
            }
        }

        return $content;
    }

    function renderPlaylistTemplate($playlist){
        global $db;
        $playlist = new Playlist($db,$playlist);

        return "<div class='playlist' onclick='openPage(\"playlist.php?id=".$playlist->Id()."\")'>
                    <img src='assets/images/icons/playlist_.png' alt='playlist image '>
                    <span class='name'>".$playlist->Name()."</span>
                </div>";

    }








?>