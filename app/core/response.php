<?php

class Response {
    public function status($status) {
        http_response_code($status);
        return $this;
    }

    public function json($array, $options = 0) {
        header('Content-Type: application/json');
        die(json_encode($array, $options));
    }

    public function send($response) {
        header('Content-Type: text/plain');
        die($response);
    }
}
