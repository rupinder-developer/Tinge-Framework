<?php

class App {
    
    protected $route;

    protected $method;

    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();
        if (file_exists('app/routes/'.$url[0].'.php')) {
            $this->route = $url[0];
            unset($url[0]);
        } else {
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Route'
            ]));
        }

        if (file_exists('app/routes/'.$this->route.'.php')) {
            require_once 'app/routes/'.$this->route.'.php';
            $this->route = new $this->route;    
        } else {
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Route'
            ]));
        }
       
        if (isset($url[1])) {
            if (method_exists($this->route, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        
        if (isset($this->method)) {
            try {
                call_user_func_array([$this->route, $this->method], $this->params);
            } catch (ArgumentCountError $e) {
                die(json_encode([
                    'response' => false,
                    'msg' => 'Invalid Route'
                ]));
            }
        } else {
            die(json_encode([
                'response' => false,
                'msg' => 'Invalid Route'
            ]));
        }     
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = explode('/',rtrim($_GET['url'], '/'));
            return $url;
        }
    }
}