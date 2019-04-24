<?php

namespace App;

class Response {
    public static function transform($data, $message, $status){
        return array('message' => $message, 'status' => $status, 'data' => $data);
    }
}