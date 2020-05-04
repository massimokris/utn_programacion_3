<?php

class File {
    public $path;
    public $content;

    public function __construct($path) {
        $this->path = $path;
        $this->content = [];
    }

    public function writeJson($data) {
        if(!isset($data)) return false;

        $this->content = self::readJson($this->path);

        array_push($this->content, $data);

        try {
            $fileOpen = fopen($this->path, 'w');
            $dataRead = fwrite($fileOpen, json_encode($this->content));
            fclose($fileOpen);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        return $dataRead > 1 ? true : false;
    }

    public static function readJson($path)
    {
        if(!file_exists($path)) return [];

        $fileSize = 20;

        if(filesize($path) > 1) $fileSize = filesize($path);

        try {
            $fileOpen = fopen($path, 'r');
            $dataRead = fread($fileOpen, $fileSize);
            fclose($fileOpen);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        return $dataRead == '' ? [] : json_decode($dataRead);
    }
    
    public function findOne($email, $password)
    {
        $users = self::readJson($this->path);

        if($users == []) return false;
        
        foreach ($users as $u => $value) {
            if($value->email == $email) {
                if($value->password == $password) {
                    return $value;
                }
            }
        }
        return false;
    }

    public static function saveImage($str, $image){
        $arr = explode(".", $image['name']);
        $dir = './images/' . $str . '.' . end($arr);
        move_uploaded_file($image['tmp_name'], $dir);
        return __DIR__ . $dir;
    }
}