<?php

class Model { 
    public $db;
    public $response;
    
    public function __construct() {
        $this->db = new MySQL\Database();
        $this->response = new Response;
    }

    public function helper($name) {
        if (file_exists('app/helpers/'.$name.'.php')) {
            require_once 'app/helpers/'.$name.'.php';
            return new $name;
        } else {
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Model Name'
            ]));
        }
    }
} 