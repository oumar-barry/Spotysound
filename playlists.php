<?php require './includes/includedFiles.php'; ?>
   <h5>Playlist</h5>
   <div class='buttons'>
      <button class='new-playlist' onclick="createPlaylist()" >New playlist</button>
   </div>
    <div class="playlists">
      <?= getPlaylists(); ?>
        
    </div>  

        


