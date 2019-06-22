<?php

class Router { 

    public $request;
    public $response;

    public function __construct() {
        $this->request = new Request;
        $this->response = new Response;
    }
    
    public function model($name) {
        if (file_exists('app/models/'.$name.'.php')) {
            require_once 'app/models/'.$name.'.php';
            return new $name;
        } else {
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
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Model Name'
            ]));
        }
    }
} 