<?php
class Home extends Router{
    public function index() {
        /* 
         * Read HTTP Request Body {JSON Data}
         * > $this->req->body
         * > Example: $this->req->body->firstName
         * 
         * Validate HTTP Request Method 
         * > $this->req->method('post');
         * 
         * Helpers Usage
         * > $obj = $this->helper('helper_name');
         * > $obj->func_name();
         * 
         * 
         * Model Usage
         * > $model = $this->model('DefaultModel');
         * > $model->modelTest();
         */

        $this->req->method('post');
        $this->res->status(200)->json([
            'response' => true,
            'msg' => 'Route -> /Home/index',
            'decode' => $decoded
        ]);
    }

    public function test($name) {
        // JWT Example
        try {
            $jwt = JWT::encode([
                'memberId' => 1134
            ], 'secret_key', 'HS512');
            $decoded = JWT::decode($jwt, 'secret_key', array('HS512'));
        } catch(Exception $e){
            $this->res->status(422)->json([
                'response' => false,
                'msg' => 'Invalid Key'
            ]);
        }
        
        $this->res->status(200)->json([
            'response' => true,
            'msg' => 'Route -> /Home/test/'.$name
        ]);  
    }
}