<?php

class User {
    public $email;
    public $password;
    public $type;

    public function __construct($email, $password, $type) { 
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
    }

    public static function validUser($email, $path)
    {
        $users = File::readJson($path);

        if($users == []) return true;
        
        foreach ($users as $u => $value) {
            if($value->email == $email) {
                return false;
            }
        }
        return true;
    }

    public static function isAdmin($email, $path) {
        $users = File::readJson($path);

        if($users == []) return false;
        
        foreach ($users as $u => $value) {
            if($value->email == $email) {
                if($value->type == 'encargado') {
                    return true;
                }
            }
        }
        return false;
    }
}