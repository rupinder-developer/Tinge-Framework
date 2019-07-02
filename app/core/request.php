<?php

class Request {

    public $body;
    public $json;
    public $urlencoded;

    public function  __construct() {
        $this->body = file_get_contents('php://input');
        $this->json = json_decode($this->body);
        parse_str($this->body, $this->urlencoded);
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