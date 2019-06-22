<?php
class Home extends Router{
    public function index() {
        /* 
         * > $this->request->has('api_id', $_POST);
         * > $this->request->has('api_id', $_GET);
         * > $this->request->has(['username','password', 'api_id'], $_POST); # AND
         * > $this->request->has(['username','password', 'api_id'], $_POST, 'OR'); # OR
         * 
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

        $this->response->status(200)->json([
            'response' => true,
            'msg' => 'Route -> /Home/index'
        ]);
    }

    public function test($name) {
        $this->response->status(200)->json([
            'response' => true,
            'msg' => 'Route -> /Home/test/'.$name
        ]);  
    }
}