<?php 

class Album {
    private $data;
    private $db;

    public function __construct($db,$id){
        $this->db = $db;
        $q = $this->db->prepare("SELECT * FROM albums WHERE id = :id ");
        $q->bindValue(":id",$id,PDO::PARAM_INT);
        $q->execute();
        $this->data = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();

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

    public function Genre(){
        return $this->data->genre;
    }

    public function artworkPath(){
        return $this->data->artworkPath;
    }

    public function Songs(){
        $q = $this->db->prepare("SELECT * FROM songs WHERE album = :album ORDER BY albumOrder ");

        $q->bindValue(":album",$this->Id(),PDO::PARAM_INT);
        $q->execute();
        $songs = $q->fetchAll(PDO::FETCH_OBJ);
        $q->closeCursor();
        return $songs;
    }




   

}




?>