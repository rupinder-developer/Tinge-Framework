<?php
class Home extends Router{
    public function index() {
        echo 'Route -> /home/index'; 
        /* 
         * > $this->request->has('api_id', $_POST);
         * > $this->request->has('api_id', $_GET);
         * > $this->request->has(['username','password', 'api_id'], $_POST); # AND
         * > $this->request->has(['username','password', 'api_id'], $_POST, 'OR'); # OR
         */

         /*
         * Helpers Usage
         * > $obj = $this->helper('helper_name');
         * > $obj->func_name();
        */
    }

    public function test($name) {
        echo 'Route -> /home/test/'.$name."<br>"; 
        $model = $this->model('DefaultModel');
        $model->modelTest();
    }
}