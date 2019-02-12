<?php

class Router { 

    public $request;

    public function __construct() {
        $this->request = new Request;
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