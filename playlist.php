<?php require './includes/includedFiles.php'; ?>
<?php 
    if(!isset($_GET['id']) || empty($_GET['id'])){
       echo "<script>openPage('index.php')</script>"; 
       exit();
    }

    $playlist = new Playlist($db,$_GET['id']);
    if(!$playlist->exists() || $playlist->notMine()) {
        echo "<script>openPage('index.php')</script>"; 
        exit();
    }

?>
   <h5><?= $playlist->Name(); ?></h5>
   <div class='buttons'>
      <button class='delete-playlist' onclick="deletePlaylist(<?= $playlist->Id() ?>)" >Delete playlist</button>
   </div>
    <div class="playlist-songs">
        <?php 
           $songIds = [];
           $content = "";
           foreach($playlist->getSongs() as $row){
                $songIds[] = $row->songId;
                $song = new Song($db,$row->songId);
                $artist = $song->Artist();
                $order = $row->playlistOrder;
                $content .= renderSongTemplate($song,$artist,true,$order);   
           }

           echo $content;
        ?>
        
    </div>  

    <?= showOptionsMenu('removeFrom') ?>

    <script>
        tmpPlaylist = JSON.parse('<?= json_encode($songIds) ?>');
        
    </script>

    <script>
        $(function(){
            var data = {
                "indexes" : [],
                "songIds": []
            }

            var playlistId = '<?= $playlist->Id(); ?>';

            $(".playlist-songs").sortable({
                update: function(event,ui){
                    $(this).children().each(function(index){
                        var position = $(this).data('position');
                        var songId = $(this).attr('id');
                        if(position != index + 1 ){
                            data.indexes.push(index + 1);
                            data.songIds.push(songId);

                        }
                    });

                    reorderSongs(playlistId,data);
                    console.log(data);
                    
                }
            });

            
        });
    
    </script>

        