<?php

class Request {
    
    public $json;
    public $urlencoded;

    public function body() {
        return file_get_contents('php://input');
    }

    public function parseJSON() {
        $this->json = json_decode(file_get_contents('php://input'));
    }

    public function parseUrlencoded() {
        parse_str(file_get_contents('php://input'), $this->urlencoded);
        $this->urlencoded = (object) $this->urlencoded;
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