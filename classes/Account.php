<?php 
class Account{
    private $db;
    public $errors;
    
    public function __construct($db){
        $this->db = $db;
        $this->errors = [];
    }

    public function getFirstError(){
        return $this->errors[0];
    }

    public function getError($err){
        if(!in_array($err,$this->errors)){
            $err = "";
        }

        return "<span class='err-msg'>$err</span>";
    }

    public function register($fn,$ln,$un,$em,$emc,$pw,$pwc){
        $this->validateFirstname($fn);
        $this->validateLastname($ln);
        $this->validateUsername($un);
        $this->validateEmail($em,$emc);
        $this->validatePassword($pw,$pwc);

        if(count($this->errors) == 0){
            $this->insertNewUser($fn,$ln,$un,$em,$pw);
            return true;
        } else {
            return false;
        }



    }

    public function validateProfileData($fn,$ln,$un,$em){
        $this->validateFirstname($fn);
        $this->validateLastname($ln);
        $this->validateProfileUsername($un);
        $this->validateProfileEmail($em);

        return count($this->errors) == 0;
            
        
    }

    public function validateProfilePassword($oldPwd,$newPwd,$confirmPwd){
        
        if(!isCorrectPassword($oldPwd)){
            $this->errors[] = Constants::$oldPwdErr;
            return;
        }

        $this->validatePassword($newPwd,$confirmPwd);

        return count($this->errors) == 0; 


    }

    public function login($credentials,$pw){
        
        if($this->validateCredentials($credentials)){
            $user = findUserBycredentails($credentials);
            
            if(!$user){
                $this->errors[] = Constants::$credentialSerr;
                return false;
            }

            $validPassword = password_verify($pw,$user->password);
            if(!$validPassword){
                $this->errors[] = Constants::$credentialSerr;
            } else {
                return $user;
            }
        }

        return false;

        
    }

    private function validateFirstname($fn){
        // if(empty($fn)){
        //     $this->errors[] = Constants::$emptyErr;
        //     return;
        // }
        

        if(mb_strlen($fn) < 2 || mb_strlen($fn) > 26 ){
            $this->errors[] = Constants::$fnErr;
        }
    }

    private function validateLastname($ln){
        // if(empty($ln)){
        //     $this->errors[] = Constants::$emptyErr;
        //     return;
        // }

        if(mb_strlen($ln) < 2 || mb_strlen($ln) > 26 ){
            $this->errors[] = Constants::$lnErr;
        }
    }

    private function validateUsername($un){
        // if(empty($un)){
        //     $this->errors[] = Constants::$emptyErr;
        //     return;
        // }
        
        if(mb_strlen($un) < 2 || mb_strlen($un) > 26 ){
            $this->errors[] = Constants::$unErr;
            return;
        }

        if(usernameAlreadyExist($un)){
            $this->errors[] = Constants::$unExists;
            return;
        }
    }

    private function validateProfileUsername($un){
        if(mb_strlen($un) < 2 || mb_strlen($un) > 26 ){
            $this->errors[] = Constants::$unErr;
            return;
        }

        if(usernameExists($un)){
            $this->errors[] = Constants::$unExists;
            return;
        }

    }

    private function validateEmail($em,$emc){
        // if(empty($em)){
        //     $this->errors[] = Constants::$emptyErr;
        //     return;
        // }

        if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
            $this->errors[] = Constants::$invalidEm;
            return;
        }

        if($em != $emc){
            $this->errors[] = Constants::$emailsDoNotMatch;
            return;
        }

        if(emailAlreadyTaken($em)){
            $this->errors[] = Constants::$emExists;
            return;
        }
    }

    private function validateProfileEmail($em){
        if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
            $this->errors[] = Constants::$invalidEm;
            return;
        }
        
        if(emailExists($em)){
            $this->errors[] = Constants::$emExists;
            return;   
        }
    }

    private function validatePassword($pw,$pwc){
        // if(empty($pw)){
        //     $this->errors[] = Constants::$emptyErr;
        //     return;
        // }
        
        if(mb_strlen($pw) < 6 || mb_strlen($pw) > 26){
            $this->errors[] = Constants::$pwErr;
            return;
        }
        
        if($pw != $pwc){
            $this->errors[] = Constants::$pwDontMatch;
            return;
        }
    }

    private function insertNewUser($fn,$ln,$un,$em,$pw){
       return insertNewUser($fn,$ln,$un,$em,$pw);

    }

    private function validateCredentials($input){
        if(empty($input)){
            $this->errors[] = Constants::$emptyErr;
            return false;
        }
        return true;

    }
    
        
    


}


?> 