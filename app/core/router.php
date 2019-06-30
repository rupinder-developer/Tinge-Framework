<?php

class Router { 

    public $req;
    public $res;

    public function __construct() {
        $this->req = new Request;
        $this->res = new Response;
    }
    
    public function model($name) {
        if (file_exists('app/models/'.$name.'.php')) {
            require_once 'app/models/'.$name.'.php';
            return new $name;
        } else {
            header('Content-Type: application/json');
            http_response_code(500);
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Model Name'
            ]));
        }
    }

    public function helper($name) {
        if (file_exists('app/helpers/'.$name.'.php')) {
            require_once 'app/helpers/'.$name.'.php';
            return new $name;
        } else {
            header('Content-Type: application/json');
            http_response_code(500);
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Model Name'
            ]));
        }
    }
} 