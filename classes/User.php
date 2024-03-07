<?php

class User{

    private $user;
    public function __construct($input){
        if(!is_array($input)){
            $this->user = getUserData($input);
        } else {
            $this->user = $input;
        }
    }

    public function getLastname(){
        return $this->user->lastName;
    }

    public function getFirstname(){
        return $this->user->firstName;
    }

    public function getUsername(){
        return $this->user->username;
    }

    public function getEmail(){
        return $this->user->email;
    }
    

}



?>