<?php

class Model { 
    public $db;
    public $res;
    
    public function __construct() {
        $this->db = new MySQL\Database();
        $this->res = new Response;
    }

    public function helper($name) {
        if (file_exists('app/helpers/'.$name.'.php')) {
            require_once 'app/helpers/'.$name.'.php';
            return new $name;
        } else {
            http_response_code(500);
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Model Name'
            ]));
        }
    }
} 