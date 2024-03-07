<?php 
    // $_SERVER['HTTP_X_REQUESTED_WITH']
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        require './config/config.php';
        require './classes/Artist.php';
        require './classes/Album.php';
        require './classes/Song.php';
        require './classes/Playlist.php';

        require './helpers/function.php';
        require './classes/User.php';
        
        
        
        if(isset($_GET['username'])){
            $username = $_GET['username'];
            $user = new User($username);
        } else {
            echo "Username was not passed in correctly ";
        }
        
        

    } else {
        require 'header.php';
        require 'footer.php';

        $url = $_SERVER['REQUEST_URI'];
        echo "<script>openPage('$url')</script>";
        exit();
    }


?>