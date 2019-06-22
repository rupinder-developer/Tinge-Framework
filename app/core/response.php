<?php

class Response {
    public function status($status) {
        http_response_code($status);
        return $this;
    }

    public function json($array, $options = 0) {
        die(json_encode($array, $options));
    }
}