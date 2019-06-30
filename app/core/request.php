<?php

class Request {

    public $body;
    public $json;

    public function  __construct() {
        $this->body = file_get_contents('php://input');
        try {
            $this->json = file_get_contents('php://input');
        } catch (Exception $e) {
            $this->json = null;
        }
        
    }
    
    public function method($method) {
        if ($_SERVER["REQUEST_METHOD"] != strtoupper($method)) {
            header('Content-Type: application/json');
            http_response_code(404);
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Route'
            ]));
        }
    }
}