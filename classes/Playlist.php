<?php

class Playlist{

    private $db;
    public function __construct($db, $playlist){
        $this->db = $db;

        if(!is_object($playlist)){
            $q = $this->db->prepare("SELECT * FROM playlists WHERE id = :id ");
            $q->bindParam("id", $playlist);
            $q->execute();
            $this->data = $q->fetch(PDO::FETCH_OBJ);
            $q->closeCursor();

        } else {
            $this->data = $playlist;
        }
    }

    public function Id(){
        return $this->data->id;
    }

    public function Name(){
        return $this->data->name;
    }

    public function Owner(){
        return $this->data->owner;
    }

    public function dateCreated(){
        return $this->data->dateCreated;
    }

    public function exists(){
        return !empty($this->data);
    }

    public function notMine(){
        return $this->owner() != $_SESSION['username'];
    }

    public function getSongs(){
        $q = $this->db->prepare("SELECT * FROM playlistsongs WHERE playlistId = :id ORDER BY playlistOrder ");
        $q->bindParam("id",$id);
        $id = $this->Id();
        $q->execute();
        $songs = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $songs;
    }
    

}



?>