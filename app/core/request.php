<?php

class Request {

    public $body;

    public function  __construct() {
        $this->body = json_decode(file_get_contents('php://input'));
    }
    
    public function method($method) {
        if ($_SERVER["REQUEST_METHOD"] != strtoupper($method)) {
            http_response_code(404);
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Route'
            ]));
        }
    }
}