<?php

class Response {
    public static $response;

    public function res($status, $code, $data){
        self::$response = new stdClass();
        self::$response->status = $status;
        self::$response->code = $code;
        if($status == "Error"){
            self::$response->message = $data;
        } else {
            self::$response->data = $data;
        }
        return json_encode(self::$response, JSON_PRETTY_PRINT);
    }
}