<?php 

class Artist {
    
    public function __construct($db,$artist){
        $this->db = $db;
        if(!is_array($artist)){
            $q = $this->db->prepare("SELECT * FROM artists WHERE id = :id ");
            $q->bindParam("id",$artist);
            $q->execute();
            $this->data = $q->fetch(PDO::FETCH_OBJ);
            $q->closeCursor();
        } else {
            $this->data = $artist;
        }
        
    }

    public function Id(){
        return $this->data->id;
    }

    public function Name(){
        return $this->data->name;
    }

    public function numAlbums(){
        $q = $this->db->prepare("SELECT DISTINCT(album) FROM songs WHERE artist = :id ");
        $q->bindParam('id',$id);
        $id = $this->Id();
        $q->execute();
        $total = $q->rowCount();
        $q->closeCursor();
        return $total;
    }

    public function popularSongs(){
            $q = $this->db->prepare("SELECT * FROM songs WHERE artist = :id ORDER BY plays DESC ");
            $q->bindParam("id",$id);
            $id = $this->Id();
            $q->execute();
            $songs = $q->fetchAll(PDO::FETCH_OBJ);
            $q->closeCursor();
            return $songs;
    }
    
    public function allAlbums(){
        $q = $this->db->prepare("SELECT * FROM albums WHERE artist = :id ");
        $q->bindParam("id",$id);
        $id = $this->Id();
        $q->execute();
        $albums = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $albums;
    }



}




?>