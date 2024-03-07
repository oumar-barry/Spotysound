<?php 

class Song {
    
    public function __construct($db,$song){
        $this->db = $db;
        if(!is_array($song)){
            $q = $this->db->prepare("SELECT * FROM songs WHERE id = :id ");
            $q->bindParam("id",$song);
            $q->execute();
            $song = $q->fetch(PDO::FETCH_OBJ);
            $q->closeCursor();
        } 
        
        $this->data = $song;
    }


    public function Id(){
        return $this->data->id;
    }

    public function Title(){
        return $this->data->title;
    }

    public function Artist(){
        return new Artist($this->db,$this->data->artist);
    }

    public function Album(){
        return new Album($this->db,$this->data->album);
    }

    public function Duration(){
        return $this->data->duration;
    }

}




?>