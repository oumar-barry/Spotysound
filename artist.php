<?php 
    require './includes/includedFiles.php'; 
    if(!isset($_GET['id'])) redirect('index');
    $artist = new Artist($db,$_GET['id']);
    
?>
    
    <div class="albums">
       <div class="album-container">
            <div class="album-cover">
                <img src="assets/images/artwork/sweet.jpg" alt="Album cover photo">
                <div class="album-details">
                    <span class="album-name"> <?= $artist->Name() ?>  </span>
                    <span class="num-songs"> <?= $artist->numAlbums()  ?> album<?= $artist->numAlbums() != 1 ? 's':'' ?>  </span>
                    
                </div>
                <button class="play-first" onclick="playFirstSong()" ><i class='fas fa-play'></i></button>
                
            </div>
            <div class="sub-menu">
                <a href="#" data-target='popular' class='active' >Popular Songs</a>
                <a href="#" data-target='album-container' >Albums</a>
            </div>
            <div class='sub-container'>
                <div class='popular active'>
                    <?php 
                        $songIds = [];
                        $content = "";
                        foreach($artist->popularSongs() as $song){
                            $songIds[] = $song->id;
                            $song = new Song($db,$song->id);
                            $content .= renderSongTemplate($song,$artist,true);
                        }

                        echo $content;
                    ?>
                </div>

                <div class='album-container'>
                    <?php 
                        $content = "";
                        foreach($artist->allAlbums() as $album){
                            $content .= albumTemplate($album);
                        }

                        echo $content;
                    ?>
                </div>
            </div>
            
          
       </div>
        
    </div>    
    <?= showOptionsMenu() ?>

    

    <script>
        tmpPlaylist = JSON.parse('<?= json_encode($songIds) ?>');
        
    </script>
            


        


