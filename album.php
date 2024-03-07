<?php 
    require './includes/includedFiles.php'; 
    if(!isset($_GET['id'])) redirect('index');
    $album = new Album($db,$_GET['id']);
    $artist = $album->artist();
    

?>
    <!-- <h5>Album</h5> -->
    <div class="albums">
       <div class="album-container">
            <div class="album-cover">
                <img src="<?= $album->artworkPath()  ?>" alt="Album cover photo">
                <div class="album-details">
                    <span class="album-name"> <?= $album->Title() ?>  </span>
                    <span class="artist-name" onclick="openPage('artist.php?id=<?= $artist->Id() ?>')"  > By <?= $artist->Name() ?> </span>
                    <span class="num-songs"> <?= count($album->Songs()) ?> Songs</span>
                    
                </div>
                <button class="play-first" onclick="playFirstSong()" ><i class='fas fa-play'></i></button>
            </div>
            
            <div class="album-songs">
                <?php 
                    $songIds = [];
                    $content = "";
                    foreach($album->Songs() as $song){
                        $songIds[] = $song->id;
                        $song = new Song($db,$song->id);
                        $content .= renderSongTemplate($song,$artist,true);
                    }

                    echo $content;
                ?>
            </div>

       </div>
        
    </div>  
    
    <?= showOptionsMenu() ?>

    

    <script>
        tmpPlaylist = JSON.parse('<?= json_encode($songIds) ?>');
        
        
    </script>
            


        


