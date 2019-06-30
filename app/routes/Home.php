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

        $this->req->method('get');
        $this->res->status(200)->json([
            'response' => true,
            'msg' => 'Route -> /Home/index'
        ]);
    }

    public function encode() {
        // JWT Encodeing Example
        $jwt = JWT::encode([
            'memberId' => 1134,
            'iat' => time(), //Issued at
            'nbf' => time() + 60, //Not Before 
            'exp' => time() + (2 * 60) //Expiration Time
        ], 'secret_key');
        $this->res->status(200)->json([
            'response' => true,
            'jwtEncoded' => $jwt
        ]);  
    }

    public function decode() {
        try {
            $decode = JWT::decode($this->req->body->token, 'secret_key');
        } catch (Exception $e) {
            $this->res->status(401)->json([
                'response' => false,
                'msg' => 'Invalid Token'
            ]); 
        }
        $this->res->status(200)->json([
            'response' => true,
            'jwtDecoded' => $decode
        ]); 
    }
}
